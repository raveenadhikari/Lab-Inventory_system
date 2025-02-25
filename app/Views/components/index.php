<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>All Components</h2>
        <!-- Search Form -->
        <form class="d-flex" method="get" action="/components">
            <input class="form-control me-2" type="search" name="q" placeholder="Search components" aria-label="Search" value="<?= esc($q) ?>">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
    </div>

    <?php if (empty($components)): ?>
        <p>No components found.</p>
    <?php else: ?>
        <!-- Grid of Cards: Four cards per row -->
        <div class="row">
            <?php foreach ($components as $component): ?>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card h-100">
                        <!-- Component Photo -->
                        <img src="/<?= esc($component['photo']) ?>" class="card-img-top" alt="<?= esc($component['name']) ?>" style="height: 150px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($component['name']) ?></h5>
                            <p class="card-text">
                                <strong>Code:</strong> <?= esc($component['component_code']) ?><br>
                                <strong>Subcat:</strong> <?= esc($component['subcategory_name'] ?? 'N/A') ?><br>
                                <strong>Model:</strong> <?= esc($component['model_name'] ?? 'N/A') ?><br>
                                <strong>Lab:</strong> <?= esc($component['lab_name'] ?? 'N/A') ?>
                            </p>
                        </div>
                        <div class="card-footer text-center">
                            <a href="/components/<?= esc($component['id']) ?>" class="btn btn-primary btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            <?= $pager->links() ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>