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
$remove_shortlist_query = $con->prepare("
    DELETE FROM recruiter_shortlist 
    WHERE A_id = ? AND S_id = ? AND R_id = ?
");
$remove_shortlist_query->bind_param("sis", $A_id, $S_id, $recruiter_id);

if ($remove_shortlist_query->execute()) {
    header("Location: e_applied.php?success=removed_from_shortlist");
    exit;
} else {
    error_log("MySQL Error: " . $con->error); // Log the error for debugging
    header("Location: e_applied.php?error=remove_failed");
    exit;
}

$remove_shortlist_query->close();
mysqli_close($con);
?>
