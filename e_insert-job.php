<?php
include 'DBConnect.php'; // Use $con

session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

// Ensure correct user is logged in
$pageRole = 'employer';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== $pageRole) {
    echo "<script>alert('You must log in first!'); window.location.href = 'index.php';</script>";
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $con->prepare("SELECT max(A_id) FROM applications");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $A_id = $row['max(A_id)'] + 1;

    $name = mysqli_real_escape_string($con, $_POST['name']);
    $recruiter_id = $_SESSION['username'];
    $deadline = mysqli_real_escape_string($con, $_POST['deadline']);
    $field = mysqli_real_escape_string($con, $_POST['field']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $salary = mysqli_real_escape_string($con, $_POST['salary']);


    // Validate inputs
    if (empty($name) || empty($deadline) || empty($field) || empty($description) || empty($salary)) {
        header("Location: post_job_form.php?message=" . urlencode("All fields are required."));
        exit;
    }

    // Insert job details into the database
    $stmt = $con->prepare("INSERT INTO applications (A_id, Name, R_id, Deadline, Field, Description, Salary)
         VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssi", $A_id, $name, $recruiter_id, $deadline, $field, $description, $salary);

    if ($stmt->execute()) {
        header("Location: e_posted-jobs.php?message=" . urlencode("Job posted successfully."));
    } else {
        header("Location: e_posted-jobs.php?message=" . urlencode("Error posting the job. Please try again."));
    }

    exit;
}

// Close the database connection
mysqli_close($conn);
