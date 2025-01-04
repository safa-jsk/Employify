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

$username = $_SESSION['username'];

//posted Jobs Count
$query_posted = "SELECT COUNT(*) AS posted_jobs_count 
                 FROM applications 
                 WHERE R_id = ?";

$stmt_posted = $con->prepare($query_posted);
if (!$stmt_posted) {
    die("Error in query preparation: " . $con->error);
}

$stmt_posted->bind_param("s", $username);
$stmt_posted->execute();
$result_posted = $stmt_posted->get_result();
$posted_jobs_count = $result_posted->fetch_assoc()['posted_jobs_count'] ?? 0;
$stmt_posted->close();

// Shortlisted candidates Count
$query_shortlisted = "SELECT A_id, COUNT(*) AS shortlisted_candidates_count 
    FROM recruiter_shortlist 
    WHERE R_id = ? 
    GROUP BY A_id";

$stmt_shortlisted = $con->prepare($query_shortlisted);
if (!$stmt_shortlisted) {
    die("Error in query preparation: " . $con->error);
}

$stmt_shortlisted->bind_param("s", $username);
$stmt_shortlisted->execute();
$result_shortlisted = $stmt_shortlisted->get_result();
$shortlisted_candidates_per_application = [];
$total_shortlisted = 0;  // Initialize the total shortlisted counter

while ($row = $result_shortlisted->fetch_assoc()) {
    $shortlisted_candidates_per_application[] = $row;
    $total_shortlisted += $row['shortlisted_candidates_count'];  // Sum the shortlisted count
}

$stmt_shortlisted->close();

// Accepted candidates count across all applications
$query_accepted = "SELECT COUNT(*) AS accepted_candidates_count
                   FROM seeker_seeks ss
                   JOIN applications a ON ss.A_id = a.A_id
                   WHERE a.R_id = ? AND ss.Status = 1";

$stmt_accepted = $con->prepare($query_accepted);
if (!$stmt_accepted) {
    die("Error in query preparation: " . $con->error);
}

$stmt_accepted->bind_param("s", $username); // Use recruiter ID (username)
$stmt_accepted->execute();
$result_accepted = $stmt_accepted->get_result();

$total_accepted = 0; // Default value
if ($row = $result_accepted->fetch_assoc()) {
    $total_accepted = $row['accepted_candidates_count']; // Get total count
}

$stmt_accepted->close();


//posted Jobs List
$query_posted_jobs = "SELECT Name, Field, Posted_Date, Deadline, Status, Salary, Description
                      FROM applications 
                      WHERE R_id = ? 
                      ORDER BY Deadline ASC";

$stmt_posted_jobs = $con->prepare($query_posted_jobs);
if (!$stmt_posted_jobs) {
    die("Error in query preparation: " . $con->error);
}

$stmt_posted_jobs->bind_param("s", $username);
$stmt_posted_jobs->execute();
$result_posted_jobs = $stmt_posted_jobs->get_result();
$stmt_posted_jobs->close();

$con->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <title>Dashboard</title>

<body>
    <?php include 'includes/e_navbar.php'; ?>
    <?php include 'includes/e_sidebar.php'; ?>

    <!-- main contents -->
    <div class="dashboard_content">
        <!-- Section 1: Summary Cards -->
        <div class="dashboard_section" id="summary-section">
            <div class="job_card">
                <h3>Total Posted Jobs</h3>
                <p id="applied-jobs-count">
                    <?php echo htmlspecialchars($posted_jobs_count); ?>
                </p>
            </div>
            <div class="job_card">
                <h3>Shortlisted Candidates</h3>
                <p id="shortlisted-candidates-count">
                    <?php
                    echo htmlspecialchars($total_shortlisted);
                    ?>
                </p>
            </div>
            <div class="job_card">
                <h3>Accepted Candidates</h3>
                <p id="accepted-candidates-count">
                    <?php
                    echo htmlspecialchars($total_accepted);
                    ?>
                </p>
            </div>
        </div>

        <!-- Section 2: Applied Jobs List -->
        <div class="dashboard_section" id="posted-jobs-section">
            <h2>Posted Jobs</h2>
            <?php if ($result_posted_jobs->num_rows > 0): ?>
                <table class="posted-jobs-list">
                    <thead>
                        <tr>
                            <th>Job Name</th>
                            <th>Field</th>
                            <th>Posted Date</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Salary</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result_posted_jobs->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['Name']); ?></td>
                                <td><?php echo htmlspecialchars($row['Field']); ?></td>
                                <td><?php echo htmlspecialchars($row['Posted_Date']); ?></td>
                                <td><?php echo htmlspecialchars($row['Deadline']); ?></td>
                                <td>
                                    <?php
                                    // Status logic
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