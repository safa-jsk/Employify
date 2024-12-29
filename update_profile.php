<?php
session_start();
require_once 'DBconnect.php';

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    echo "<script>alert('You must log in first.'); window.location.href = 'index.php';</script>";
    exit();
}

// Fetch the Job Seeker ID from the session
$user_id = $_SESSION['username'];

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    // Fetch form data
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
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
              SET FName = ?, LName = ?, Email = ?, Skills = ?, Experience = ?, Education = ?, DoB = ? 
              WHERE S_id = ?");

    $stmt->bind_param("ssssssss", $fname, $lname, $email, $skills, $experience, $education, $dob, $user_id);

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
?>