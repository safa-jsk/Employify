<!-- Start the session -->
<?php
// session_start();

// // Check if the user is logged in
// if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
//     header("Location: login.php"); // Redirect to login page if not logged in
//     exit;
// }

// // set connection
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "employify";

// // Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// // Fetch counts
// $applied_jobs_count = $conn->query("SELECT COUNT(*) as count FROM applied_jobs")->fetch_assoc()['count'];
// $bookmarked_jobs_count = $conn->query("SELECT COUNT(*) as count FROM bookmarked_jobs")->fetch_assoc()['count'];

// // Fetch detailed applied jobs
// $applied_jobs = $conn->query("SELECT name, status, link FROM applied_jobs");

// // Fetch detailed bookmarked jobs
// $bookmarked_jobs = $conn->query("SELECT name, deadline FROM bookmarked_jobs");

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
                <h3>Applied Jobs</h3>
                <p id="applied-jobs-count">"applied-jobs-count"</p>
            </div>
            <div class="job_card">
                <h3>Bookmarks</h3>
                <p id="bookmarked-jobs-count">"bookmarked-jobs-count"</p>
            </div>
        </div>

        <!-- Section 2: Applied Jobs List -->
        <div class="dashboard_section" id="applied-jobs-section">
            <h2>Applied Jobs</h2>
            <ul id="applied-jobs-list">
                <!-- Dynamic data will be loaded here -->
            </ul>
        </div>

        <!-- Section 3: Bookmarks List -->
        <div class="dashboard_section" id="bookmarks-section">
            <h2>Bookmarked Jobs</h2>
            <ul id="bookmarked-jobs-list">
                <!-- Dynamic data will be loaded here -->
            </ul>
        </div>
    </div>
</body>
</html>