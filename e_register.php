<?php
session_start();

require_once 'DBconnect.php'; // use $con

if (isset($_POST['e_register'])) {

    $username = trim($_POST['r_id']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $gender = isset($_POST['gender']) ? intval($_POST['gender']) : null;
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $company_name = trim($_POST['company_name']);
    $company_description = trim($_POST['company_description']);
    $contact_number = trim($_POST['contact_number']);

    // Checking if email already exists
    $stmt = $con->prepare("SELECT s.email FROM seeker s 
                            LEFT JOIN recruiter r ON s.email = r.email 
                            WHERE s.Email = ? 
                            UNION 
                            SELECT r.email FROM seeker s 
                            RIGHT JOIN recruiter r ON s.email = r.email 
                            WHERE r.Email = ?");
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('Email already exists'); window.location.href='index.php';</script>";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert data
        $stmt = $conn->prepare("INSERT INTO `recruiter` (`R_id`, `FName`, `LName`, `Gender`, `Email`, `Password`, `CName`, `CDescription`, `Contact`) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("sssisssss", $username, $first_name, $last_name, $gender, $email, $hashed_password, $company_name, $company_description, $contact_number);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! Please login.'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Error during registration: " . $stmt->error . "'); window.location.href='index.php';</script>";
        }
    }

    $stmt->close();
    $conn->close();
}
