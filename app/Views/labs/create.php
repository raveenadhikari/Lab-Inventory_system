<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h3>Create New Lab</h3>
            <form method="post" action="/labs/store">
                <div class="mb-3">
                    <label for="name" class="form-label">Lab Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="faculty_id" class="form-label">Faculty</label>
                    <select id="faculty_id" name="faculty_id" class="form-select" required>
                        <?php foreach ($faculties as $faculty): ?>
                            <option value="<?= esc($faculty['id']) ?>"><?= esc($faculty['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="department_id" class="form-label">Department</label>
                    <select id="department_id" name="department_id" class="form-select" required>
                        <?php foreach ($departments as $department): ?>
                            <option value="<?= esc($department['id']) ?>"><?= esc($department['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="manager_id" class="form-label">Manager</label>
                    <select id="manager_id" name="manager_id" class="form-select" required>
                        <?php foreach ($managers as $manager): ?>
                            <option value="<?= esc($manager['id']) ?>"><?= esc($manager['username']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Create Lab</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>