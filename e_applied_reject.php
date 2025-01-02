<?php
session_start();
require_once 'DBconnect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$recruiter_id = $_SESSION['username'];
$A_id = $_GET['A_id'] ?? null; // Application ID
$S_id = $_GET['S_id'] ?? null; // Seeker ID

if (!$A_id || !$S_id) {
    echo "Invalid request.";
    exit;
}

// Remove the candidate from the shortlist table
$remove_shortlist_query = $con->prepare("
    DELETE FROM recruiter_shortlist 
    WHERE A_id = ? AND S_id = ? AND R_id = ?
");
$remove_shortlist_query->bind_param("sis", $A_id, $S_id, $recruiter_id);

if ($remove_shortlist_query->execute()) {
    echo "Candidate removed from shortlist and rejected successfully.";
    header("Location: e_applied.php");
    exit;
} else {
    error_log("MySQL Error: " . $con->error); // Log the error for debugging
    echo "Failed to reject candidate.";
}

$remove_shortlist_query->close();
mysqli_close($con);
?>
