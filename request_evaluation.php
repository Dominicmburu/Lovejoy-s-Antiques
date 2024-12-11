<?php
require 'session.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Evaluation - Lovejoy's Antiques</title>
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

        .form-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 700px;
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
                        <a class="nav-link active" href="request_evaluation.php">Request Evaluation</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="form-container">
            <h2 class="form-title">Request Antique Evaluation</h2>
            <?php
            if (isset($_SESSION['evaluation_error'])) {
                echo '<div class="alert alert-danger" role="alert">' . $_SESSION['evaluation_error'] . '</div>';
                unset($_SESSION['evaluation_error']);
            }
            if (isset($_SESSION['evaluation_success'])) {
                echo '<div class="alert alert-success" role="alert">' . $_SESSION['evaluation_success'] . '</div>';
                unset($_SESSION['evaluation_success']);
            }
            ?>
            <form action="process_request_evaluation.php" method="POST" enctype="multipart/form-data" novalidate>
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div class="mb-3">
                    <label for="object_details" class="form-label">Details of the Antique Object <span
                            class="text-danger">*</span></label>
                    <textarea class="form-control" id="object_details" name="object_details" rows="5" required></textarea>
                    <div class="invalid-feedback">
                        Please provide details about the antique object.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="contact_method" class="form-label">Preferred Method of Contact <span
                            class="text-danger">*</span></label>
                    <select class="form-select" id="contact_method" name="contact_method" required>
                        <option value="" selected disabled>Select an option</option>
                        <option value="Email">Email</option>
                        <option value="Phone">Phone</option>
                    </select>
                    <div class="invalid-feedback">
                        Please select a preferred method of contact.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="photo_upload" class="form-label">Upload Photo of the Object <span
                            class="text-danger">*</span></label>
                    <input class="form-control" type="file" id="photo_upload" name="photo_upload" accept="image/*" required>
                    <div class="invalid-feedback">
                        Please upload a photo of the antique object.
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Submit Evaluation Request</button>
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
