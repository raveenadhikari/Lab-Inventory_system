<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Component Profile</h2>
    <div class="row">
        <div class="col-md-4">
            <!-- Photo at the top -->
            <img src="/<?= esc($component['photo']) ?>" alt="Component Photo" class="img-fluid" />
            <hr>
            <!-- QR code -->
            <h5>QR Code:</h5>
            <img src="/<?= esc($component['qr_code']) ?>" alt="QR Code" width="120" />
        </div>
        <div class="col-md-8">
            <table class="table table-borderless">
                <tr>
                    <th>Component Code:</th>
                    <td><?= esc($component['component_code']) ?></td>
                </tr>
                <tr>
                    <th>Name:</th>
                    <td><?= esc($component['name']) ?></td>
                </tr>
                <tr>
                    <th>Category:</th>
                    <td><?= esc($categoryName) ?></td>
                </tr>
                <tr>
                    <th>Subcategory:</th>
                    <td><?= esc($subcategoryName) ?></td>
                </tr>
                <tr>
                    <th>Model:</th>
                    <td><?= esc($modelName) ?></td>
                </tr>
                <tr>
                    <th>Date Bought:</th>
                    <td><?= esc($component['date_bought']) ?></td>
                </tr>
                <tr>
                    <th>Value:</th>
                    <td><?= esc($component['value']) ?></td>
                </tr>
                <tr>
                    <th>Funds From:</th>
                    <td><?= esc($component['funds_from']) ?></td>
                </tr>
                <tr>
                    <th>Warranty:</th>
                    <td><?= esc($warrantyLeft ?? 'N/A') ?></td>
                </tr>
                <tr>
                    <th>Current State:</th>
                    <td><?= esc($component['state']) ?></td>
                </tr>
                <tr>
                    <th>Belongs to Lab:</th>
                    <td><?= esc($lab['name'] ?? 'Unknown Lab') ?></td>
                </tr>
                <tr>
                    <th>Borrow Status:</th>
                    <td>
                        <?= esc($component['borrow_status']) ?>
                        <?php if ($component['borrow_status'] === 'borrowed' && $borrowedBy): ?>
                            <br>
                            <small>
                                <em>
                                    Borrowed by: <a href="/users/profile/<?= esc($borrowedBy['id']) ?>"><?= esc($borrowedBy['username']) ?></a>
                                    <?php if (!empty($borrowedBy['mobile_number'])): ?>
                                        (<?= esc($borrowedBy['mobile_number']) ?>)
                                    <?php endif; ?>
                                </em>
                            </small>
                        <?php elseif ($component['borrow_status'] === 'requested' && $requestedBy): ?>
                            <br>
                            <small>
                                <em>Requested by: <a href="/users/profile/<?= esc($requestedBy['id']) ?>"><?= esc($requestedBy['username']) ?></a></em>
                            </small>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>

            <!-- Action Buttons -->
            <?php if ($isLabManager): ?>
                <!-- Lab Manager Actions -->
                <a href="/components/edit/<?= esc($component['id']) ?>" class="btn btn-warning">Edit</a>
                <a href="/components/delete/<?= esc($component['id']) ?>" class="btn btn-danger">Delete</a>
                <?php if ($component['borrow_status'] === 'requested'): ?>
                    <button class="btn btn-success approve-request-btn" data-component-id="<?= esc($component['id']) ?>">Approve Request</button>
                    <button class="btn btn-danger decline-request-btn" data-component-id="<?= esc($component['id']) ?>">Decline Request</button>
                <?php elseif ($component['borrow_status'] === 'borrowed'): ?>
                    <?php if ($hasPendingReturn): ?>
                        <button class="btn btn-success accept-return-btn" data-component-id="<?= esc($component['id']) ?>">Accept Return</button>
                        <button class="btn btn-danger decline-return-btn" data-component-id="<?= esc($component['id']) ?>">Decline Return</button>
                    <?php else: ?>
                        <span class="badge bg-warning">Borrowed</span>
                    <?php endif; ?>
                <?php endif; ?>
            <?php else: ?>
                <!-- Normal User Actions -->
                <?php if ($component['borrow_status'] === 'available'): ?>
                    <button class="btn btn-primary borrow-btn" data-component-id="<?= esc($component['id']) ?>">Borrow</button>
                <?php elseif ($component['borrow_status'] === 'requested'): ?>
                    <?php if ($hasPendingRequest): ?>
                        <button class="btn btn-secondary cancel-request-btn" data-component-id="<?= esc($component['id']) ?>">Cancel Request</button>
                    <?php else: ?>
                        <span class="badge bg-secondary">Requested by someone else</span>
                    <?php endif; ?>
                <?php elseif ($component['borrow_status'] === 'borrowed'): ?>
                    <?php if ($isBorrower): ?>
                        <button class="btn btn-warning return-btn" data-component-id="<?= esc($component['id']) ?>">Return Item</button>
                    <?php else: ?>
                        <span class="badge bg-warning">Borrowed by someone else</span>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- JavaScript for handling button clicks -->
<script>
    document.addEventListener('click', function(e) {
        const endpoints = {
            'borrow-btn': '/labs/borrow/',
            'cancel-request-btn': '/labs/cancelRequest/',
            'return-btn': '/labs/requestReturn/',
            'approve-request-btn': '/labs/approveRequest/',
            'decline-request-btn': '/labs/declineRequest/',
            'accept-return-btn': '/labs/acceptReturn/',
            'decline-return-btn': '/labs/declineReturn/'
        };

        // Loop through each endpoint key to attach event listeners
        Object.keys(endpoints).forEach(className => {
            if (e.target.classList.contains(className)) {
                const componentId = e.target.dataset.componentId;
                fetch(endpoints[className] + componentId, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            alert(result.success);
                            location.reload();
                        } else {
                            alert(result.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
            }
        });
    });
</script>

<?= $this->endSection() ?>