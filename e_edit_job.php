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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Edit Job</title>
</head>

<body>
    <?php include 'includes/e_navbar.php'; ?>
    <?php include 'includes/e_sidebar.php'; ?>

    <div class="container mt-4">
        <h2>Edit Job</h2>
        
        <?php if (isset($_SESSION['msg'])): ?>
            <div class="alert alert-info">
                <?= $_SESSION['msg']; ?>
            </div>
            <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Job Name</label>
                <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($job['Name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="field" class="form-label">Field</label>
                <input type="text" id="field" name="field" class="form-control" value="<?= htmlspecialchars($job['Field']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="posted_date" class="form-label">Posted Date</label>
                <input type="date" id="posted_date" name="posted_date" class="form-control" value="<?= htmlspecialchars($job['Posted_Date']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="deadline" class="form-label">Deadline</label>
                <input type="date" id="deadline" name="deadline" class="form-control" value="<?= htmlspecialchars($job['Deadline']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="salary" class="form-label">Salary</label>
                <input type="number" id="salary" name="salary" class="form-control" value="<?= htmlspecialchars($job['Salary']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-control" rows="5"><?= htmlspecialchars($job['Description']); ?></textarea>
            </div>
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="status" name="status" <?= $job['Status'] ? 'checked' : ''; ?>>
                <label class="form-check-label" for="status">Active</label>
            </div>
            <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to update this job?');">Update Job</button>
            <a href="e_posted-jobs.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>
