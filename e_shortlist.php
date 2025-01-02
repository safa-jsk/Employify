<?php
session_start();
require_once 'DBconnect.php'; // Include the database connection file

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php?message=" . urlencode("You need to log in to perform this action."));
    exit;
}

// Validate and fetch A_id and S_id from GET request
if (isset($_GET['A_id']) && isset($_GET['S_id']) && is_numeric($_GET['A_id'])) {
    $application_id = intval($_GET['A_id']);
    $seeker_id = mysqli_real_escape_string($con, $_GET['S_id']);
    $recruiter_id = $_SESSION['username'];

    // Check if the recruiter owns the job
    $ownership_check = $con->prepare("SELECT 1 FROM applications WHERE A_id = ? AND R_id = ?");
    $ownership_check->bind_param("is", $application_id, $recruiter_id);
    $ownership_check->execute();
    $ownership_result = $ownership_check->get_result();

    // Check if already shortlisted
    $shortlist_check = $con->prepare("SELECT 1 FROM recruiter_shortlist WHERE R_id = ? AND A_id = ? AND S_id = ?");
    $shortlist_check->bind_param("sis", $recruiter_id, $application_id, $seeker_id);
    $shortlist_check->execute();
    $shortlist_result = $shortlist_check->get_result();
    if ($shortlist_result->num_rows > 0) {
        $message = "Candidate already shortlisted.";
        header("Location: e_applied.php?message=" . urlencode($message));
        exit;
    }

    if ($ownership_result->num_rows > 0) {
        // Add to the shortlist table
        $shortlist_stmt = $con->prepare("INSERT INTO recruiter_shortlist (R_id, A_id, S_id) VALUES (?, ?, ?)");
        $shortlist_stmt->bind_param("sis", $recruiter_id, $application_id, $seeker_id);

        if ($shortlist_stmt->execute()) {
            $message = "Candidate successfully shortlisted.";
        } else {
            $message = "Error: Unable to shortlist the candidate.";
        }

        $shortlist_stmt->close();
    } else {
        $message = "You are not authorized to perform this action.";
    }

    $ownership_check->close();
} else {
    $message = "Invalid request.";
}

// Redirect back to the applicants page with a message
header("Location: e_applied.php?message=" . urlencode($message));
exit;

// Close the database connection
mysqli_close($con);