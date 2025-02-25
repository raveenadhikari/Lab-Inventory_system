<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="container mt-3">
    <h3>Your Borrow Cart</h3>
    <?php if (empty($components)): ?>
        <p>Your cart is empty.</p>
        <a href="/labs/view/<?= esc($lab['id'] ?? '') ?>/inventory" class="btn btn-primary">Back to Inventory</a>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Component Code</th>
                    <th>Name</th>
                    <th>State</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($components as $component): ?>
                    <tr>
                        <td><?= esc($component['component_code']) ?></td>
                        <td><?= esc($component['name']) ?></td>
                        <td><?= esc($component['state']) ?></td>
                        <td>
                            <a href="/components/<?= esc($component['id']) ?>" class="btn btn-info btn-sm">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <form method="post" action="/labs/requestCart">
            <button type="submit" class="btn btn-primary">Request Borrow for All Items</button>
        </form>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>