<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once 'DBconnect.php';

// Ensure correct user is logged in
$pageRole = 'job_seeker';
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
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="style.css" />
    <title>Bookmarked Jobs</title>
</head>

<body>
    <?php include 'includes/js_navbar.php'; ?>
    <?php include 'includes/js_sidebar.php'; ?>

    <div class="jobs_content">
        <h2>Bookmarked Jobs</h2>
        <?php
        // session_start();

        require_once 'DBconnect.php'; // use $con

        // Check if the user is logged in
        if (!isset($_SESSION['username'])) {
            echo "<p>You need to log in to view your applied jobs.</p>";
            exit;
        }

        $username = $_SESSION['username'];

        $query = "SELECT sb.A_id, a.Name, r.CName, a.Field, a.Salary, a.Deadline, a.Description,
                 (SELECT COUNT(*) FROM seeker_seeks ss WHERE ss.S_id = ? AND ss.A_id = sb.A_id) AS has_applied
                  FROM seeker_bookmarks sb
                  INNER JOIN applications a ON sb.A_id = a.A_id
                  INNER JOIN recruiter r ON a.R_id = r.R_id
                  WHERE sb.S_id = ?";

        $stmt = $con->prepare($query);
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the user has bookmarked any jobs
        if ($result->num_rows > 0) {
            echo '<table class="bookmarked-jobs-table">';
            echo '<tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Field</th>
                    <th>Salary</th>
                    <th>Deadline</th>
                    <th>Apply</th>
                    <th>Remove</th>
                </tr>';

            while ($row = $result->fetch_assoc()) {

                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['A_id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['CName']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Field']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Salary']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Deadline']) . '</td>';

                if ($row['has_applied'] > 0) {
                    echo '<td><button class="applied-button" disabled>Applied</button></td>';
                } else {
                    echo '<td><a href="js_apply.php?A_id=' . htmlspecialchars($row['A_id']) .
                        '" class="search-button" name="apply">Apply</a></td>';
                }

                echo '<td><a href="js_remove_bookmark.php?A_id=' . htmlspecialchars($row['A_id']) .
                    '" class="status rejected" name="remove">Remove</a></td>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo "<p>You have not bookmarked any jobs yet.</p>";
        }

        $stmt->close();
        mysqli_close($con);
        ?>
    </div>
    <?php
    if (isset($_GET['message']) && !empty($_GET['message'])) {
        $message = htmlspecialchars($_GET['message']);
        echo "<script> alert('$message'); </script>";
    }
    ?>
</body>

</html>