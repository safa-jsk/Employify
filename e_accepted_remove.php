<?php
session_start();
require_once 'DBconnect.php';

// Ensure correct user is logged in
$pageRole = 'employer';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== $pageRole) {
    echo "<script>alert('You must log in first!'); window.location.href = 'index.php';</script>";
    exit;
}

$A_id = $_GET['A_id'] ?? null;
$S_id = $_GET['S_id'] ?? null;

if (!$A_id || !$S_id) {
    echo "Invalid request.";
    exit;
}

// Update candidate status to 'Applied' (default state)
$update_status_query = $con->prepare("
    UPDATE seeker_seeks SET Status = NULL WHERE A_id = ? AND S_id = ?
");
$update_status_query->bind_param("is", $A_id, $S_id);

if ($update_status_query->execute()) {
    header("Location: e_accepted.php?success=removed_from_accepted");
    exit;
} else {
    error_log("MySQL Error: " . $con->error);
    header("Location: e_accepted.php?error=remove_failed");
    exit;
}

$update_status_query->close();
$con->close();
?>
