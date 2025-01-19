<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="text-center">
        <h1 class="mb-4">Welcome to the Lab Inventory Management System</h1>
        <!-- <p class="lead">Hello, <?= esc(session()->get('username')) ?>!</p>
        <p class="text-muted">Your role: <strong><?= esc($role) ?></strong></p>

        <a href="/logout" class="btn btn-danger mt-3">Logout</a> -->
    </div>
</div>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= esc($labsTitle) ?></h3>
        <form class="d-flex" action="/labs/filter" method="get">
            <select class="form-select me-2" id="faculty" name="faculty_id" required>
                <option value="all">All Faculties</option>
                <?php foreach ($faculties as $faculty): ?>
                    <option value="<?= esc($faculty['id']) ?>"><?= esc($faculty['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <select class="form-select me-2" id="department" name="department_id" required>
                <option value="all">All Departments</option>
                <?php foreach ($departments as $department): ?>
                    <option value="<?= esc($department['id']) ?>" data-faculty="<?= esc($department['faculty_id']) ?>"><?= esc($department['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>

    <div class="row">
        <?php if (!empty($labs)): ?>
            <?php foreach ($labs as $lab): ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($lab['name']) ?></h5>
                            <p class="card-text">
                                Faculty: <?= esc($lab['faculty_name']) ?><br>
                                Department: <?= esc($lab['department_name']) ?>
                            </p>
                            <a href="/labs/view/<?= esc($lab['id']) ?>" class="btn btn-primary btn-sm">View</a>
                            <?php if (hasPermission('Manage Labs')): ?>
                                <a href="/labs/edit/<?= esc($lab['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="/labs/delete/<?= esc($lab['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this lab?');">Remove</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="text-muted text-center">No labs available to display.</p>
            </div>
        <?php endif; ?>


        <!-- Add Lab Widget for Authorized Users -->
        <?php if (hasPermission('Manage Labs')): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm text-center">
                    <div class="card-body d-flex justify-content-center align-items-center" style="height: 150px;">
                        <a href="/labs/create" class="btn btn-outline-primary">+ Add Lab</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>