<?php
// session_start();

// if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
//     header("location: dashboard.php");
//     exit;
// }
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
                <p id="applied-jobs-count">"total-applications-count"</p>
            </div>
            <div class="card">
                <h3>Bookmarks</h3>
                <p id="bookmarked-jobs-count">"advertise-count"</p>
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