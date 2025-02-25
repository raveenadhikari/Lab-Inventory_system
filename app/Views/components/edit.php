<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="container mt-3">
    <h3>Edit Component: <?= esc($component['name']) ?></h3>
    <form method="post" action="/components/update/<?= esc($component['id']) ?>" enctype="multipart/form-data">
        <!-- Component Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Component Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= esc($component['name']) ?>" required>
        </div>

        <!-- Date Bought -->
        <div class="mb-3">
            <label for="date_bought" class="form-label">Date Bought</label>
            <input type="date" name="date_bought" id="date_bought" class="form-control" value="<?= esc($component['date_bought']) ?>" required>
        </div>

        <!-- Value -->
        <div class="mb-3">
            <label for="value" class="form-label">Value</label>
            <input type="number" step="0.01" name="value" id="value" class="form-control" value="<?= esc($component['value']) ?>" required>
        </div>

        <!-- State -->
        <div class="mb-3">
            <label for="state" class="form-label">State</label>
            <select name="state" id="state" class="form-select">
                <option value="new" <?= $component['state'] === 'new' ? 'selected' : '' ?>>New</option>
                <option value="used" <?= $component['state'] === 'used' ? 'selected' : '' ?>>Used</option>
                <option value="damaged" <?= $component['state'] === 'damaged' ? 'selected' : '' ?>>Damaged</option>
            </select>
        </div>

        <!-- Component Code -->
        <div class="mb-3">
            <label for="component_code" class="form-label">Component Code</label>
            <input type="text" name="component_code" id="component_code" class="form-control" value="<?= esc($component['component_code']) ?>" required>
        </div>

        <!-- Warranty End Date -->
        <div class="mb-3">
            <label for="warranty_end_date" class="form-label">Warranty End Date</label>
            <input type="date" name="warranty_end_date" id="warranty_end_date" class="form-control" value="<?= esc($component['warranty_end_date']) ?>">
        </div>

        <!-- Funds From -->
        <div class="mb-3">
            <label for="funds_from" class="form-label">Funds From</label>
            <input type="text" name="funds_from" id="funds_from" class="form-control" value="<?= esc($component['funds_from']) ?>" required>
        </div>

        <!-- Category -->
        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select name="category_id" id="category_id" class="form-select" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= esc($category['id']) ?>" <?= $component['category_id'] == $category['id'] ? 'selected' : '' ?>>
                        <?= esc($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Subcategory -->
        <div class="mb-3">
            <label for="subcategory_id" class="form-label">Subcategory</label>
            <select name="subcategory_id" id="subcategory_id" class="form-select" required>
                <?php foreach ($subcategories as $subcategory): ?>
                    <option value="<?= esc($subcategory['id']) ?>" <?= $component['subcategory_id'] == $subcategory['id'] ? 'selected' : '' ?>>
                        <?= esc($subcategory['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Model -->
        <div class="mb-3">
            <label for="model_id" class="form-label">Model</label>
            <select name="model_id" id="model_id" class="form-select" required>
                <?php foreach ($models as $model): ?>
                    <option value="<?= esc($model['id']) ?>" <?= $component['model_id'] == $model['id'] ? 'selected' : '' ?>>
                        <?= esc($model['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Photo Upload -->
        <div class="mb-3">
            <label for="photo" class="form-label">Component Photo</label>
            <?php if (!empty($component['photo'])): ?>
                <div class="mb-2">
                    <img src="/<?= esc($component['photo']) ?>" alt="Current Photo" width="100">
                </div>
            <?php endif; ?>
            <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
            <small class="form-text text-muted">Leave blank if you do not want to change the photo.</small>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Update Component</button>
        <a href="/components/<?= esc($component['id']) ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?= $this->endSection() ?>