<?php
session_start();
include 'DBconnect.php';  // Include the database connection
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Assuming user ID is stored in session
    if (!isset($_SESSION['username'])) {
        echo "Session ID not set. Please log in.";
        exit;
    }
    $user_id = $_SESSION['username']; 

    if ($new_password !== $confirm_password) {
        echo "New passwords do not match!";
        exit;
    }

    // Validate current password
    $query = $con->prepare("SELECT password FROM seeker WHERE S_id = ?");
    $query->bind_param("s", $user_id);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_assoc();

    if (empty($current_password)) {
        echo "Current password cannot be empty!";
        exit;
    }
    
    if (!password_verify($current_password, $row['password'])) {
        echo "Incorrect current password!";
        exit;
    }

    // Update password
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
    $update_query = $con->prepare("UPDATE seeker SET password = ? WHERE S_id = ?");
    $update_query->bind_param("ss", $hashed_password, $user_id);

    if ($update_query->execute()) {
        echo "<script>alert('Password changed successfully.'); window.location.href = 'js_account.php';</script>";
    } else {
        echo "<script>alert('Error changing password!'); window.history.back();</script>";
    }
}
?>