<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once 'DBconnect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Fetch all posted jobs by the recruiter
$query_posted_jobs = "SELECT A_id, Name, Field, Posted_Date, Deadline, Status, Salary, Description 
                      FROM applications 
                      WHERE R_id = ? 
                      ORDER BY Posted_Date DESC";

$stmt_posted_jobs = $con->prepare($query_posted_jobs);
if (!$stmt_posted_jobs) {
    die("Error in query preparation: " . $con->error);
}

$stmt_posted_jobs->bind_param("s", $username);
$stmt_posted_jobs->execute();
$result_posted_jobs = $stmt_posted_jobs->get_result();
$stmt_posted_jobs->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css" />
    <title>Posted Jobs</title>
</head>

<body>
    <?php include 'includes/e_navbar.php'; ?>
    <?php include 'includes/e_sidebar.php'; ?>

    <div class="dashboard_content">
        <h2>Posted Jobs</h2>
        <?php if ($result_posted_jobs->num_rows > 0): ?>
            <table class="posted-jobs-list">
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
                        $query_total_applicants = "SELECT COUNT(*) AS total_applicants 
                                                  FROM seeker_seeks 
                                                  WHERE A_id = ?";
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
                                // Display job status
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
                            <td><a href="e_edit_job.php?A_id=<?= $row['A_id'] ?>" class="btn btn-primary">Edit</a></td>
                            <td><a href="e_remove_job.php?A_id=<?= $row['A_id'] ?>" class="btn btn-danger">Delete</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No jobs have been posted yet.</p>
        <?php endif; ?>
    </div>
</body>

</html>