<?php
require 'db.php';
require 'session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token");
    }

    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    $name = htmlspecialchars(trim($_POST['name']));
    $telephone = preg_replace('/[^0-9]/', '', trim($_POST['telephone']));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    if (strlen($password) < 8) {
        die("Password must be at least 8 characters long.");
    }

    if (empty($name)) {
        die("Name is required.");
    }

    if (strlen($telephone) !== 10) {
        die("Telephone number must be exactly 10 digits.");
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        die("Email is already registered.");
    }
    $stmt->close();

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (email, password, name, telephone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $email, $hashed_password, $name, $telephone);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $name;
        $_SESSION['is_admin'] = false;

        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    die("Invalid request.");
}
?>
