<?php
require 'db.php';
require 'session.php';

if (!isset($_SESSION['failed_login'])) {
    $_SESSION['failed_login'] = 0;
    $_SESSION['last_failed_login'] = time();
}

if ($_SESSION['failed_login'] >= 5 && (time() - $_SESSION['last_failed_login']) < 900) { 
    $_SESSION['login_error'] = "Too many failed login attempts. Please try again later.";
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $_SESSION['login_error'] = "Invalid CSRF token.";
        header("Location: login.php");
        exit();
    }

    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['login_error'] = "Invalid email format.";
        $_SESSION['failed_login'] += 1;
        $_SESSION['last_failed_login'] = time();
        header("Location: login.php");
        exit();
    }

    if (empty($password)) {
        $_SESSION['login_error'] = "Password is required.";
        $_SESSION['failed_login'] += 1;
        $_SESSION['last_failed_login'] = time();
        header("Location: login.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT id, password, name, is_admin FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $hashed_password, $name, $is_admin);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name'] = $name;
            $_SESSION['is_admin'] = $is_admin;

            $_SESSION['failed_login'] = 0;

            header("Location: request_evaluation.php");
            exit();
        } else {
            $_SESSION['login_error'] = "Incorrect email or password.";
            $_SESSION['failed_login'] += 1;
            $_SESSION['last_failed_login'] = time();
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['login_error'] = "Incorrect email or password.";
        $_SESSION['failed_login'] += 1;
        $_SESSION['last_failed_login'] = time();
        header("Location: login.php");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    $_SESSION['login_error'] = "Invalid request.";
    header("Location: login.php");
    exit();
}
?>
