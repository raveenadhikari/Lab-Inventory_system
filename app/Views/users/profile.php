<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header text-center">
            <!-- User Icon: a default image (ensure you have this image in /public/images/) -->
            <img src="/images/user.png" alt="User Icon" style="width: 100px; border-radius: 50%;">
            <h2><?= esc($user['username']) ?></h2>
        </div>
        <div class="card-body">
            <p><strong>Email:</strong> <?= !empty($user['email']) ? esc($user['email']) : 'Unavailable' ?></p>
            <p><strong>Faculty:</strong> <?= esc($facultyName) ?></p>
            <p><strong>Department:</strong> <?= esc($departmentName) ?></p>
            <p><strong>Mobile Number:</strong> <?= !empty($user['mobile_number']) ? esc($user['mobile_number']) : 'Unavailable' ?></p>
        </div>
        <div class="card-footer">
            <h4>Borrowed Items</h4>
            <?php if (empty($borrowedItems)): ?>
                <p>No items borrowed.</p>
            <?php else: ?>
                <ul class="list-group">
                    <?php foreach ($borrowedItems as $item): ?>
                        <li class="list-group-item">
                            <strong><?= esc($item['component_code']) ?></strong> - <?= esc($item['component_name']) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>