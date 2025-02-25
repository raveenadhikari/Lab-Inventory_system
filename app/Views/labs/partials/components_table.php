<?php foreach ($components as $component): ?>
    <tr>
        <!-- Component Code (clickable link) -->
        <td>
            <a href="/components/<?= esc($component['id']) ?>">
                <?= esc($component['component_code']) ?>
            </a>
        </td>
        <!-- Name (clickable link) -->
        <td>
            <a href="/components/<?= esc($component['id']) ?>">
                <?= esc($component['name']) ?>
            </a>
        </td>
        <!-- Subcategory -->
        <td>
            <?= !empty($component['subcategory_name']) ? esc($component['subcategory_name']) : 'No Subcategory' ?>
        </td>
        <!-- Model -->
        <td>
            <?= !empty($component['model_name']) ? esc($component['model_name']) : 'No Model' ?>
        </td>
        <!-- Photo -->
        <td>
            <img src="/<?= esc($component['photo']) ?>" alt="Photo" width="50">
        </td>
        <!-- QR Code -->
        <td>
            <img src="/<?= esc($component['qr_code']) ?>" alt="QR Code" width="50">
        </td>
        <!-- Actions -->
        <td>
            <?php if ($isLabManager): ?>
                <?php if ($component['borrow_status'] === 'available'): ?>
                    <span class="badge bg-success">Available</span>
                <?php elseif ($component['borrow_status'] === 'requested'): ?>
                    <div>
                        <span class="badge bg-info">
                            Requested by: <?= esc($component['requested_by'] ?? 'Unknown') ?>
                        </span>
                    </div>
                    <div class="btn-group mt-1">
                        <button class="btn btn-success btn-sm approve-request-btn" data-component-id="<?= $component['id'] ?>">
                            Approve
                        </button>
                        <button class="btn btn-danger btn-sm decline-request-btn" data-component-id="<?= $component['id'] ?>">
                            Decline
                        </button>
                    </div>
                <?php elseif ($component['borrow_status'] === 'borrowed'): ?>
                    <?php if (isset($component['hasPendingReturn']) && $component['hasPendingReturn']): ?>
                        <div class="btn-group">
                            <button class="btn btn-success btn-sm accept-return-btn" data-component-id="<?= $component['id'] ?>">
                                Accept Return
                            </button>
                            <button class="btn btn-danger btn-sm decline-return-btn" data-component-id="<?= $component['id'] ?>">
                                Decline Return
                            </button>
                        </div>
                    <?php else: ?>
                        <span class="badge bg-warning">Borrowed</span>
                    <?php endif; ?>
                <?php endif; ?>

                <a href="/components/edit/<?= $component['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="/components/delete/<?= $component['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
            <?php else: ?>
                <?php if ($component['borrow_status'] === 'available'): ?>
                    <div class="btn-group">
                        <!-- Borrow Button (if immediate borrowing is allowed) -->
                        <button class="btn btn-primary btn-sm borrow-btn" data-component-id="<?= $component['id'] ?>">
                            Borrow
                        </button>
                        <!-- Add to Cart Button -->
                        <button class="btn btn-secondary btn-sm add-to-cart-btn" data-component-id="<?= $component['id'] ?>">
                            Add to Cart
                        </button>
                    </div>
                <?php elseif ($component['borrow_status'] === 'requested'): ?>
                    <?php if (isset($userRequests[$component['id']]) && $userRequests[$component['id']]): ?>
                        <button class="btn btn-secondary btn-sm cancel-request-btn" data-component-id="<?= $component['id'] ?>">
                            Cancel Request
                        </button>
                    <?php else: ?>
                        <span class="badge bg-secondary">Requested</span>
                    <?php endif; ?>
                <?php elseif ($component['borrow_status'] === 'borrowed'): ?>
                    <?php if (isset($component['isBorrower']) && $component['isBorrower']): ?>
                        <button class="btn btn-warning btn-sm return-btn" data-component-id="<?= $component['id'] ?>">
                            Return Item
                        </button>
                    <?php else: ?>
                        <span class="badge bg-secondary">Borrowed</span>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
        </td>
    </tr>
<?php endforeach; ?>

<script>
    /*
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-to-cart-btn')) {
            const componentId = e.target.getAttribute('data-component-id');
            console.log("Add to Cart clicked for component:", componentId);

            // Get the CSRF token from the meta tag
            const csrfToken = document.querySelector('meta[name="X-CSRF-TOKEN"]').getAttribute('content');

            fetch(`/labs/addToCart/${componentId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken // include the token here
                    }
                })
                .then(response => response.json())
                .then(result => {
                    console.log("Server response:", result);
                    if (result.success) {
                        alert(result.message);
                        // Optionally, update a cart count indicator here
                    } else {
                        alert('Error adding item to cart.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred.');
                });
        }
    });*/
</script>