<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container mt-3">
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-md-8 col-lg-6">

            <!-- Login Card -->
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

                        <h3 class="text-center mb-4">Sign In</h3>

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

                        <!-- Login Form -->
                        <form method="post" action="/login">
                            <div class="mb-3">
                                <label for="email" class="form-label">User Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Enter your Email" required>
                            </div>

                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                                <button type="button" class="btn btn-light btn-sm position-absolute end-0 top-0 mt-1 me-1" id="togglePassword" data-target="password" style="border: none;">
                                    👁️
                                </button>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Sign In</button>
                        </form>

                        <div class="mt-3 text-center">
                            <a href="/register" class="btn btn-link">Register</a>
                            <a href="/forgot-password" class="btn btn-link">Forgot your password?</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Login Card -->
        </div>
    </div>
</div>
<?= $this->endSection() ?>