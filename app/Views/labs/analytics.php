<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container mt-3">
    <h2>Analytics for Lab: <?= esc($lab['name']) ?></h2>

    <!-- Lab Manager Info -->
    <p>
        <strong>Lab Manager:</strong>
        <?php if (isset($manager) && !empty($manager)): ?>
            <a href="/users/profile/<?= esc($manager['id']) ?>">
                <?= esc($manager['username']) ?>
            </a>
            <?php if (!empty($manager['phone'])): ?>
                - <?= esc($manager['phone']) ?>
            <?php endif; ?>
        <?php else: ?>
            Not Assigned.
        <?php endif; ?>
    </p>

    <!-- Back to Inventory Button -->
    <a href="/labs/view/<?= esc($lab['id']) ?>/inventory" class="btn btn-secondary mb-3">
        &laquo; Back to Inventory
    </a>

    <div class="row">
        <!-- Pie Chart: Component State Distribution -->
        <div class="col-md-6">
            <canvas id="stateChart"></canvas>
        </div>
        <!-- Bar Chart: Borrow Status Distribution -->
        <div class="col-md-6">
            <canvas id="borrowStatusChart"></canvas>
        </div>
    </div>
    <div class="row mt-3">
        <!-- Expired Warranty Items -->
        <div class="col">
            <div class="alert alert-warning">
                <strong>Expired Warranty Items:</strong> <?= $expiredWarranty ?>
            </div>
        </div>
    </div>
</div>

<!-- Load Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Pie Chart for Component State Distribution
    var ctxState = document.getElementById('stateChart').getContext('2d');
    var stateChart = new Chart(ctxState, {
        type: 'pie',
        data: {
            labels: ['New', 'Used', 'Damaged'],
            datasets: [{
                data: [
                    <?= $stateCounts['new'] ?>,
                    <?= $stateCounts['used'] ?>,
                    <?= $stateCounts['damaged'] ?>
                ],
                backgroundColor: ['#28a745', '#ffc107', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Component State Distribution'
                },
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Bar Chart for Borrow Status Distribution
    var ctxBorrow = document.getElementById('borrowStatusChart').getContext('2d');
    var borrowStatusChart = new Chart(ctxBorrow, {
        type: 'bar',
        data: {
            labels: ['Available', 'Requested', 'Borrowed'],
            datasets: [{
                label: 'Count',
                data: [
                    <?= $borrowStatusCounts['available'] ?>,
                    <?= $borrowStatusCounts['requested'] ?>,
                    <?= $borrowStatusCounts['borrowed'] ?>
                ],
                backgroundColor: ['#28a745', '#17a2b8', '#ffc107']
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Borrow Status Distribution'
                },
                legend: {
                    display: false
                }
            }
        }
    });
</script>
<?= $this->endSection() ?>