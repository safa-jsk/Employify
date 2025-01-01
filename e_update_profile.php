<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once 'DBconnect.php';

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    echo "<script>alert('You must log in first.'); window.location.href = 'index.php';</script>";
    exit();
}

// Fetch the Job Seeker ID from the session
$user_id = $_SESSION['username'];

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['e_edit'])) {
    // Fetch form data
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $company_name = trim($_POST['cname']);
    $company_description = trim($_POST['company_description']);

    // Validate input (optional, add more validation as needed)
    if (empty($fname) || empty($lname) || empty($email)) {
        echo "<script>alert('First Name, Last Name, and Email are required.'); window.history.back();</script>";
        exit();
    }

    // Prepare the SQL update query
    $stmt = $con->prepare("UPDATE recruiter 
              SET FName = ?, LName = ?, Email = ?, CName = ?, CDescription = ?, Contact = ?
              WHERE R_id = ?");

    $stmt->bind_param("sssssss", $fname, $lname, $email, $company_name, $company_description,$contact, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    // Execute the query
    if ($result -> affected_rows > 0) {
        echo "<script>alert('Profile updated successfully.'); window.location.href = 'e_account.php';</script>";
    } else {
        echo "<script>alert('Failed to update profile.'); window.history.back();</script>";
    }

    $stmt->close();
    $con->close();
} else {
    header("Location: e_account.php");
    exit();
}
