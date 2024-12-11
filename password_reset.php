<?php
require 'db.php';
require 'session.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT user_id, expires_at FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $expires_at);
        $stmt->fetch();

        if (strtotime($expires_at) < time()) {
            echo "This password reset link has expired.";
            exit();
        }
    } else {
        echo "Invalid password reset token.";
        exit();
    }

    $stmt->close();
} else {
    echo "No password reset token provided.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Lovejoy's Antiques</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            max-width: 500px;
            margin: auto;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.html">Lovejoy's Antiques</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.html">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.html">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="form-container">
            <h2 class="text-center mb-4">Reset Your Password</h2>
            <?php
            if (isset($_SESSION['reset_error'])) {
                echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['reset_error']) . '</div>';
                unset($_SESSION['reset_error']);
            }
            if (isset($_SESSION['reset_success'])) {
                echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['reset_success']) . '</div>';
                unset($_SESSION['reset_success']);
            }
            ?>
            <form action="process_password_reset.php" method="POST" novalidate>
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <div class="mb-3">
                    <label for="password" class="form-label">New Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="password" name="password" required minlength="8" placeholder="Enter new password">
                    <div class="invalid-feedback">
                        Please enter a new password with at least 8 characters.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="8" placeholder="Confirm new password">
                    <div class="invalid-feedback">
                        Please confirm your new password.
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Reset Password</button>
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
                        if (form.password.value !== form.confirm_password.value) {
                            event.preventDefault()
                            event.stopPropagation()
                            alert("Passwords do not match.");
                        }
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
