<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow rounded">
                <div class="card-body">
                    <h3 class="text-center mb-4">Profile</h3>

                    <p><strong>Username:</strong> <?= esc($user['username']) ?></p>
                    <p><strong>Email:</strong> <?= esc($user['email']) ?></p>
                    <p><strong>Faculty:</strong> <?= esc($user['faculty_id'] ?? 'Not Assigned') ?></p>
                    <p><strong>Department:</strong> <?= esc($user['department_id'] ?? 'Not Assigned') ?></p>
                    <p><strong>Role:</strong> <?= esc($user['role_id'] ?? 'Not Assigned') ?></p>

                    <div class="text-center mt-4">
                        <a href="/logout" class="btn btn-danger">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>