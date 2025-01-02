<?php
session_start();
require_once 'DBconnect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['A_id'])) {
    echo "<script>alert('Job ID not specified.'); window.location.href = 'e_posted-jobs.php';</script>";
    exit;
}

$username = $_SESSION['username'];
$job_id = $_GET['A_id'];

// Fetch the job details for the given job ID
$stmt = $con->prepare("SELECT * FROM applications WHERE A_id = ? AND R_id = ?");
$stmt->bind_param("ss", $job_id, $username);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

if (!$job) {
    echo "<script>alert('Job not found or access denied.'); window.location.href = 'e_posted-jobs.php';</script>";
    exit;
}

$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $field = trim($_POST['field']);
    $posted_date = trim($_POST['posted_date']);
    $deadline = trim($_POST['deadline']);
    $salary = trim($_POST['salary']);
    $description = trim($_POST['description']);
    $status = isset($_POST['status']) ? 1 : 0; // Slider: Checked means 1 (Active)

    // Validate mandatory fields
    if (empty($name) || empty($field) || empty($posted_date) || empty($deadline) || empty($salary)) {
        echo "<script>alert('All fields except description are required.'); window.history.back();</script>";
        exit();
    }

    // Update the job details
    $update_stmt = $con->prepare("UPDATE applications 
                                  SET Name = ?, Field = ?, Posted_Date = ?, Deadline = ?, Salary = ?, Description = ?, Status = ? 
                                  WHERE A_id = ? AND R_id = ?");
    $update_stmt->bind_param("ssssdsiss", $name, $field, $posted_date, $deadline, $salary, $description, $status, $job_id, $username);

    if ($update_stmt->execute()) {
        $_SESSION['msg'] = 'Job updated successfully.';
        echo "<script>window.location.href = 'e_posted-jobs.php';</script>";
    } else {
        $_SESSION['msg'] = 'Failed to update job. Please try again.';
        echo "<script>window.location.href = 'e_posted-jobs.php';</script>";
    }

    $update_stmt->close();
    $con->close();
    exit();
}