<?php
session_start();

require_once 'DBconnect.php'; // use $con

if (isset($_POST['register_js'])) {

    $username = trim($_POST['s_id']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $gender = isset($_POST['gender']) ? intval($_POST['gender']) : null;
    $dob = trim($_POST['dob']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $skills = trim($_POST['skills']);
    $experience = trim($_POST['experience']);
    $education = trim($_POST['education']);
    $skills = trim($_POST['skills']);

    // Check if user_id already exists
    $stmt = $con->prepare("SELECT S_id from seeker WHERE S_id = ?
                            UNION
                            SELECT R_id from recruiter WHERE R_id = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('User ID already exists'); window.location.href='index.php';</script>";
    } else {
        // Check if email already exists
        $stmt = $con->prepare("SELECT Email from seeker WHERE Email = ?
                            UNION
                            SELECT Email from recruiter WHERE Email = ?");
        $stmt->bind_param("ss", $email, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<script>alert('Email already exists'); window.location.href='index.php';</script>";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert data
            $stmt = $con->prepare("INSERT INTO `seeker` (`S_id`, `FName`, `LName`, `Gender`, `Email`, `Password`, `DoB`, `Experience`, `Education`, `Skills`) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param("sssissssss", $username, $first_name, $last_name, $gender, $email, $hashed_password, $dob, $experience, $education, $skills);

            if ($stmt->execute()) {
                echo "<script>alert('Registration successful! Please login.'); window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('Error during registration: " . $stmt->error . "'); window.location.href='index.php';</script>";
            }
        }
    }

    $stmt->close();
    $conn->close();
}
