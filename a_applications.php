<?php
session_start();
require_once 'DBconnect.php';

// Ensure the admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Handle search query
$search_query = '';
if (isset($_GET['query'])) {
    $search_query = trim($_GET['query']);
    $stmt = $con->prepare("SELECT * FROM applications WHERE A_id LIKE ? R_id LIKE ? OR Name LIKE ? OR Field LIKE ? OR Salary LIKE ? ");
    $like_query = "%{$search_query}%";
    $stmt->bind_param("sssss", $like_query, $like_query, $like_query, $like_query, $like_query);
} else {
    $stmt = $con->prepare("SELECT * FROM applications");
}

$stmt->execute();
$result = $stmt->get_result();

// Handle deletion
if (isset($_GET['delete']) && $_GET['delete']) {
    $application_id = $_GET['delete'];
    $delete_stmt = $con->prepare("DELETE FROM applicaitons WHERE A_id = ?");
    $delete_stmt->bind_param("s", $application_id);
    if ($delete_stmt->execute()) {
        header("Location: a_applications.php?success=deleted");
        exit;
    } else {
        header("Location: a_applications.php?error=delete_failed");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css" />
    <title>Admin Panel - Applications</title>
</head>

<body>
    <?php include 'includes/a_navbar.php'; ?>
    <?php include 'includes/a_sidebar.php'; ?>

    <main class="search-container">
        <form action="a_applications.php" method="GET" class="search-form">
            <input type="text" name="query" placeholder="Search for applications" class="search-input"
                value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit" class="search-button">Search</button>
        </form>

        <div class="filtered-section">
            <?php if ($result->num_rows > 0): ?>
                <table class="filtered-job-list">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Recruiter</th>
                            <th>Field</th>
                            <th>Status</th>
                            <th>Deadline</th>
                            <th>Salary</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()):
                            //Recruiter Name
                            $recruiter = "SELECT CONCAT(FName, ' ', LName) AS Name FROM recruiter WHERE R_id = ?";
                            $stmt_recruiter = $con->prepare($recruiter);
                            $stmt_recruiter->bind_param("s", $row['R_id']);
                            $stmt_recruiter->execute();
                            $result_recruiter = $stmt_recruiter->get_result();
                            $recruiter_name = $result_recruiter->fetch_assoc()['Name'];
                            $stmt_recruiter->close();
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['A_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['Name']); ?></td>
                                <td><?php echo htmlspecialchars($recruiter_name); ?></td>
                                <td><?php echo htmlspecialchars($row['Field']); ?></td>
                                <td>
                                    <span class="btn <?php echo $row['Status'] == 1 ? 'btn-success' : 'btn-danger'; ?>">
                                        <?php echo $row['Status'] == 1 ? 'ACTIVE' : 'INACTIVE'; ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($row['Deadline']); ?></td>
                                <td><?php echo htmlspecialchars($row['Salary']); ?></td>
                                <td>
                                    <a href="a_applications.php?delete=<?= $row['A_id'] ?>" class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this application?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No Applications Found.</p>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>