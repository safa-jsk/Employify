<?php
session_start();
require_once 'DBconnect.php';

// Ensure the recruiter is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

$job_id = intval($_GET['A_id']);
$seeker_id = $_GET['S_id'];

// Prepare the query to reject the candidate
$stmt_reject_candidate = $con->prepare("DELETE FROM recruiter_shortlist WHERE A_id = ? AND S_id = ? AND R_id = ?");

if (!$stmt_reject_candidate) {
    die("Error in query preparation: " . $con->error);
}

$stmt_reject_candidate->bind_param("iss", $job_id, $seeker_id, $username);
$stmt_reject_candidate->execute();

if ($stmt_reject_candidate->affected_rows > 0) {
    // Successfully removed

    $stmt = $con->prepare("UPDATE seeker_seeks SET Status = 0 WHERE A_id = ? AND S_id = ?");
    $stmt->bind_param("is", $job_id, $seeker_id);
    $stmt->execute();

    header("Location: e_shortlisted.php?success=candidate_rejected");
} else {
    // Failed to remove, candidate might not exist or doesn't belong to the recruiter
    header("Location: e_shortlisted.php?error=rejection_failed");
}

$stmt_reject_candidate->close();
$con->close();
