<?php
session_start(); // Start the session
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once 'DBconnect.php';

// Ensure correct user is logged in
$pageRole = 'job_seeker';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== $pageRole) {
    echo "<script>alert('You must log in first!'); window.location.href = 'index.php';</script>";
    exit;
}

// Fetch the Job Seeker ID and role from the session
$user_id = $_SESSION['username'];


// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['js_edit'])) {
    // Fetch form data
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $skills = trim($_POST['skills']);
    $experience = trim($_POST['experience']);
    $education = trim($_POST['education']);
    $dob = trim($_POST['dob']);

    // Validate input (optional, add more validation as needed)
    if (empty($fname) || empty($lname) || empty($email)) {
        echo "<script>alert('First Name, Last Name, and Email are required.'); window.history.back();</script>";
        exit();
    }

    // Prepare the SQL update query
    $stmt = $con->prepare("UPDATE seeker 
              SET FName = ?, LName = ?, Email = ?, Skills = ?, Experience = ?, Education = ?, DoB = ? , Contact = ? 
              WHERE S_id = ?");
    $stmt->bind_param("sssssssss", $fname, $lname, $email, $skills, $experience, $education, $dob, $contact, $user_id);

    // Execute the query
    if ($stmt->execute()) {
        echo "<script>alert('Profile updated successfully.'); window.location.href = 'js_account.php';</script>";
    } else {
        echo "<script>alert('Failed to update profile.'); window.history.back();</script>";
    }

    $stmt->close();
    $con->close();
} else {
    header("Location: js_account.php");
    exit();
}
