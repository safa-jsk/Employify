<?php
// session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require 'DBconnect.php'; // Ensure the connection is available

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php'); // Redirect to login if not logged in
    exit();
}

// Fetch job seeker data
$username = $_SESSION['username'];
$stmt = $con->prepare("SELECT FName, LName, Email, Skills, Experience, Education, DoB, Contact FROM seeker WHERE S_id = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$job_seeker = $result->fetch_assoc();

// Close the statement and connection
$stmt->close();
$con->close();