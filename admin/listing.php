<?php
// admin/listing.php

require '../db.php';
require '../session.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] !== true) {
    // Set an error message and redirect to login
    $_SESSION['login_error'] = "Access denied. Please log in as an admin to view this page.";
    header("Location: ../login.php");
    exit();
}

// Retrieve admin's name from session
$admin_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Admin';

// Fetch all evaluation requests
$stmt = $conn->prepare("
    SELECT er.id, u.email, er.object_details, er.contact_method, er.photo_path, er.request_date, er.status
    FROM evaluation_requests er
    JOIN users u ON er.user_id = u.id
    ORDER BY er.request_date DESC
");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Evaluation Requests - Lovejoy's Antiques</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2DkMw0BGkVj6z+e1RYkBuU5Xy5Gqf1N2W5+Bp38fgGh2gqX9Z2IPhWnYJdC6XQ96A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Custom CSS -->
    <link href="../css/styles.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            padding-top: 70px; /* To prevent navbar overlap */
            background-color: #f8f9fa;
        }

        .table-responsive {
            margin-top: 1rem;
        }

        .btn-success, .btn-danger, .btn-info {
            width: 100%;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">Lovejoy's Antiques</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item me-3">
                        <span class="navbar-text text-white">
                            Welcome, <?php echo htmlspecialchars($admin_name); ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Evaluation Requests Listing -->
    <div class="container mt-5 pt-5">
        <h2>Evaluation Requests</h2>
        <?php
        // Display success or error messages if any
        if (isset($_SESSION['admin_success'])) {
            echo '<div class="alert alert-success" role="alert">' . htmlspecialchars($_SESSION['admin_success']) . '</div>';
            unset($_SESSION['admin_success']);
        }
        if (isset($_SESSION['admin_error'])) {
            echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_SESSION['admin_error']) . '</div>';
            unset($_SESSION['admin_error']);
        }
        ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered mt-4">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>User Email</th>
                        <th>Object Details</th>
                        <th>Preferred Contact</th>
                        <th>Photo</th>
                        <th>Request Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Only allow status updates if the request is pending
                            $can_update = ($row['status'] === 'Pending') ? true : false;

                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['object_details']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['contact_method']) . "</td>";
                            echo "<td><a href='../" . htmlspecialchars($row['photo_path']) . "' target='_blank'>View Photo</a></td>";
                            echo "<td>" . htmlspecialchars($row['request_date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['status']) . "</td>";

                            echo "<td>
                                    <a href='view_request.php?id=" . urlencode($row['id']) . "' class='btn btn-sm btn-info mb-2'><i class='fas fa-eye'></i> View</a>";

                            if ($can_update) {
                                echo "<form action='update_status.php' method='POST' style='display:inline-block;' onsubmit=\"return confirm('Are you sure you want to approve this request?');\">
                                        <input type='hidden' name='csrf_token' value='" . htmlspecialchars($_SESSION['csrf_token']) . "'>
                                        <input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>
                                        <input type='hidden' name='status' value='Approved'>
                                        <button type='submit' class='btn btn-sm btn-success mb-2'><i class='fas fa-check'></i> Approve</button>
                                      </form>";

                                echo "<form action='update_status.php' method='POST' style='display:inline-block;' onsubmit=\"return confirm('Are you sure you want to reject this request?');\">
                                        <input type='hidden' name='csrf_token' value='" . htmlspecialchars($_SESSION['csrf_token']) . "'>
                                        <input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>
                                        <input type='hidden' name='status' value='Rejected'>
                                        <button type='submit' class='btn btn-sm btn-danger'><i class='fas fa-times'></i> Reject</button>
                                      </form>";
                            } else {
                                echo "<span class='badge bg-secondary'>No actions available</span>";
                            }

                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' class='text-center'>No evaluation requests found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
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

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="../js/scripts.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
