<?php
require 'session.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';

$stmt = $conn->prepare("SELECT email, name, telephone FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($email, $name, $telephone);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $_SESSION['profile_error'] = "Invalid CSRF token.";
        header("Location: profile.php");
        exit();
    }

    $new_name = trim($_POST['name']);
    $new_telephone = trim($_POST['telephone']);

    if (empty($new_name) || empty($new_telephone)) {
        $_SESSION['profile_error'] = "All fields are required.";
        header("Location: profile.php");
        exit();
    }

    if (!preg_match("/^[0-9]{10}$/", $new_telephone)) {
        $_SESSION['profile_error'] = "Please enter a valid 10-digit telephone number.";
        header("Location: profile.php");
        exit();
    }

    $update_stmt = $conn->prepare("UPDATE users SET name = ?, telephone = ? WHERE id = ?");
    $update_stmt->bind_param("ssi", $new_name, $new_telephone, $_SESSION['user_id']);
    if ($update_stmt->execute()) {
        $_SESSION['profile_success'] = "Profile updated successfully.";
        $_SESSION['user_name'] = $new_name; 
    } else {
        $_SESSION['profile_error'] = "There was an error updating your profile. Please try again.";
    }
    $update_stmt->close();

    header("Location: profile.php");
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Lovejoy's Antiques</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2DkMw0BGkVj6z+e1RYkBuU5Xy5Gqf1N2W5+Bp38fgGh2gqX9Z2IPhWnYJdC6XQ96A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="css/styles.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            padding-top: 70px; 
            background-color: #f8f9fa;
        }

        .profile-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }

        .profile-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
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
                        <a class="nav-link" href="request_evaluation.php">Request Evaluation</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="profile-container">
            <h2 class="profile-title">Your Profile</h2>
            <?php
            if (isset($_SESSION['profile_error'])) {
                echo '<div class="alert alert-danger" role="alert">' . $_SESSION['profile_error'] . '</div>';
                unset($_SESSION['profile_error']);
            }
            if (isset($_SESSION['profile_success'])) {
                echo '<div class="alert alert-success" role="alert">' . $_SESSION['profile_success'] . '</div>';
                unset($_SESSION['profile_success']);
            }
            ?>
            <form action="profile.php" method="POST" novalidate>
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
                    <div class="form-text">Your email address cannot be changed.</div>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                    <div class="invalid-feedback">
                        Please enter your full name.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="telephone" class="form-label">Contact Telephone Number <span
                            class="text-danger">*</span></label>
                    <input type="tel" class="form-control" id="telephone" name="telephone" pattern="[0-9]{10}" value="<?php echo htmlspecialchars($telephone); ?>" required>
                    <div class="invalid-feedback">
                        Please enter a valid 10-digit telephone number.
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Update Profile</button>
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
