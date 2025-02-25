<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container mt-3">
    <h3>Borrowing Log</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Component</th>
                <th>User</th>
                <th>Status</th>
                <th>Borrow Date</th>
                <th>Return Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($borrowings as $borrow): ?>
                <tr>
                    <td><?= esc($borrow['component_name']) ?></td>
                    <td><?= esc($borrow['user_name']) ?></td>
                    <td><?= esc($borrow['status']) ?></td>
                    <td><?= esc($borrow['borrow_date']) ?></td>
                    <td><?= esc($borrow['return_date'] ?? 'Not Returned') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>