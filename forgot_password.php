<?php
require_once 'DBconnect.php'; // Use $con

if (isset($_POST['reset_password'])) {
    $email = trim($_POST['email']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate fields
    if (empty($email) || empty($new_password) || empty($confirm_password)) {
        echo "<script>alert('All fields are required'); window.location.href='index.php';</script>";
        exit();
    }

    if ($new_password !== $confirm_password) {
        echo "<script>alert('Passwords do not match'); window.location.href='index.php';</script>";
        exit();
    }

    if (strlen($new_password) < 8) {
        echo "<script>alert('Password must be at least 8 characters long'); window.location.href='index.php';</script>";
        exit();
    }

    // Check if email exists
    $stmt = $con->prepare("SELECT S_id FROM seeker WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Update password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_stmt = $con->prepare("UPDATE seeker SET Password = ? WHERE Email = ?");
        $update_stmt->bind_param("ss", $hashed_password, $email);
        $update_stmt->execute();

        echo "<script>alert('Password successfully reset. Please login with your new password.'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Email not found'); window.location.href='index.php';</script>";
    }

    $stmt->close();
    $update_stmt->close();
    $con->close();
} else {
    header("Location: index.php");
    exit();
}
?>
