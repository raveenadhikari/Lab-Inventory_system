<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container mt-3">
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link <?= $activeTab == 'inventory' ? 'active' : '' ?>" href="/labs/view/<?= esc($lab['id']) ?>/inventory">Inventory</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $activeTab == 'borrowing-log' ? 'active' : '' ?>" href="/labs/view/<?= esc($lab['id']) ?>/borrowing-log">Borrowing Log</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $activeTab == 'analytics' ? 'active' : '' ?>" href="/labs/view/<?= esc($lab['id']) ?>/analytics">Analytics</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <?php if ($activeTab == 'inventory'): ?>
                <h3>Inventory</h3>
                <button class="btn btn-primary mb-3" id="addComponentBtn">+ Add Component</button>

                <!-- Components Table -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Date Bought</th>
                            <th>Value</th>
                            <th>State</th>
                            <th>Photo</th>
                            <th>QR Code</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($components as $component): ?>
                            <tr>
                                <td><?= esc($component['name']) ?></td>
                                <td><?= esc($component['date_bought']) ?></td>
                                <td><?= esc($component['value']) ?></td>
                                <td><?= esc($component['state']) ?></td>
                                <td><img src="/<?= esc($component['photo']) ?>" alt="Photo" width="50"></td>
                                <td><img src="/<?= esc($component['qr_code']) ?>" alt="QR Code" width="50"></td>
                                <td>
                                    <a href="/components/edit/<?= $component['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="/components/delete/<?= $component['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php elseif ($activeTab == 'borrowing-log'): ?>
                <h3>Borrowing Log</h3>
                <!-- Borrowing log content -->
            <?php elseif ($activeTab == 'analytics'): ?>
                <h3>Analytics</h3>
                <!-- Analytics content -->
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add Component Modal -->
<div class="modal fade" id="addComponentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="/labs/<?= esc($lab['id']) ?>/components/add" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Add Component</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Component Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="date_bought" class="form-label">Date Bought</label>
                        <input type="date" name="date_bought" id="date_bought" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="value" class="form-label">Value</label>
                        <input type="number" name="value" id="value" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="state" class="form-label">State</label>
                        <select name="state" id="state" class="form-select">
                            <option value="new">New</option>
                            <option value="used">Used</option>
                            <option value="damaged">Damaged</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Photo</label>
                        <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('addComponentBtn').addEventListener('click', () => {
        new bootstrap.Modal(document.getElementById('addComponentModal')).show();
    });
</script>
<?= $this->endSection() ?>