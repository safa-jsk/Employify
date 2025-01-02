<?php
session_start();
require_once 'DBconnect.php';

// Ensure the recruiter is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}


$username = $_SESSION['username'];
$user_type = $_SESSION['role']; // Fetch the role from the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-container {
            max-width: 400px;
            margin: 50px auto;
            text-align: center;
        }
        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }
        .role-badge {
            font-size: 1.2rem;
            font-weight: bold;
            color: #ffffff;
            background-color: #007bff;
            padding: 5px 10px;
            border-radius: 10px;
            display: inline-block;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <!-- Display user role -->
        <div class="role-badge"><?= ucfirst(htmlspecialchars($user_type)); ?></div>
        
        <!-- Profile Avatar -->
        <img src="path/to/avatar.jpg" alt="Profile Avatar" class="profile-avatar">
        
        <!-- Username -->
        <h2><?= htmlspecialchars($username); ?></h2>
        
        <!-- Other profile details -->
        <p><a href="logout.php" class="btn btn-danger mt-3">Logout</a></p>
    </div>
</body>
</html>
