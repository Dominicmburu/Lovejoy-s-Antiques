<?php
require '../db.php';
require '../session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] !== true) {
    $_SESSION['login_error'] = "Access denied. Please log in as an admin to perform this action.";
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['admin_error'] = "Invalid request method.";
    header("Location: listing.php");
    exit();
}

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $_SESSION['admin_error'] = "Invalid CSRF token.";
    header("Location: listing.php");
    exit();
}

if (!isset($_POST['id']) || !isset($_POST['status'])) {
    $_SESSION['admin_error'] = "Missing required parameters.";
    header("Location: listing.php");
    exit();
}

$request_id = intval($_POST['id']);
$new_status = $_POST['status'];

$allowed_statuses = ['Approved', 'Rejected'];

if (!in_array($new_status, $allowed_statuses)) {
    $_SESSION['admin_error'] = "Invalid status value.";
    header("Location: listing.php");
    exit();
}

$stmt_check = $conn->prepare("SELECT status FROM evaluation_requests WHERE id = ?");
$stmt_check->bind_param("i", $request_id);
$stmt_check->execute();
$stmt_check->bind_result($current_status);
$stmt_check->fetch();
$stmt_check->close();

if ($current_status !== 'Pending') {
    $_SESSION['admin_error'] = "Only pending requests can be updated.";
    header("Location: listing.php");
    exit();
}

$stmt_update = $conn->prepare("UPDATE evaluation_requests SET status = ? WHERE id = ?");
$stmt_update->bind_param("si", $new_status, $request_id);

if ($stmt_update->execute()) {
    $_SESSION['admin_success'] = "Request ID {$request_id} has been marked as '{$new_status}'.";
} else {
    $_SESSION['admin_error'] = "Failed to update the request status. Please try again.";
}

$stmt_update->close();
$conn->close();

header("Location: listing.php");
exit();
?>
