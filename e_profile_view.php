<?php
session_start();
require_once 'DBconnect.php';

// Ensure correct user is logged in
$pageRole = 'employer';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== $pageRole) {
    echo "<script>alert('You must log in first!'); window.location.href = 'index.php';</script>";
    exit;
}

if (!isset($_GET['S_id']) || empty($_GET['S_id'])) {
    echo json_encode(['error' => 'Seeker ID not provided']);
    exit;
}

$seeker_id = $_GET['S_id'];
$query = "SELECT FName, LName, Gender, Email, Experience, Education, Skills, Contact 
          FROM seeker 
          WHERE S_id = ?";

$stmt = $con->prepare($query);
$stmt->bind_param("s", $seeker_id);
$stmt->execute();
$result = $stmt->get_result();
echo json_encode($result->fetch_assoc());
$con->close();
