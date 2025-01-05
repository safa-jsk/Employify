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
$A_id = intval($_GET['A_id']);
$S_id = $_GET['S_id'];

// Prepare the query to reject the candidate
$reject_query = $con->prepare("UPDATE seeker_seeks SET Status = 0 
                               WHERE A_id = ? AND S_id = ?");

if (!$reject_query) {
    die("Error in query preparation: " . $con->error);
}

$reject_query->bind_param("is", $A_id, $S_id);

if ($reject_query->execute()) {
    header("Location: e_shortlisted.php?success=candidate_rejected");
} else {
    // Failed to remove, candidate might not exist or doesn't belong to the recruiter
    header("Location: e_shortlisted.php?error=rejection_failed");
}

$reject_query->close();
$con->close();
