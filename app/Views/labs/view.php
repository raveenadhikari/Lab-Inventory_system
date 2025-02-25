<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container mt-3">

    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link <?= $activeTab == 'inventory' ? 'active' : '' ?>"
                            href="/labs/view/<?= esc($lab['id']) ?>/inventory">Inventory</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $activeTab == 'borrowing-log' ? 'active' : '' ?>"
                            href="/labs/view/<?= esc($lab['id']) ?>/borrowing-log">Borrowing Log</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $activeTab == 'analytics' ? 'active' : '' ?>"
                            href="/labs/view/<?= esc($lab['id']) ?>/analytics">Analytics</a>
                    </li>
                </ul>
        </div>

    </div>

    <div class="row mt-3">
        <div class="col-12">
            <?php if ($activeTab == 'inventory'): ?>
                <h3>Inventory</h3>
                <?php if ($isLabManager): ?>
                    <button class="btn btn-primary mb-3" id="addComponentBtn">+ Add Component</button>
                <?php endif; ?>
                <?php if ($isLabManager): ?>
                    <a href="/labs/bulkUpload/<?= esc($lab['id']) ?>" class="btn btn-secondary mb-3">Bulk Upload Components</a>
                <?php endif; ?>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Left: Filter Controls -->
            <div class="d-flex align-items-center gap-2">
                <select class="form-select" id="categoryFilter" style="width: 200px;">
                    <option value="all">All Categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>"><?= esc($category['name']) ?></option>
                    <?php endforeach; ?>
                </select>

                <select class="form-select" id="subcategoryFilter" style="width: 200px;">
                    <option value="all">All Subcategories</option>
                </select>

                <button class="btn btn-primary" id="applyFilter">Filter</button>
            </div>

            <!-- Right: View Cart Button -->
            <div>
                <a href="/labs/viewCart" id="viewCartBtn" class="btn btn-info">View Cart</a>
            </div>
        </div>

    </div>

    <!-- Components Table -->

    <table class="table">
        <thead>
            <tr>
                <th>Component Code</th>
                <th>Name</th>
                <th>Subcategory</th>
                <th>Model</th>
                <th>Photo</th>
                <th>QR Code</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($components as $component): ?>
                <tr>
                    <td>
                        <a href="/components/<?= esc($component['id']) ?>">
                            <?= esc($component['component_code']) ?>
                        </a>
                    </td>
                    <td>
                        <a href="/components/<?= esc($component['id']) ?>">
                            <?= esc($component['name']) ?>
                        </a>
                    </td>

                    <!-- 3) Subcategory -->
                    <td>
                        <?php if (! empty($component['subcategory_name'])): ?>
                            <?= esc($component['subcategory_name']) ?>
                        <?php else: ?>
                            No Subcategory
                        <?php endif; ?>
                    </td>

                    <!-- 4) Model -->
                    <td>
                        <?php if (! empty($component['model_name'])): ?>
                            <?= esc($component['model_name']) ?>
                        <?php else: ?>
                            No Model
                        <?php endif; ?>
                    </td>
                    <td><img src="/<?= esc($component['photo']) ?>" alt="Photo" width="50"></td>
                    <td><img src="/<?= esc($component['qr_code']) ?>" alt="QR Code" width="50"></td>
                    <td>
                        <?php if ($isLabManager): ?>
                            <?php if ($component['borrow_status'] === 'requested'): ?>
                                <div>
                                    <span class="badge bg-info">
                                        Requested by: <?= esc($component['requested_by'] ?? 'Unknown') ?>
                                    </span>
                                </div>
                                <div class="btn-group mt-1">
                                    <button class="btn btn-success btn-sm approve-request-btn" data-component-id="<?= $component['id'] ?>">
                                        Approve
                                    </button>
                                    <button class="btn btn-danger btn-sm decline-request-btn" data-component-id="<?= $component['id'] ?>">
                                        Decline
                                    </button>
                                </div>
                            <?php elseif ($component['borrow_status'] === 'available'): ?>
                                <!-- For available items, you might show a "Borrow" or "Add to Cart" button for non-manager users -->
                                <?php if (!$isLabManager): ?>
                                    <button class="btn btn-primary btn-sm add-to-cart-btn" data-component-id="<?= $component['id'] ?>">
                                        Add to Cart
                                    </button>
                                <?php else: ?>
                                    <span class="badge bg-success">Available</span>
                                <?php endif; ?>
                            <?php elseif ($component['borrow_status'] === 'borrowed'): ?>
                                <span class="badge bg-warning">Borrowed</span>
                            <?php endif; ?>

                            <!-- Lab Manager's Actions -->
                            <a href="/components/edit/<?= $component['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="/components/delete/<?= $component['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                        <?php else: ?>
                            <?php if ($component['borrow_status'] === 'available'): ?>
                                <button class="btn btn-primary btn-sm borrow-btn" data-component-id="<?= $component['id'] ?>">Borrow</button>
                            <?php elseif ($component['borrow_status'] === 'requested'): ?>
                                <?php if (isset($userRequests[$component['id']]) && $userRequests[$component['id']]): ?>
                                    <button class="btn btn-secondary btn-sm cancel-request-btn" data-component-id="<?= $component['id'] ?>">Cancel Request</button>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Requested</span>
                                <?php endif; ?>
                            <?php elseif ($component['borrow_status'] === 'borrowed'): ?>
                                <span class="badge bg-secondary">Borrowed</span>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if (!$isLabManager && $component['borrow_status'] === 'borrowed'): ?>
                            <?php
                            // Check if the current user is the borrower
                            $userId = session()->get('id');
                            $isBorrower = $borrowingModel->where('component_id', $component['id'])
                                ->where('user_id', $userId)
                                ->where('status', 'approved')
                                ->countAllResults() > 0;
                            ?>
                            <?php if ($isBorrower): ?>
                                <button class="btn btn-warning btn-sm return-btn"
                                    data-component-id="<?= $component['id'] ?>">
                                    Return Item
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($isLabManager && $component['borrow_status'] === 'borrowed'): ?>
                            <?php
                            // Check if there's a pending return request
                            $hasPendingReturn = $borrowingModel->where('component_id', $component['id'])
                                ->where('return_status', 'pending')
                                ->countAllResults() > 0;
                            ?>
                            <?php if ($hasPendingReturn): ?>
                                <div class="btn-group">
                                    <button class="btn btn-success btn-sm accept-return-btn"
                                        data-component-id="<?= $component['id'] ?>">
                                        Accept Return
                                    </button>
                                    <button class="btn btn-danger btn-sm decline-return-btn"
                                        data-component-id="<?= $component['id'] ?>">
                                        Decline Return
                                    </button>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php elseif ($activeTab == 'borrowing-log'): ?>
    <!-- Display Borrowing Log Code -->
    <h3>Borrowing Log</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Component</th>
                <th>User</th>
                <th>Phone</th> <!-- New column for phone number -->
                <th>Status</th>
                <th>Borrow Date</th>
                <th>Return Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($borrowings as $borrow): ?>
                <tr>
                    <td><?= esc($borrow['component_name']) ?></td>
                    <td><?= esc($borrow['user_name']) ?></td>
                    <td>
                        <?= !empty($borrow['user_mobile']) ? esc($borrow['user_mobile']) : 'No Phone Number' ?>
                    </td>
                    <td><?= esc($borrow['status']) ?></td>
                    <td><?= esc($borrow['borrow_date']) ?></td>
                    <td><?= esc($borrow['return_date'] ?? 'Not Returned') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php elseif ($activeTab == 'analytics'): ?>
    <h3>Analytics</h3>
    <!-- Analytics content -->
<?php endif; ?>
</div>
</div>
</div>

<!-- Add Component Modal -->
<div class="modal fade" id="addComponentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="/labs/<?= esc($lab['id']) ?>/components/add" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Add Component</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Component Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="" disabled selected>Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= esc($category['id']) ?>"><?= esc($category['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="subcategory_id" class="form-label">Subcategory</label>
                        <select name="subcategory_id" id="subcategory_id" class="form-select" required>
                            <option value="" disabled selected>Select Subcategory</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="model_id" class="form-label">Model</label>
                        <select name="model_id" id="model_id" class="form-select" required>
                            <option value="" disabled selected>Select Model</option>
                            <!-- Dynamically populated models -->
                            <option value="add_new">+ Add New Model</option>
                        </select>
                    </div>

                    <!-- Inline Form for Adding a New Model -->
                    <div id="addModelForm" style="display: none;">
                        <label for="new_model_name" class="form-label">New Model Name</label>
                        <input type="text" id="new_model_name" class="form-control" placeholder="Enter new model name">
                        <button id="saveModel" type="button" class="btn btn-primary mt-2">Save Model</button>
                    </div>

                    <div class="mb-3">
                        <label for="component_code" class="form-label">Component Code</label>
                        <input type="text" name="component_code" id="component_code" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="warranty_end_date" class="form-label">Warranty End Date</label>
                        <input type="date" name="warranty_end_date" id="warranty_end_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="funds_from" class="form-label">Funds From</label>
                        <input type="text" name="funds_from" id="funds_from" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="date_bought" class="form-label">Date Bought</label>
                        <input type="date" name="date_bought" id="date_bought" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="value" class="form-label">Value</label>
                        <input type="number" name="value" id="value" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="state" class="form-label">State</label>
                        <select name="state" id="state" class="form-select">
                            <option value="new">New</option>
                            <option value="used">Used</option>
                            <option value="damaged">Damaged</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Photo</label>
                        <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Show Add Component Modal (Only for Lab Managers)
    const addComponentBtn = document.getElementById('addComponentBtn');
    if (addComponentBtn) {
        addComponentBtn.addEventListener('click', () => {
            const addComponentModal = document.getElementById('addComponentModal');
            if (addComponentModal) {
                new bootstrap.Modal(addComponentModal).show();
            }
        });
    }




    document.getElementById('category_id').addEventListener('change', function() {
        const categoryId = this.value;
        fetch(`/subcategories/category/${categoryId}`)
            .then(response => response.json())
            .then(subcategories => {
                const subcategoryDropdown = document.getElementById('subcategory_id');
                subcategoryDropdown.innerHTML = '<option value="" disabled selected>Select Subcategory</option>';
                subcategories.forEach(subcategory => {
                    const option = document.createElement('option');
                    option.value = subcategory.id;
                    option.textContent = subcategory.name;
                    subcategoryDropdown.appendChild(option);
                });
            });
    });

    document.getElementById('subcategory_id').addEventListener('change', function() {
        const subcategoryId = this.value;
        fetch(`/models/subcategory/${subcategoryId}`)
            .then(response => response.json())
            .then(models => {
                const modelDropdown = document.getElementById('model_id');
                modelDropdown.innerHTML = '<option value="" disabled selected>Select Model</option>';
                models.forEach(model => {
                    const option = document.createElement('option');
                    option.value = model.id;
                    option.textContent = model.name;
                    modelDropdown.appendChild(option);
                });

                // Always include the "Add New Model" option
                const addNewOption = document.createElement('option');
                addNewOption.value = 'add_new';
                addNewOption.textContent = '+ Add New Model';
                modelDropdown.appendChild(addNewOption);


            });
    });

    document.getElementById('viewCartBtn').addEventListener('click', function() {
        window.preventCartClear = true;
    });

    window.addEventListener('unload', function() {
        // Only clear if the user did not click "View Cart"
        if (!window.preventCartClear) {
            navigator.sendBeacon('/labs/clearCart');
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const modelSelect = document.getElementById('model_id');
        const addModelForm = document.getElementById('addModelForm');

        modelSelect.addEventListener('change', function() {
            if (this.value === 'add_new') {
                addModelForm.style.display = 'block';
            } else {
                addModelForm.style.display = 'none';
            }
        });

        document.getElementById('saveModel').addEventListener('click', function() {
            const newModelName = document.getElementById('new_model_name').value;
            const subcategoryId = document.getElementById('subcategory_id').value; // Ensure a subcategory is selected

            if (newModelName && subcategoryId) {
                // Make an API call to save the model
                fetch('/labs/addModel', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            name: newModelName,
                            subcategory_id: subcategoryId,
                        }),
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            const modelSelect = document.getElementById('model_id');
                            const newOption = document.createElement('option');
                            newOption.value = result.model_id; // Use the ID returned from the server
                            newOption.textContent = newModelName;

                            // Add the new option to the dropdown
                            modelSelect.appendChild(newOption);
                            modelSelect.value = result.model_id; // Select the newly added model

                            // Hide the add model form
                            document.getElementById('addModelForm').style.display = 'none';
                        } else if (result.error) {
                            alert(`Error: ${result.error}`);
                        }
                    })
                    .catch(error => {
                        console.error('Error saving model:', error);
                        alert('An error occurred while saving the model.');
                    });
            } else {
                alert('Please enter a model name and select a subcategory.');
            }
        });

    });
    document.addEventListener('click', function(e) {
        // Borrow Button
        if (e.target.classList.contains('borrow-btn')) {
            const componentId = e.target.dataset.componentId;
            fetch(`/labs/borrow/${componentId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert(result.success);
                        e.target.textContent = 'Requested';
                        e.target.disabled = true;
                    } else {
                        alert(result.error);
                    }
                });
        }

        // Approve Request Button (Lab Manager)
        if (e.target.classList.contains('approve-request-btn')) {
            const componentId = e.target.dataset.componentId;

            fetch(`/labs/approveRequest/${componentId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        // Update the entire action cell
                        const td = e.target.closest('td');
                        td.innerHTML = `
                <span class="badge bg-warning">Borrowed</span>
                <a href="/components/edit/${componentId}" 
                   class="btn btn-warning btn-sm">Edit</a>
                <a href="/components/delete/${componentId}" 
                   class="btn btn-danger btn-sm">Delete</a>
            `;
                    } else {
                        alert(`Error: ${result.error}`);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
        }
        // Cancel Request Button (User)
        if (e.target.classList.contains('cancel-request-btn')) {
            const componentId = e.target.dataset.componentId;

            fetch(`/labs/cancelRequest/${componentId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                })
                .then((response) => response.json())
                .then((result) => {
                    if (result.success) {
                        alert(result.success);
                        e.target.closest('tr').querySelector('.badge').textContent = 'Available';
                        e.target.closest('td').innerHTML = `
                        <button class="btn btn-primary btn-sm borrow-btn" data-component-id="${componentId}">Borrow</button>
                    `;
                    } else {
                        alert(`Error: ${result.error}`);
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
        }

        // Decline Request Button (Lab Manager)
        if (e.target.classList.contains('decline-request-btn')) {
            const componentId = e.target.dataset.componentId;

            fetch(`/labs/declineRequest/${componentId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                })
                .then((response) => response.json())
                .then((result) => {
                    if (result.success) {
                        alert(result.success);
                        e.target.closest('tr').querySelector('.badge').textContent = 'Available';
                        e.target.closest('td').innerHTML = `
                        <span class="badge bg-success">Available</span>
                    `;
                    } else {
                        alert(`Error: ${result.error}`);
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
        }

        if (e.target.classList.contains('return-btn')) {
            const componentId = e.target.dataset.componentId;

            fetch(`/labs/requestReturn/${componentId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert(result.success);
                        e.target.disabled = true; // Disable the return button
                    } else {
                        alert(result.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
        }

        if (e.target.classList.contains('accept-return-btn')) {
            const componentId = e.target.dataset.componentId;

            fetch(`/labs/acceptReturn/${componentId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert(result.success);
                        // Update the UI to show the component as available
                        const td = e.target.closest('td');
                        td.innerHTML = `
                <span class="badge bg-success">Available</span>
                <a href="/components/edit/${componentId}" 
                   class="btn btn-warning btn-sm">Edit</a>
                <a href="/components/delete/${componentId}" 
                   class="btn btn-danger btn-sm">Delete</a>
            `;
                    } else {
                        alert(result.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
        }

        if (e.target.classList.contains('decline-return-btn')) {
            const componentId = e.target.dataset.componentId;

            fetch(`/labs/declineReturn/${componentId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert(result.success);
                        // Hide the Accept/Decline buttons
                        const td = e.target.closest('td');
                        td.innerHTML = `
                <span class="badge bg-warning">Borrowed</span>
                <a href="/components/edit/${componentId}" 
                   class="btn btn-warning btn-sm">Edit</a>
                <a href="/components/delete/${componentId}" 
                   class="btn btn-danger btn-sm">Delete</a>
            `;
                    } else {
                        alert(result.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
        }
    });
    // Filtering Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const categoryFilter = document.getElementById('categoryFilter');
        const subcategoryFilter = document.getElementById('subcategoryFilter');
        const applyFilter = document.getElementById('applyFilter');

        // Initialize subcategories using groupedSubcategories from PHP (if applicable)
        const groupedSubcategories = <?= json_encode($groupedSubcategories) ?>;
        categoryFilter.addEventListener('change', function() {
            const categoryId = this.value;
            subcategoryFilter.innerHTML = '<option value="all">All Subcategories</option>';
            if (categoryId !== 'all' && groupedSubcategories[categoryId]) {
                groupedSubcategories[categoryId].forEach(sub => {
                    const option = document.createElement('option');
                    option.value = sub.id;
                    option.textContent = sub.name;
                    subcategoryFilter.appendChild(option);
                });
            }
        });

        applyFilter.addEventListener('click', function() {
            const categoryId = categoryFilter.value;
            const subcategoryId = subcategoryFilter.value;

            fetch(`/labs/filterComponents/<?= $lab['id'] ?>`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        category_id: categoryId,
                        subcategory_id: subcategoryId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Replace the table's tbody with the rendered HTML from the partial view
                    const tbody = document.querySelector('table tbody');
                    tbody.innerHTML = data.html;
                })
                .catch(error => console.error('Error:', error));
        });
    });
</script>
<?= $this->endSection() ?>