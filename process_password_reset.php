<?php
require 'db.php';
require 'session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token");
    }

    $token = $_POST['token'];
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (strlen($password) < 8) {
        die("Password must be at least 8 characters long.");
    }

    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }

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

        $stmt->close();

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt_update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt_update->bind_param("si", $hashed_password, $user_id);

        if ($stmt_update->execute()) {
            $stmt_delete = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
            $stmt_delete->bind_param("s", $token);
            $stmt_delete->execute();
            $stmt_delete->close();

            echo "Your password has been successfully reset. You can now <a href='login.html'>login</a>.";
        } else {
            echo "Error updating password: " . $stmt_update->error;
        }

        $stmt_update->close();
    } else {
        echo "Invalid password reset token.";
    }

    $conn->close();
} else {
    die("Invalid request.");
}
?>
