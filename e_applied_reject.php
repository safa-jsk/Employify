<?php
session_start();
require_once 'DBconnect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$recruiter_id = $_SESSION['username'];
$A_id = $_GET['A_id'] ?? null;
$S_id = $_GET['S_id'] ?? null;

if (!$A_id || !$S_id) {
    echo "Invalid request.";
    exit;
}

// Remove the candidate from the shortlist or rejection process
$reject_query = $con->prepare("
    DELETE FROM recruiter_shortlist 
    WHERE A_id = ? AND S_id = ? AND R_id = ?
");
$reject_query->bind_param("sis", $A_id, $S_id, $recruiter_id);

if ($reject_query->execute()) {
    echo "Candidate rejected successfully.";
    header("Location: e_applied.php");
    exit;
} else {
    echo "Failed to reject candidate.";
}

$reject_query->close();
mysqli_close($con);
?>
