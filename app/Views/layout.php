<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Lab Inventory Management System' ?></title>
    <meta name="X-CSRF-TOKEN" content="<?= csrf_hash(); ?>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Base and Layout */
        html,
        body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        body {
            padding-top: 60px;
            background-color: #E8F4FF;
            /* Mobile safeArea background */
            font-family: 'Roboto', Arial, sans-serif;
            color: #2F3C7E;
        }

        h1,
        h2,
        h3,
        .title {
            font-weight: bold;
            color: #0277BD;
            /* Mobile title color */
        }

        /* Navbar */
        .navbar {
            background-color: #0277BD;
            /* Primary mobile color */
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
            color: #FFFFFF;
        }

        .navbar-nav .nav-link {
            color: #FFFFFF;
            font-size: 1.1rem;
            margin-right: 15px;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: #FFDADA;
            transform: scale(1.1);
        }

        .navbar-toggler {
            border-color: #FFFFFF;
        }

        .navbar-toggler-icon {
            filter: invert(100%);
        }

        /* Footer */
        footer {
            background-color: #0277BD;
            color: #FFFFFF;
            padding: 15px 0;
            font-size: 0.9rem;
            margin-top: auto;
        }

        footer p {
            margin: 0;
        }

        /* Buttons and Form Controls */
        .btn-primary {
            background-color: #0277BD;
            border-color: #0277BD;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #01579B;
            box-shadow: 0 4px 8px rgba(2, 87, 189, 0.6);
        }

        .form-control {
            border: 1px solid #0277BD;
            padding: 10px;
            border-radius: 8px;
            transition: box-shadow 0.3s ease;
            background-color: #FFFFFF;
        }

        .form-control:focus {
            box-shadow: 0 0 10px rgba(2, 87, 189, 0.5);
        }

        /* Container */
        .container {
            margin-top: 20px;
            background-color: #E8F4FF;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            flex: 1;
        }

        /* Card */
        .card {
            max-width: 700px;
            margin: auto;
            border: none;
            background-color: #FFFFFF;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .card img {
            border-radius: 10px 10px 0 0;
        }

        /* Category Titles */
        .category-title {
            background: none;
            border: none;
            padding: 0;
            color: #0277BD;
            text-decoration: underline;
            cursor: pointer;
        }

        .category-title:hover {
            color: #01579B;
        }

        /* Filter Controls */
        #categoryFilter,
        #subcategoryFilter {
            width: 200px;
            display: inline-block;
            margin-right: 10px;
        }

        #applyFilter {
            vertical-align: top;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {

            #categoryFilter,
            #subcategoryFilter {
                width: 100% !important;
            }

            .gap-2 {
                gap: 0.5rem !important;
                flex-wrap: wrap;
            }
        }

        /* Toast Notifications */
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 4px;
            color: white;
            z-index: 1000;
            animation: slideIn 0.3s ease-out;
        }

        .toast-success {
            background: #28a745;
        }

        .toast-error {
            background: #dc3545;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
            }

            to {
                transform: translateX(0);
            }
        }

        /* Additional Mobile-inspired Elements */
        .profileTab {
            display: flex;
            align-items: center;
            background-color: #FFFFFF;
            padding: 6px 10px;
            border-radius: 30px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .profileImage {
            width: 36px;
            height: 36px;
            border-radius: 18px;
            margin-right: 8px;
        }

        .profileText {
            font-size: 14px;
            font-weight: 500;
            color: #0277BD;
        }

        .logoutButton {
            background-color: #FF5252;
            padding: 6px 12px;
            border-radius: 20px;
            border: none;
            color: #FFFFFF;
            font-weight: 500;
            font-size: 12px;
        }

        .logoutButton:hover {
            opacity: 0.9;
        }

        /* Other Mobile-inspired Styles */
        .sectionTitle {
            font-size: 16px;
            font-weight: 600;
            color: #424242;
            margin: 8px 0;
        }

        .searchInput {
            height: 40px;
            border-color: #B0BEC5;
            border-width: 1px;
            border-radius: 20px;
            padding: 0 12px;
            font-size: 14px;
            background-color: #FFFFFF;
        }

        .itemContainer {
            padding: 12px;
            margin-bottom: 8px;
            background-color: #FFFFFF;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .itemText {
            font-size: 14px;
            color: #424242;
            margin-bottom: 4px;
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
                        <li class="nav-item">
                            <a class="nav-link" href="/users/profile/<?= session()->get('id') ?>">Profile</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="/components">Components</a></li>
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

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Global event delegation for "Add to Cart" buttons.
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('add-to-cart-btn')) {
                const componentId = e.target.getAttribute('data-component-id');
                console.log("Add to Cart clicked for component:", componentId);

                // Get the CSRF token from the meta tag
                const csrfToken = document.querySelector('meta[name="X-CSRF-TOKEN"]').getAttribute('content');

                fetch(`/labs/addToCart/${componentId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(result => {
                        console.log("Server response:", result);
                        if (result.success) {
                            alert(result.message);
                        } else {
                            alert('Error adding item to cart.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred.');
                    });
            }
        });

        // Toggle Password Visibility Script
        document.addEventListener('DOMContentLoaded', () => {
            const togglePasswordButtons = document.querySelectorAll('[id^="togglePassword"]');
            togglePasswordButtons.forEach((button) => {
                button.addEventListener('click', function() {
                    const passwordField = document.getElementById(this.dataset.target);
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);
                    this.textContent = type === 'password' ? '👁️' : '🙈';
                });
            });
        });
    </script>
</body>

</html>