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
                                <button type="button" class="btn btn-light btn-sm position-absolute end-0 top-0 mt-1 me-1" id="togglePassword" data-target="password" style="border: none;">👁️</button>
                            </div>

                            <!-- NEW: Mobile Number Field -->
                            <div class="mb-3">
                                <label for="mobile_number" class="form-label">Mobile Number</label>
                                <input type="text" name="mobile_number" id="mobile_number" class="form-control" placeholder="Enter your mobile number" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="faculty">Faculty</label>
                                <select class="form-control" id="faculty" name="faculty_id">
                                    <option value="">Select Faculty (optional)</option>
                                    <?php foreach ($faculties as $faculty): ?>
                                        <option value="<?= $faculty['id'] ?>"><?= $faculty['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="department">Department</label>
                                <select class="form-control" id="department" name="department_id">
                                    <option value="">Select Department (optional)</option>
                                    <?php foreach ($departments as $department): ?>
                                        <option value="<?= $department['id'] ?>"><?= $department['name'] ?></option>
                                    <?php endforeach; ?>
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

<?= $this->endSection() ?>