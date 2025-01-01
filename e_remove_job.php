<?php
session_start();
require_once 'DBconnect.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Validate the job ID
if (!isset($_GET['A_id']) || !is_numeric($_GET['A_id'])) {
    header("Location: posted_jobs.php?error=invalid_id");
    exit;
}

$job_id = intval($_GET['A_id']);

// Prepare the SQL statement to delete the job
$stmt_delete_job = $con->prepare("DELETE FROM applications WHERE A_id = ? AND R_id = ?");

if (!$stmt_delete_job) {
    die("Error in query preparation: " . $con->error);
}

$stmt_delete_job->bind_param("is", $job_id, $username);
$stmt_delete_job->execute();

if ($stmt_delete_job->affected_rows > 0) {
    // Successfully deleted
    header("Location: e_posted-jobs.php?success=job_deleted");
} else {
    // Failed to delete, job might not exist or doesn't belong to the user
    header("Location: e_posted-jobs.php?error=delete_failed");
}

$stmt_delete_job->close();