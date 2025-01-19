<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Lab Inventory Management System' ?></title>

    <!-- Add CSS links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        body {
            padding-top: 60px;
            background-color: #FBEAEB;
            font-family: 'Roboto', Arial, sans-serif;
            color: #2F3C7E;
        }

        h1 {
            font-weight: bold;
            color: #2F3C7E;
        }

        .navbar {
            background-color: #2F3C7E;
            padding: 1.2rem 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .navbar:hover {
            transform: scale(1.01);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #FBEAEB;
        }

        .navbar-nav .nav-link {
            color: #FBEAEB;
            font-size: 1.1rem;
            margin-right: 15px;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: #FFDADA;
            transform: scale(1.1);
        }

        .navbar-toggler {
            border-color: #FBEAEB;
        }

        .navbar-toggler-icon {
            filter: invert(100%);
        }

        footer {
            background-color: #2F3C7E;
            color: #FBEAEB;
            padding: 15px 0;
            font-size: 0.9rem;
            margin-top: auto;
        }

        footer p {
            margin: 0;
        }

        .btn-primary {
            background-color: #2F3C7E;
            border-color: #2F3C7E;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #1E2759;
            box-shadow: 0 4px 8px rgba(47, 60, 126, 0.6);
        }

        .form-control {
            border: 1px solid #2F3C7E;
            padding: 10px;
            border-radius: 8px;
            transition: box-shadow 0.3s ease;
        }

        .form-control:focus {
            box-shadow: 0 0 10px rgba(47, 60, 126, 0.5);
        }

        .container {
            margin-top: 20px;
            background-color: #FFFFFF;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            flex: 1;
        }

        .card {
            max-width: 700px;
            margin: auto;
            border: none;
            background-color: #FBEAEB;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .card img {
            border-radius: 10px 10px 0 0;
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <?php if (!isset($showNavbar) || $showNavbar): ?>
        <nav class="navbar navbar-expand-lg fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">Lab Inventory</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <!-- Conditionally Show Dashboard -->
                        <?php if (session()->get('role_id') === '1' || session()->get('role_id') === '2'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/dashboard">Dashboard</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link" href="/homepage">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="/profile">Profile</a></li>
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
    <footer class="text-center">
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