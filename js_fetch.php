<?php
session_start(); // Start the session
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require 'DBconnect.php'; // Ensure the connection is available

// Ensure correct user is logged in
$pageRole = 'job_seeker';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== $pageRole) {
    echo "<script>alert('You must log in first!'); window.location.href = 'index.php';</script>";
    exit;
}

// Fetch job seeker data
$username = $_SESSION['username'];
$stmt = $con->prepare("SELECT FName, LName, Email, Skills, Experience, Education, DoB, Contact 
                        FROM seeker WHERE S_id = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$job_seeker = $result->fetch_assoc();



// Close the statement and connection
$stmt->close();
$con->close();
