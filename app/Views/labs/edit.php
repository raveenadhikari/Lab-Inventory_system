<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h3 class="mb-4">Edit Lab</h3>

            <form method="post" action="/labs/update/<?= esc($lab['id']) ?>">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="name" class="form-label">Lab Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= esc($lab['name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="faculty_id" class="form-label">Faculty</label>
                    <select id="faculty_id" name="faculty_id" class="form-select" required>
                        <?php foreach ($faculties as $faculty): ?>
                            <option value="<?= esc($faculty['id']) ?>" <?= $faculty['id'] == $lab['faculty_id'] ? 'selected' : '' ?>>
                                <?= esc($faculty['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="department_id" class="form-label">Department</label>
                    <select id="department_id" name="department_id" class="form-select" required>
                        <?php foreach ($departments as $department): ?>
                            <option value="<?= esc($department['id']) ?>" <?= $department['id'] == $lab['department_id'] ? 'selected' : '' ?>>
                                <?= esc($department['name']) ?>
                            </option>
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

                <button type="submit" class="btn btn-primary">Update Lab</button>
                <a href="/homepage" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>