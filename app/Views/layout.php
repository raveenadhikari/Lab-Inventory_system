<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Lab Inventory Management System' ?></title>

    <!-- Add CSS links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/styles.css">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        h1 {
            font-weight: bold;
            color: #343a40;
        }

        footer {
            background-color: #f1f1f1;
        }

        .navbar {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            border: 1px solid #ced4da;
            padding: 10px;
        }

        button.btn-light {
            background: none;
            cursor: pointer;
            color: #495057;
        }

        button.btn-light:hover {
            color: #007bff;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .card {
            max-width: 700px;
            margin: auto;
        }

        .card img {
            border-radius: 0.25rem 0 0 0.25rem;
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <?php if (!isset($showNavbar) || $showNavbar): ?>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">Lab Inventory</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="/homepage">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="/logout">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="container mt-4">
        <?= $this->renderSection('content') ?>
    </div>

    <!-- Footer -->
    <footer class="bg-light text-center mt-5 py-3">
        <p>&copy; <?= date('Y') ?> LIMS. All Rights Reserved.</p>
    </footer>

    <!-- Add JS links -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toggle Password Visibility Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const togglePasswordButtons = document.querySelectorAll('[id^="togglePassword"]');

            togglePasswordButtons.forEach((button) => {
                button.addEventListener('click', function() {
                    const passwordField = document.getElementById(this.dataset.target);
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);
                    this.textContent = type === 'password' ? '👁️' : '🙈'; // Change icon/text
                });
            });
        });
    </script>
</body>

</html>