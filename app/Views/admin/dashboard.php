<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <h1 class="mb-4">Admin Dashboard</h1>

    <!-- User Table -->
    <h2>Users</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= esc($user['id']) ?></td>
                    <td><?= esc($user['username']) ?></td>
                    <td><?= esc($user['email']) ?></td>
                    <td><?= esc($user['role_name']) ?></td>
                    <td>
                        <form method="post" action="/update-role/<?= esc($user['id']) ?>">
                            <select name="role_id">
                                <?php foreach ($roles as $role): ?>
                                    <?php
                                    // Skip the Super Admin role (assuming its id is 1 or name is "Super Admin")
                                    if ($role['id'] == 1 || strtolower($role['name']) === 'super admin') {
                                        continue;
                                    }
                                    ?>
                                    <option value="<?= esc($role['id']) ?>" <?= $user['role_id'] == $role['id'] ? 'selected' : '' ?>>
                                        <?= esc($role['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Role Permissions Table -->
    <h2 class="mt-5">Role Permissions</h2>
    <form method="post" action="/update-role-permissions">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Role</th>
                    <?php foreach ($permissions as $permission): ?>
                        <th><?= esc($permission['name']) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($roles as $role): ?>
                    <tr>
                        <td><?= esc($role['name']) ?></td>
                        <?php foreach ($permissions as $permission): ?>
                            <td>
                                <input type="checkbox" name="role_permissions[<?= esc($role['id']) ?>][]" value="<?= esc($permission['id']) ?>"
                                    <?= isset($rolePermissionsMap[$role['id']]) && in_array($permission['id'], $rolePermissionsMap[$role['id']]) ? 'checked' : '' ?>>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" class="btn btn-success">Update Permissions</button>
    </form>
</div>
<?= $this->endSection() ?>