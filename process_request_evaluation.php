<?php
require 'db.php';
require 'session.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['evaluation_error'] = "Please log in to submit an evaluation request.";
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $_SESSION['evaluation_error'] = "Invalid CSRF token.";
        header("Location: request_evaluation.php");
        exit();
    }

    $object_details = trim($_POST['object_details']);
    $contact_method = trim($_POST['contact_method']);

    if (empty($object_details)) {
        $_SESSION['evaluation_error'] = "Please provide details about the antique object.";
        header("Location: request_evaluation.php");
        exit();
    }

    if (!in_array($contact_method, ['Email', 'Phone'])) {
        $_SESSION['evaluation_error'] = "Invalid contact method selected.";
        header("Location: request_evaluation.php");
        exit();
    }

    if (isset($_FILES['photo_upload']) && $_FILES['photo_upload']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['photo_upload']['tmp_name'];
        $fileName = $_FILES['photo_upload']['name'];
        $fileSize = $_FILES['photo_upload']['size'];
        $fileType = $_FILES['photo_upload']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

        $allowedfileExtensions = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($fileExtension, $allowedfileExtensions)) {
            $uploadFileDir = './uploads/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $stmt = $conn->prepare("INSERT INTO evaluation_requests (user_id, object_details, contact_method, photo_path, request_date, status) VALUES (?, ?, ?, ?, NOW(), 'Pending')");
                $stmt->bind_param("isss", $_SESSION['user_id'], $object_details, $contact_method, $dest_path);
                if ($stmt->execute()) {
                    $_SESSION['evaluation_success'] = "Your evaluation request has been submitted successfully.";
                } else {
                    $_SESSION['evaluation_error'] = "There was an error submitting your request. Please try again.";
                }
                $stmt->close();
            } else {
                $_SESSION['evaluation_error'] = "There was an error moving the uploaded file.";
            }
        } else {
            $_SESSION['evaluation_error'] = "Upload failed. Allowed file types: " . implode(", ", $allowedfileExtensions);
        }
    } else {
        $_SESSION['evaluation_error'] = "There was an error uploading your file. Please try again.";
    }

    $conn->close();

    header("Location: request_evaluation.php");
    exit();
} else {
    $_SESSION['evaluation_error'] = "Invalid request.";
    header("Location: request_evaluation.php");
    exit();
}
?>
