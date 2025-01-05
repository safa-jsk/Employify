<?php
session_start();

require_once 'DBconnect.php'; // use $con

// Ensure correct user is logged in
$pageRole = 'job_seeker';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== $pageRole) {
    echo "<script>alert('You must log in first!'); window.location.href = 'index.php';</script>";
    exit;
}

// Validate and fetch the job ID
if (isset($_GET['A_id']) && is_numeric($_GET['A_id'])) {
    $application_id = intval($_GET['A_id']);
    $username = $_SESSION['username'];

    // Prepared statement to check if the user has already applied
    $stmt = $con->prepare("DELETE FROM seeker_bookmarks 
                            WHERE S_id = ? 
                            AND A_id = ?");
    $stmt->bind_param("si", $username, $application_id);
    $stmt->execute();
    $check_result = $stmt->get_result();

    // Check if rows are returned
    // Execute the query
    if ($stmt->execute()) {
        // If successful, redirect back to the bookmarks page
        header("Location: js_bookmarks.php");
        exit();
    } else {
        // If there is an error, display an error message
        echo "Error: Could not remove the job from bookmarks.";
    }

    $stmt->close();
} else {
    $message = "Invalid job ID.";
}

mysqli_close($con);
header("Location: js_bookmarks.php?message=" . urlencode($message));
exit;
