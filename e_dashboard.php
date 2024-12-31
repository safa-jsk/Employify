<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once 'DBconnect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// //posted Jobs Count
// $query_posted = "SELECT COUNT(*) AS posted_jobs_count FROM applications WHERE A_id = ?";
// $stmt_posted = $con->prepare($query_posted);
// if (!$stmt_posted) {
//     die("Error in query preparation: " . $con->error);
// }

// $stmt_posted->bind_param("s", $username);
// $stmt_posted->execute();
// $result_posted = $stmt_posted->get_result();
// $posted_jobs_count = $result_posted->fetch_assoc()['posted_jobs_count'] ?? 0;
// $stmt_posted->close();

// //Bookmarked candidates Count
// $query_bookmarked = "SELECT COUNT(*) AS shortlisted_candidates_count FROM recruiter_shortlist WHERE S_id = ?";
// $stmt_bookmarked = $con->prepare($query_bookmarked);
// if (!$stmt_bookmarked) {
//     die("Error in query preparation: " . $con->error);
// }

// $stmt_bookmarked->bind_param("s", $username);
// $stmt_bookmarked->execute();
// $result_bookmarked = $stmt_bookmarked->get_result();
// $shortlisted_candidates_count = $result_bookmarked->fetch_assoc()['bookmarked_jobs_count'] ?? 0;
// $stmt_bookmarked->close();

// //posted Jobs List
// $query_shortlist = "SELECT a.Name, r.CName,a.Deadline, ss.Applied_Date, ss.Status
//                        FROM seeker_seeks ss
//                        INNER JOIN applications a ON ss.A_id = a.A_id
//                        INNER JOIN recruiter r ON a.R_id = r.R_id
//                        WHERE ss.S_id = ?
//                        ORDER BY a.Deadline
//                        LIMIT 5";
// $stmt_applied_list = $con->prepare($query_applied_list);
// if (!$stmt_applied_list) {
//     die("Error in query preparation: " . $con->error);
// }
// $stmt_applied_list->bind_param("s", $username);
// $stmt_applied_list->execute();
// $result_applied_list = $stmt_applied_list->get_result();

// shortlisted_candidates_count
// $query_bookmarked_list = "SELECT a.Name, a.Deadline
//                           FROM seeker_bookmarks sb
//                           INNER JOIN applications a ON sb.A_id = a.A_id
//                           WHERE sb.S_id = ?";
// $stmt_bookmarked_list = $con->prepare($query_bookmarked_list);
// if (!$stmt_bookmarked_list) {
//     die("Error in query preparation: " . $con->error);
// }
// $stmt_bookmarked_list->bind_param("s", $username);
// $stmt_bookmarked_list->execute();
// $result_bookmarked_list = $stmt_bookmarked_list->get_result();

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
    <div class="content">
        <!-- Section 1: Summary Cards -->
        <div class="section" id="summary-section">
            <div class="card">
                <h3>Total Applications</h3>
                <p id="applied-jobs-count">
                    <?php echo htmlspecialchars($posted_jobs_count); ?>
                </p>
            </div>
            <div class="card">
                <h3>Bookmarks</h3>
                <p id="bookmarked-jobs-count">
                <?php echo htmlspecialchars($shortlisted_candidates_count); ?>
                </p>
            </div>
        </div>

        <!-- Section 2: Applied Jobs List -->
        <div class="section" id="applied-jobs-section">
            <h2>Posted Jobs</h2>
            <ul id="applied-jobs-list">
                <!-- Dynamic data will be loaded here -->
            </ul>
        </div>
    </div>
    
</body>
</html>