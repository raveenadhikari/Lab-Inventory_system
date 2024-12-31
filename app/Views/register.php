<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-md-10 col-lg-8">

            <!-- Register Card -->
            <div class="card shadow rounded">
                <div class="row g-0">
                    <!-- Image Section -->
                    <div class="col-md-5 d-none d-md-block">
                        <img src="/images/uni.jpg" alt="University Photo" class="img-fluid rounded-start h-100" style="object-fit: cover;">
                    </div>

                    <!-- Form Section -->
                    <div class="col-md-7 p-4">
                        <!-- Subtle Text Above the Heading -->
                        <h6 class="text-center text-muted mb-1">Lab Inventory Management System</h6>
                        <h3 class="text-center mb-4">Register</h3>

                        <!-- Flash Messages -->
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success">
                                <?= session()->getFlashdata('success') ?>
                            </div>
                        <?php endif; ?>

                        <!-- Registration Form -->
                        <form method="post" action="/register">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
                            </div>

                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                                <button type="button" class="btn btn-light btn-sm position-absolute end-0 top-0 mt-1 me-1" id="togglePassword" data-target="password" style="border: none;">
                                    👁️
                                </button>
                            </div>

                            <div class="mb-3">
                                <label for="faculty" class="form-label">Faculty</label>
                                <select name="faculty" id="faculty" class="form-select" required>
                                    <option value="" disabled selected>Select Faculty</option>
                                    <option value="Faculty of Science">Faculty of Science</option>
                                    <option value="Faculty of Medicine">Faculty of Medicine</option>
                                    <option value="Faculty of Finance and Management">Faculty of Finance and Management</option>
                                    <option value="Faculty of Technology">Faculty of Technology</option>
                                    <option value="Faculty of Arts">Faculty of Arts</option>
                                    <option value="Faculty of Nursing">Faculty of Nursing</option>
                                    <option value="Faculty of Education">Faculty of Education</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="department" class="form-label">Department</label>
                                <select name="department" id="department" class="form-select">
                                    <option value="" disabled selected>Select Department</option>
                                </select>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary w-100">Register</button>
                                <a href="/login" class="btn btn-secondary w-100 mt-2">Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End of Register Card -->
        </div>
    </div>
</div>

<!-- Script to handle Faculty-Department Dependency -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const facultyDropdown = document.getElementById('faculty');
        const departmentDropdown = document.getElementById('department');

        // Predefined departments for each faculty
        const departments = {
            "Faculty of Science": [
                "Department of Physics",
                "Department of Chemistry",
                "Department of Zoology",
                "Department of Mathematics",
                "Department of Nuclear Science",
                "Department of Statistics",
                "Department of Plant Science"
            ],
            "Faculty of Medicine": [
                "Department of Anatomy",
                "Department of Surgery",
                "Department of Pediatrics",
                "Department of Pharmacology"
            ],
            "Faculty of Finance and Management": [
                "Department of Accounting",
                "Department of Finance",
                "Department of Business Management",
                "Department of Human Resource Management"
            ],
            "Faculty of Technology": [
                "Department of Information Technology",
                "Department of Civil Technology",
                "Department of Electrical Technology",
                "Department of Mechanical Technology"
            ],
            "Faculty of Arts": [
                "Department of English",
                "Department of History",
                "Department of Sociology",
                "Department of Political Science"
            ],
            "Faculty of Nursing": [
                "Department of Clinical Nursing",
                "Department of Public Health Nursing"
            ],
            "Faculty of Education": [
                "Department of Curriculum Studies",
                "Department of Educational Psychology",
                "Department of Teacher Education"
            ]
        };

        // Update departments based on selected faculty
        facultyDropdown.addEventListener('change', function() {
            const selectedFaculty = this.value;
            const departmentOptions = departments[selectedFaculty] || [];

            // Clear existing options in the department dropdown
            departmentDropdown.innerHTML = '<option value="" disabled selected>Select Department</option>';

            // Add new options
            departmentOptions.forEach(department => {
                const option = document.createElement('option');
                option.value = department;
                option.textContent = department;
                departmentDropdown.appendChild(option);
            });
        });
    });
</script>
<?= $this->endSection() ?>