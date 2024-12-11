<?php
require '../db.php';
require '../session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token");
    }

    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    if (empty($password)) {
        die("Password is required.");
    }

    $stmt = $conn->prepare("SELECT id, password, name, is_admin FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $hashed_password, $name, $is_admin);
        $stmt->fetch();

        if ($is_admin !== 1) {
            die("Access denied. You are not an admin.");
        }

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name'] = $name;
            $_SESSION['is_admin'] = true;

            header("Location: listing.php");
            exit();
        } else {
            die("Incorrect email or password.");
        }
    } else {
        die("Incorrect email or password.");
    }

    $stmt->close();
    $conn->close();
} else {
    die("Invalid request.");
}
?>
