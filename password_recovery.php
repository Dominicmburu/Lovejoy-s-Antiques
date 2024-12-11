<?php
require 'session.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Recovery - Lovejoy's Antiques</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2DkMw0BGkVj6z+e1RYkBuU5Xy5Gqf1N2W5+Bp38fgGh2gqX9Z2IPhWnYJdC6XQ96A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="css/styles.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            padding-top: 70px;
            background-color: #f8f9fa;
        }

        .form-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }

        .form-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.75rem;
            margin-bottom: 1rem;
            text-align: center;
            color: #343a40;
        }

        .btn-primary {
            background-color: #ff6f61;
            border-color: #ff6f61;
        }

        .btn-primary:hover {
            background-color: #e65b50;
            border-color: #e65b50;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Lovejoy's Antiques</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#testimonials">Testimonials</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="form-container">
            <h2 class="form-title">Password Recovery</h2>
            <?php
            if (isset($_SESSION['recovery_error'])) {
                echo '<div class="alert alert-danger" role="alert">' . $_SESSION['recovery_error'] . '</div>';
                unset($_SESSION['recovery_error']);
            }
            if (isset($_SESSION['recovery_success'])) {
                echo '<div class="alert alert-success" role="alert">' . $_SESSION['recovery_success'] . '</div>';
                unset($_SESSION['recovery_success']);
            }
            ?>
            <form action="process_password_recovery.php" method="POST" novalidate>
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div class="mb-3">
                    <label for="email" class="form-label">Registered Email Address <span
                            class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <div class="invalid-feedback">
                        Please enter your registered email address.
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Send Recovery Instructions</button>
            </form>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p>&copy; 2024 Lovejoy's Antiques. All rights reserved.</p>
            <div class="social-icons mt-2">
                <a href="#" class="text-white me-3"><i class="fab fa-facebook-f fa-lg"></i></a>
                <a href="#" class="text-white me-3"><i class="fab fa-twitter fa-lg"></i></a>
                <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-lg"></i></a>
                <a href="#" class="text-white"><i class="fab fa-linkedin-in fa-lg"></i></a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            'use strict'
            var forms = document.querySelectorAll('form')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>

</html>

