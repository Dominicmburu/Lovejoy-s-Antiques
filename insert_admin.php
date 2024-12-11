<?php
require 'db.php';

$email = 'admin@lovejoysantiques.com';
$password = 'Admin@123';
$name = 'Admin User';
$telephone = '1234567890';

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (email, password, name, telephone, is_admin) VALUES (?, ?, ?, ?, ?)");
$is_admin = 1;

$stmt->bind_param("ssssi", $email, $hashed_password, $name, $telephone, $is_admin);

if ($stmt->execute()) {
    echo "Admin user inserted successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
