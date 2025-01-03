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
$job_id = $_GET['A_id'];
$seeker_id = $_GET['S_id'];

// Update the candidate's status
$stmt = $con->prepare("UPDATE seeker_seeks SET Status = 1 WHERE A_id = ? AND S_id = ?");
$stmt->bind_param("ss", $job_id, $seeker_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    header("Location: e_shortlisted.php?success=candidate_accepted");
} else {
    header("Location: e_shortlisted.php?error=acceptation_failed");
}

$stmt->close();
$con->close();
