<?php
session_start();
require_once 'DBconnect.php';

// Ensure the admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Seeker COUNT
$seeker_count = "SELECT COUNT(*) FROM seeker";
$seeker_count = $con->query($seeker_count)->fetch_row()[0];

// Recruiter COUNT
$recruiter_count = "SELECT COUNT(*) FROM recruiter";
$recruiter_count = $con->query($recruiter_count)->fetch_row()[0];

// Application COUNT
$application_count = "SELECT COUNT(*) FROM applications";
$application_count = $con->query($application_count)->fetch_row()[0];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="style.css" />
    <title>Admin Panel - Employify</title>
</head>

<body>
    <?php include 'includes/a_navbar.php'; ?>
    <?php include 'includes/a_sidebar.php'; ?>

    <main>
        <div class="dashboard_content">
            <!-- Section 1: Summary Cards -->
            <div class="dashboard_section" id="summary-section">
                <div class="job_card">
                    <h3>Total Job Seekers</h3>
                    <p id="seeker-count">
                        <?php echo htmlspecialchars($seeker_count); ?>
                    </p>
                </div>
                <div class="job_card">
                    <h3>Total Recruiters</h3>
                    <p id="recruiter-count">
                        <?php echo htmlspecialchars($recruiter_count); ?>
                    </p>
                </div>
                <div class="job_card">
                    <h3>Total Job Applications</h3>
                    <p id="application-count">
                        <?php echo htmlspecialchars($application_count); ?>
                    </p>
                </div>
            </div>

            <!-- Section 2: Seekers List -->
            <div class="dashboard_section scrollable" id="seekers-section">
                <h2>Job Seekers</h2>
                <table class="applied-jobs-list">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>DoB</th>
                            <th>Contact</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $seekers = "SELECT S_id, CONCAT(FName, ' ', LName) AS Name, Email, DoB, Contact FROM seeker";
                        $result_seekers = $con->query($seekers);

                        if ($result_seekers->num_rows > 0) {
                            while ($row = $result_seekers->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['S_id']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['Name']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['Email']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['DoB']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['Contact']) . '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5">No recent job seekers</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Section 3: Recruiters List -->
            <div class="dashboard_section scrollable" id="recruiters-section">
                <h2>Recruiters</h2>
                <table class="applied-jobs-list">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Company</th>
                            <th>Contact</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $recruiters = "SELECT R_id, CONCAT(FName, ' ', LName) AS Name, Email, CName, Contact FROM recruiter";
                        $result_recruiters = $con->query($recruiters);

                        if ($result_recruiters->num_rows > 0) {
                            while ($row = $result_recruiters->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['R_id']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['Name']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['Email']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['CName']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['Contact']) . '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5">No recent recruiters</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Section 4: Applications List -->
            <div class="dashboard_section scrollable" id="applications-section">
                <h2>Applications</h2>
                <table class="applied-jobs-list">
                    <thead>
                        <tr>
                            <th>Job ID</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Deadline</th>
                            <th>Field</th>
                            <th>Salary</th>
                            <th>Posted_Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $applications = "SELECT A_id, Name, Status, Deadline, Field, Salary, Posted_Date FROM applications ORDER BY Posted_Date DESC";
                        $result_applications = $con->query($applications);

                        if ($result_applications->num_rows > 0) {
                            while ($row = $result_applications->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['A_id']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['Name']) . '</td>';
                                if (is_null($row['Status'])) {
                                    echo '<td><span class="status on-hold">On Hold</span></td>';
                                } elseif ($row['Status'] == 0) {
                                    echo '<td><span class="status rejected">Inactive</span></td>';
                                } elseif ($row['Status'] == 1) {
                                    echo '<td><span class="status accepted">Active</span></td>';
                                }
                                echo '<td>' . htmlspecialchars($row['Deadline']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['Field']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['Salary']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['Posted_Date']) . '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="7">No recent applications</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
    </main>
</body>

</html>