<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once 'DBconnect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Fetch jobs posted by the logged-in user
$query_posted_jobs = "SELECT * FROM applications WHERE R_id = ?";
$stmt_posted_jobs = $con->prepare($query_posted_jobs);
$stmt_posted_jobs->bind_param("s", $username);
$stmt_posted_jobs->execute();
$result_posted_jobs = $stmt_posted_jobs->get_result();
$stmt_posted_jobs->close();

// Handle job editing and saving changes
if (isset($_GET['edit'])) {
    $A_id = $_GET['edit'];  // Get the A_id from the URL

    // Fetch the job data for editing
    $query_job = "SELECT * FROM applications WHERE A_id = ?";
    $stmt_job = $con->prepare($query_job);
    $stmt_job->bind_param("s", $A_id);
    $stmt_job->execute();
    $job = $stmt_job->get_result()->fetch_assoc();
    $stmt_job->close();
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_job'])) {
    // Process the form submission to update the job
    $A_id = $_POST['A_id'];
    $name = $_POST['name'];
    $field = $_POST['field'];
    $posted_date = $_POST['posted_date'];
    $deadline = $_POST['deadline'];
    $salary = $_POST['salary'];
    $description = $_POST['description'];
    $status = isset($_POST['status']) ? 1 : 0;  // Default to inactive if checkbox is not checked

    // Update the job in the database
    $update_query = "UPDATE applications SET Name = ?, Field = ?, Posted_Date = ?, Deadline = ?, Salary = ?, Description = ?, Status = ? WHERE A_id = ?";
    $stmt_update = $con->prepare($update_query);
    $stmt_update->bind_param("ssssssss", $name, $field, $posted_date, $deadline, $salary, $description, $status, $A_id);
    $stmt_update->execute();
    $stmt_update->close();

    $_SESSION['msg'] = "Job updated successfully!";
    header("Location: e_posted-jobs.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <title>Posted Jobs</title>
</head>

<body>
    <?php include 'includes/e_navbar.php'; ?>
    <?php include 'includes/e_sidebar.php'; ?>

    <div class="dashboard_content">
        <h2>Posted Jobs</h2>

        <?php if (isset($_SESSION['msg'])): ?>
        <div class="alert alert-info">
            <?= $_SESSION['msg']; ?>
        </div>
        <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>

        <!-- Display Jobs List -->
        <table class="shortlisted-candidates-list">
            <thead>
                <tr>
                    <th>Job ID</th>
                    <th>Job Name</th>
                    <th>Field</th>
                    <th>Posted Date</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Salary</th>
                    <th>Description</th>
                    <th>Total Applicants</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_posted_jobs->fetch_assoc()): ?>
                <?php
                    // Fetch total applicants for the job
                    $query_total_applicants = "SELECT COUNT(*) AS total_applicants FROM seeker_seeks WHERE A_id = ?";
                    $stmt_total_applicants = $con->prepare($query_total_applicants);
                    $stmt_total_applicants->bind_param("s", $row['A_id']);
                    $stmt_total_applicants->execute();
                    $result_total_applicants = $stmt_total_applicants->get_result();
                    $total_applicants = $result_total_applicants->fetch_assoc()['total_applicants'];
                    $stmt_total_applicants->close();
                    ?>

                <tr>
                    <td><?php echo htmlspecialchars($row['A_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['Name']); ?></td>
                    <td><?php echo htmlspecialchars($row['Field']); ?></td>
                    <td><?php echo htmlspecialchars($row['Posted_Date']); ?></td>
                    <td><?php echo htmlspecialchars($row['Deadline']); ?></td>
                    <td>
                        <?php
                            if (is_null($row['Status'])) {
                                echo '<span class="status on-hold">On Hold</span>';
                            } elseif ($row['Status'] == 0) {
                                echo '<span class="status rejected">Inactive</span>';
                            } elseif ($row['Status'] == 1) {
                                echo '<span class="status accepted">Active</span>';
                            }
                            ?>
                    </td>
                    <td><?php echo htmlspecialchars(number_format($row['Salary'])); ?> USD</td>
                    <td><?php echo htmlspecialchars($row['Description']); ?></td>
                    <td><?php echo htmlspecialchars($total_applicants); ?></td>
                    <td>
                        <a href="e_posted-jobs.php?edit=<?php echo $row['A_id']; ?>" class="status accepted">Edit</a>
                    </td>
                    <td><a href="e_remove_job.php?A_id=<?= $row['A_id'] ?>" class="status rejected">Delete</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <?php if (isset($job)): ?>
        <!-- Display the Edit Form -->
        <h3>Edit Job: <?php echo htmlspecialchars($job['Name']); ?></h3>
        <form method="POST" action="e_posted-jobs.php">
            <input type="hidden" name="A_id" value="<?= $job['A_id'] ?>" />
            <div class="mb-3">
                <label for="name" class="form-label">Job Name</label>
                <input type="text" id="name" name="name" class="form-control"
                    value="<?= htmlspecialchars($job['Name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="field" class="form-label">Field</label>
                <input type="text" id="field" name="field" class="form-control"
                    value="<?= htmlspecialchars($job['Field']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="posted_date" class="form-label">Posted Date</label>
                <input type="date" id="posted_date" name="posted_date" class="form-control"
                    value="<?= htmlspecialchars($job['Posted_Date']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="deadline" class="form-label">Deadline</label>
                <input type="date" id="deadline" name="deadline" class="form-control"
                    value="<?= htmlspecialchars($job['Deadline']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="salary" class="form-label">Salary</label>
                <input type="number" id="salary" name="salary" class="form-control"
                    value="<?= htmlspecialchars($job['Salary']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-control"
                    rows="5"><?= htmlspecialchars($job['Description']); ?></textarea>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="status" name="status"
                    <?= $job['Status'] ? 'checked' : ''; ?>>
                <label for="status" class="form-check-label" id="status-label">
                    <?= $job['Status'] ? 'Active' : 'Inactive'; ?>
                </label>
            </div>
            <button type="submit" name="update_job" class="search-button">Update Job</button>
            <a href="e_posted-jobs.php" class="status rejected">Cancel</a>
        </form>
        <?php endif; ?>
    </div>
</body>

</html>