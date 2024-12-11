<?php
require 'session.php'; 

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lovejoy's Antiques - Home</title>
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
            scroll-behavior: smooth;
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.75rem;
            letter-spacing: 1px;
        }

        .hero {
            background: url('images/IMG-20220118-WA0020.jpg') no-repeat center center/cover;
            height: 100vh;
            position: relative;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            width: 50px;
            height: 3px;
            background-color: #ff6f61;
            position: absolute;
            left: 50%;
            bottom: -10px;
            transform: translateX(-50%);
        }

        .card {
            border-color: #ff6f61;
        }

        .services .card:hover {
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        .testimonials .testimonial {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .cta {
            background-color: #ff6f61;
            color: white;
            padding: 3rem 1rem;
            text-align: center;
        }

        .cta a {
            color: white;
            text-decoration: underline;
        }

        footer {
            background-color: #343a40;
            color: white;
            padding: 1.5rem 0;
        }

        @media (max-width: 768px) {
            .hero {
                height: 60vh;
            }
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
                        <a class="nav-link active" aria-current="page" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials">Testimonials</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="request_evaluation.php">Request Evaluation</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">Dashboard</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <section id="home" class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content text-center">
            <h1 class="display-3">Discover the Timeless Beauty of Antiques</h1>
            <p class="lead mt-4">Register today to have your cherished items evaluated by our experts.</p>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="register.php" class="btn btn-primary btn-lg mt-3">Get Started</a>
            <?php else: ?>
                <a href="request_evaluation.php" class="btn btn-primary btn-lg mt-3">Request Evaluation</a>
            <?php endif; ?>
        </div>
    </section>

    <section id="about" class="py-5">
        <div class="container">
            <h2 class="section-title text-center">About Us</h2>
            <p class="text-center mx-auto" style="max-width: 700px;">
                At Lovejoy's Antiques, we specialize in authentic antique evaluations, ensuring that your valuable
                pieces are accurately assessed and appreciated. With years of experience and a passion for history, our
                team is dedicated to providing you with reliable and secure services.
            </p>
        </div>
    </section>

    <section id="services" class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-center">Our Services</h2>
            <div class="row mt-4">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-search-dollar fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Comprehensive Evaluations</h5>
                            <p class="card-text">Detailed assessments of your antiques to determine their value and
                                authenticity.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Secure Transactions</h5>
                            <p class="card-text">Ensuring the utmost security and confidentiality in all your
                                interactions with us.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-comments fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Expert Consultations</h5>
                            <p class="card-text">Get personalized advice and insights from our team of antique
                                specialists.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="testimonials" class="py-5">
        <div class="container">
            <h2 class="section-title text-center">What Our Clients Say</h2>
            <div class="row mt-4">
                <div class="col-md-6 mb-4">
                    <div class="testimonial">
                        <p class="mb-3">"Lovejoy's Antiques provided an exceptional evaluation of my 19th-century vase.
                            Their expertise is unparalleled."</p>
                        <h5 class="text-primary">- Emily R.</h5>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="testimonial">
                        <p class="mb-3">"I felt completely secure entrusting my antique furniture to Lovejoy's. Their
                            professionalism is top-notch."</p>
                        <h5 class="text-primary">- Michael S.</h5>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="testimonial">
                        <p class="mb-3">"The evaluation process was seamless and thorough. Highly recommend Lovejoy's
                            Antiques for anyone serious about their collectibles."</p>
                        <h5 class="text-primary">- Sarah L.</h5>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="testimonial">
                        <p class="mb-3">"Their attention to detail and commitment to security gave me peace of mind.
                            Excellent service overall."</p>
                        <h5 class="text-primary">- David M.</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta">
        <div class="container">
            <h2>Ready to Evaluate Your Antiques?</h2>
            <p class="mt-3">Join our community and ensure the safety and authenticity of your valuable items.</p>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="register.php" class="btn btn-light btn-lg mt-3 text-primary">Register Now</a>
            <?php else: ?>
                <a href="request_evaluation.php" class="btn btn-light btn-lg mt-3 text-primary">Request Evaluation</a>
            <?php endif; ?>
        </div>
    </section>

    <footer class="bg-dark text-white text-center py-4">
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
    <script src="js/scripts.js"></script>
</body>

</html>
