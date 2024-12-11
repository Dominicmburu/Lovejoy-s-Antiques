<?php
require '../db.php';
require '../session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] !== true) {
    $_SESSION['login_error'] = "Access denied. Please log in as an admin to view this page.";
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['admin_error'] = "Invalid request ID.";
    header("Location: listing.php");
    exit();
}

$request_id = intval($_GET['id']);

$stmt = $conn->prepare("
    SELECT er.id, u.email, er.object_details, er.contact_method, er.photo_path, er.request_date, er.status
    FROM evaluation_requests er
    JOIN users u ON er.user_id = u.id
    WHERE er.id = ?
");
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $_SESSION['admin_error'] = "Evaluation request not found.";
    header("Location: listing.php");
    exit();
}

$request = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Evaluation Request - Admin - Lovejoy's Antiques</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2DkMw0BGkVj6z+e1RYkBuU5Xy5Gqf1N2W5+Bp38fgGh2gqX9Z2IPhWnYJdC6XQ96A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="../css/styles.css" rel="stylesheet">
    <style>

        body {
            font-family: 'Roboto', sans-serif;
            padding-top: 70px; 
            background-color: #f8f9fa;
        }

        .request-details {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
        }

        .request-title {
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
            <a class="navbar-brand" href="../index.php">Lovejoy's Antiques</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5 pt-5">
        <div class="request-details">
            <h2 class="request-title">Evaluation Request Details</h2>
            <p><strong>Request ID:</strong> <?php echo htmlspecialchars($request['id']); ?></p>
            <p><strong>User Email:</strong> <?php echo htmlspecialchars($request['email']); ?></p>
            <p><strong>Object Details:</strong> <?php echo nl2br(htmlspecialchars($request['object_details'])); ?></p>
            <p><strong>Preferred Contact Method:</strong> <?php echo htmlspecialchars($request['contact_method']); ?></p>
            <p><strong>Request Date:</strong> <?php echo htmlspecialchars($request['request_date']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($request['status']); ?></p>
            <p><strong>Photo:</strong> <a href="../<?php echo htmlspecialchars($request['photo_path']); ?>" target="_blank">View Photo</a></p>

            <div class="mt-4">
                <?php if ($request['status'] === 'Pending'): ?>
                    <a href="update_status.php?id=<?php echo urlencode($request['id']); ?>&status=Approved" class="btn btn-success me-2" onclick="return confirm('Are you sure you want to approve this request?');"><i class="fas fa-check"></i> Approve</a>
                    <a href="update_status.php?id=<?php echo urlencode($request['id']); ?>&status=Rejected" class="btn btn-danger me-2" onclick="return confirm('Are you sure you want to reject this request?');"><i class="fas fa-times"></i> Reject</a>
                <?php else: ?>
                    <span class="badge bg-secondary">No actions available</span>
                <?php endif; ?>
                <a href="listing.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Listings</a>
            </div>
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
    <script src="../js/scripts.js"></script>
</body>
</html>
