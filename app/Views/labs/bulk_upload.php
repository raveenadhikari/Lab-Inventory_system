<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="container mt-3">
    <h3>Bulk Upload Components for Lab: <?= esc($lab['name']) ?></h3>
    <form method="post" action="/labs/bulkUpload/<?= esc($lab['id']) ?>" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="csv_file" class="form-label">Upload CSV File</label>
            <input type="file" name="csv_file" id="csv_file" class="form-control" accept=".csv" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
    <hr>
    <p><strong>CSV Format:</strong> The CSV file should have a header row with the following columns:</p>
    <ul>
        <li>name</li>
        <li>date_bought (YYYY-MM-DD)</li>
        <li>value</li>
        <li>state (new, used, damaged)</li>
        <li>component_code</li>
        <li>warranty_end_date (YYYY-MM-DD)</li>
        <li>funds_from</li>
        <li>category_id</li>
        <li>subcategory_id</li>
        <li>model_id</li>
    </ul>
    <p>Note: The photo is not included. You can add a photo later by editing the component. QR codes will be automatically generated.</p>
</div>
<?= $this->endSection() ?>