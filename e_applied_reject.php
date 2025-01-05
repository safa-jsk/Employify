<?php
session_start();
require_once 'DBconnect.php';

// Ensure correct user is logged in
$pageRole = 'employer';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== $pageRole) {
    echo "<script>alert('You must log in first!'); window.location.href = 'index.php';</script>";
    exit;
}

$recruiter_id = $_SESSION['username'];
$A_id = $_GET['A_id'] ?? null; // Application ID
$S_id = $_GET['S_id'] ?? null; // Seeker ID

if (!$A_id || !$S_id) {
    echo "Invalid request.";
    exit;
}

// Remove the candidate from the shortlist table without changing their status
$reject_query = $con->prepare("UPDATE seeker_seeks 
                                SET Status = 0 
                                WHERE A_id = ? AND S_id = ?");
$reject_query->bind_param("is", $A_id, $S_id);

if ($reject_query->execute()) {
    header("Location: e_applied.php?success=applicant_rejected");
    exit;
} else {
    error_log("MySQL Error: " . $con->error); // Log the error for debugging
    header("Location: e_applied.php?error=rejection_failed");
    exit;
}

$reject_query->close();
mysqli_close($con);
