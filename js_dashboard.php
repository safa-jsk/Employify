<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once 'DBconnect.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'job_seeker') {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

//Applied Jobs Count
$query_applied = "SELECT COUNT(*) AS applied_jobs_count FROM seeker_seeks WHERE S_id = ?";
$stmt_applied = $con->prepare($query_applied);
if (!$stmt_applied) {
    die("Error in query preparation: " . $con->error);
}

$stmt_applied->bind_param("s", $username);
$stmt_applied->execute();
$result_applied = $stmt_applied->get_result();
$applied_jobs_count = $result_applied->fetch_assoc()['applied_jobs_count'] ?? 0;
$stmt_applied->close();

//Bookmarked Jobs Count
$query_bookmarked = "SELECT COUNT(*) AS bookmarked_jobs_count 
                     FROM seeker_bookmarks 
                     WHERE S_id = ?";

$stmt_bookmarked = $con->prepare($query_bookmarked);
if (!$stmt_bookmarked) {
    die("Error in query preparation: " . $con->error);
}

$stmt_bookmarked->bind_param("s", $username);
$stmt_bookmarked->execute();
$result_bookmarked = $stmt_bookmarked->get_result();
$bookmarked_jobs_count = $result_bookmarked->fetch_assoc()['bookmarked_jobs_count'] ?? 0;
$stmt_bookmarked->close();

// Total accepted count
$query_accepted = "SELECT COUNT(*) AS total_accepted
FROM seeker_seeks s
INNER JOIN applications a 
ON s.A_id = a.A_id
WHERE s.S_id = ? AND s.Status = 1";

$stmt_accepted = $con->prepare($query_accepted);
if (!$stmt_accepted) {
    die("Error in query preparation: " . $con->error);
}

$stmt_accepted->bind_param("s", $username);
$stmt_accepted->execute();

$result_accepted = $stmt_accepted->get_result();
$total_accepted = $result_accepted->fetch_assoc()["total_accepted"] ?? 0;

$stmt_accepted->close();


//Applied Jobs List
$query_applied_list = "SELECT a.Name, r.CName,a.Deadline, ss.Applied_Date, ss.Status
                       FROM seeker_seeks ss
                       INNER JOIN applications a ON ss.A_id = a.A_id
                       INNER JOIN recruiter r ON a.R_id = r.R_id
                       WHERE ss.S_id = ?
                       ORDER BY a.Deadline
                       LIMIT 5";
$stmt_applied_list = $con->prepare($query_applied_list);
if (!$stmt_applied_list) {
    die("Error in query preparation: " . $con->error);
}
$stmt_applied_list->bind_param("s", $username);
$stmt_applied_list->execute();
$result_applied_list = $stmt_applied_list->get_result();


// Bookmarked Jobs List
$query_bookmarked_list = "SELECT a.Name, a.Deadline
                          FROM seeker_bookmarks sb
                          INNER JOIN applications a ON sb.A_id = a.A_id
                          WHERE sb.S_id = ?";
$stmt_bookmarked_list = $con->prepare($query_bookmarked_list);
if (!$stmt_bookmarked_list) {
    die("Error in query preparation: " . $con->error);
}
$stmt_bookmarked_list->bind_param("s", $username);
$stmt_bookmarked_list->execute();
$result_bookmarked_list = $stmt_bookmarked_list->get_result();

$con->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="style.css" />
    <title>Dashboard</title>

<body>
    <?php include 'includes/js_navbar.php'; ?>
    <?php include 'includes/js_sidebar.php'; ?>

    <!-- main contents -->
    <div class="dashboard_content">
        <!-- Section 1: Summary Cards -->
        <div class="dashboard_section" id="summary-section">
            <div class="job_card">
                <h3>Number of Applied Jobs</h3>
                <p id="applied-jobs-count">
                    <?php echo htmlspecialchars($applied_jobs_count); ?>
                </p>
            </div>
            <div class="job_card">
                <h3>Total Bookmarks</h3>
                <p id="bookmarked-jobs-count">
                    <?php echo htmlspecialchars($bookmarked_jobs_count); ?>
                </p>
            </div>
            <div class="job_card">
                <h3>Accepted applications</h3>
                <p id="accepted-applications-count">
                    <?php
                    echo htmlspecialchars($total_accepted);
                    ?>
                </p>
            </div>
        </div>

        <!-- Section 2: Applied Jobs List -->
        <div class="dashboard_section" id="applied-jobs-section">
            <h2>Upcoming Applied Jobs</h2>
            <ul id="applied-jobs-list">

                <?php if ($result_applied_list->num_rows > 0) {
                    echo '<table class="applied-jobs-list">';
                    echo '<tr>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Deadline</th>
                    <th>Applied Date</th>
                    <th>Status</th>
                </tr>';

                    while ($row = $result_applied_list->fetch_assoc()) {

                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['Name']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['CName']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['Deadline']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['Applied_Date']) . '</td>';

                        // Status logic
                        if (is_null($row['Status'])) {
                            echo '<td><span class="status on-hold">On Hold</span></td>';
                        } elseif ($row['Status'] == 0) {
                            echo '<td><span class="status rejected">Rejected</span></td>';
                        } elseif ($row['Status'] == 1) {
                            echo '<td><span class="status accepted">Accepted</span></td>';
                        }
                        echo '</tr>';
                    }

                    echo '</table>';
                } else {
                    echo "<p>You have not applied to any jobs yet.</p>";
                } ?>
            </ul>
        </div>

        <!-- Section 3: Bookmarks List -->
        <div class="dashboard_section" id="bookmarks-section">
            <h2>Upcoming Bookmarked Jobs</h2>
            <ul id="bookmarked-jobs-list">
                <?php if ($result_bookmarked_list->num_rows > 0) {
                    echo '<table class="bookmarked-jobs-list">';
                    echo '<tr>
                    <th>Name</th>
                    <th>Deadline</th>
                </tr>';

                    while ($row = $result_bookmarked_list->fetch_assoc()) {

                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['Name']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['Deadline']) . '</td>';
                        echo '</tr>';
                    }

                    echo '</table>';
                } else {
                    echo "<p>You have no bookmarked jobs yet.</p>";
                } ?>
            </ul>
        </div>
    </div>
</body>

</html>