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

    <div class="content">
        <h1>Bookmarked Jobs</h1>
        <?php
        session_start();

        require_once 'DBconnect.php'; // use $con

        // Check if the user is logged in
        if (!isset($_SESSION['username'])) {
            echo "<p>You need to log in to view your applied jobs.</p>";
            exit;
        }

        $username = $_SESSION['username'];

        // Query to retrieve bookmarked jobs
        $query = "SELECT sb.A_id, a.Name, r.CName, a.Field, a.Salary, a.Deadline, a.Description
                    FROM seeker_bookmarks sb
                    INNER JOIN applications a ON sb.A_id = a.A_id
                    INNER JOIN recruiter r ON a.R_id = r.R_id
                    WHERE sb.S_id = ?";

        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $username);
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
                </tr>';

            while ($row = $result->fetch_assoc()) {

                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['A_id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['CName']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Field']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Salary']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Deadline']) . '</td>';
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
</body>

</html>