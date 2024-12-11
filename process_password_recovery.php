<?php
require 'db.php';
require 'session.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_recovery_email($email, $token, $user_name) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = ''; // Your SMTP username
        $mail->Password   = '';    // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port       = 587;

        $mail->setFrom('oliviamarjorie787@gmail.com', 'Lovejoy\'s Antiques');
        $mail->addAddress($email, $user_name);

        $mail->isHTML(true);
        $mail->Subject = 'Password Recovery - Lovejoy\'s Antiques';
        $mail->Body    = "
            <html>
            <head>
                <title>Password Recovery</title>
            </head>
            <body>
                <p>Hi " . htmlspecialchars($user_name) . ",</p>
                <p>You requested a password recovery. Click the link below to reset your password:</p>
                <p><a href='http://localhost/Lovejoys_Antique/password_reset.php?token=" . urlencode($token) . "'>Reset Your Password</a></p>
                <p>If you did not request this, please ignore this email.</p>
                <p>Thank you,<br>Lovejoy's Antiques Team</p>
            </body>
            </html>
        ";
        $mail->AltBody = "Hi $user_name,\n\nYou requested a password recovery. Visit the link below to reset your password:\nhttp://localhost/Lovejoys_Antique/password_reset.php?token=$token\n\nIf you did not request this, please ignore this email.\n\nThank you,\nLovejoy's Antiques Team";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("PHPMailer Error: " . $mail->ErrorInfo);
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $_SESSION['recovery_error'] = "Invalid CSRF token.";
        header("Location: password_recovery.php");
        exit();
    }

    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['recovery_error'] = "Please enter a valid email address.";
        header("Location: password_recovery.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT id, name FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result_user = $stmt->get_result();

    if ($result_user->num_rows === 1) {
        $user = $result_user->fetch_assoc();
        $user_id = $user['id'];
        $user_name = $user['name'];

        $token = bin2hex(random_bytes(32));

        $expires_at = date("Y-m-d H:i:s", strtotime('+1 hour'));

        $stmt_insert = $conn->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
        $stmt_insert->bind_param("iss", $user_id, $token, $expires_at);
        if ($stmt_insert->execute()) {
            if (send_recovery_email($email, $token, $user_name)) {
                $_SESSION['recovery_success'] = "A password recovery link has been sent to your email address.";
            } else {
                $_SESSION['recovery_error'] = "Failed to send password recovery email. Please try again later.";
            }
        } else {
            $_SESSION['recovery_error'] = "Failed to initiate password recovery. Please try again.";
        }

        $stmt_insert->close();
    } else {
        $_SESSION['recovery_error'] = "No account is associated with that email address.";
    }

    $stmt->close();
    $conn->close();

    header("Location: password_recovery.php");
    exit();
} else {
    $_SESSION['recovery_error'] = "Invalid request.";
    header("Location: password_recovery.php");
    exit();
}
?>
