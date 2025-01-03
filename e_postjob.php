<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once 'DBconnect.php';

// Ensure correct user is logged in
$pageRole = 'employer';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== $pageRole) {
    echo "<script>alert('You must log in first!'); window.location.href = 'index.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <title>Post a Job - Employify</title>
</head>

<body>
    <?php include 'includes/e_navbar.php'; ?>
    <?php include 'includes/e_sidebar.php'; ?>

    <div class="job-container mt-5">
        <h1>Post a Job</h1>
        <form action="e_insert-job.php" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Job Name</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="field" class="form-label">Field</label>
                <input type="text" id="field" name="field" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="deadline" class="form-label">Deadline</label>
                <input type="date" id="deadline" name="deadline" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="salary" class="form-label">Salary</label>
                <input type="number" id="salary" name="salary" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" rows="4" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Post Job</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFyZ6tOrX2UGjUZJhzoeF1hZeGkEe2kC7HrrxQi1RAd+0cFihkc7x9o2p6" crossorigin="anonymous">
    </script>
</body>

</html>