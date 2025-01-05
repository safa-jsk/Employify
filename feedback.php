<?php
session_start();

require_once 'DBconnect.php';

if (isset($_POST['feedback'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    $stmt = $con->prepare("INSERT INTO `feedback` (`Name`, `Email`, `Subject`, `Message`) 
                                VALUES (?, ?, ?, ?)");

    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        echo "<script>alert('Feedback submitted successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error during feedback submission: " . $stmt->error . "'); window.location.href='index.php';</script>";
    }

    $stmt->close();
    $con->close();
}