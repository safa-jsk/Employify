<?php
session_start();
require_once 'DBconnect.php';

// Ensure correct user is logged in
$pageRole = 'employer';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== $pageRole) {
    echo "<script>alert('You must log in first!'); window.location.href = 'index.php';</script>";
    exit;
}

$username = $_SESSION['username'];
$job_id = $_GET['A_id'] ?? null;
$seeker_id = $_GET['S_id'] ?? null;

// Validate parameters
if (!$job_id || !$seeker_id) {
    header("Location: e_shortlisted.php?error=invalid_parameters");
    exit;
}

// Update seeker status
$update_query = $con->prepare("UPDATE seeker_seeks SET Status = 1 WHERE A_id = ? AND S_id = ?");
$update_query->bind_param("is", $job_id, $seeker_id);
$update_query->execute();

if ($update_query->affected_rows > 0) {
    header("Location: e_shortlisted.php?success=candidate_accepted");
} else {
    header("Location: e_shortlisted.php?error=acceptation_failed");
}

$update_query->close();
$con->close();
exit;
