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
    $stmt = $con->prepare("SELECT * FROM recruiter WHERE R_id LIKE ? OR FName LIKE ? OR LName LIKE ? OR Email LIKE ? OR CName LIKE ?");
    $like_query = "%{$search_query}%";
    $stmt->bind_param("sssss", $like_query, $like_query, $like_query, $like_query, $like_query);
} else {
    $stmt = $con->prepare("SELECT * FROM recruiter");
}

$stmt->execute();
$result = $stmt->get_result();

// Handle deletion
if (isset($_GET['delete']) && $_GET['delete']) {
    $recruiter_id = $_GET['delete'];
    $delete_stmt = $con->prepare("DELETE FROM recruiter WHERE R_id = ?");
    $delete_stmt->bind_param("s", $recruiter_id);
    if ($delete_stmt->execute()) {
        header("Location: a_recruiters.php?success=deleted");
        exit;
    } else {
        header("Location: a_recruiters.php?error=delete_failed");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <title>Admin Panel - Recruiters</title>
</head>

<body>
    <?php include 'includes/a_navbar.php'; ?>
    <?php include 'includes/a_sidebar.php'; ?>

    <main class="search-container">
        <form action="a_recruiters.php" method="GET" class="search-form">
            <input type="text" name="query" placeholder="Search for recruiters" class="search-input"
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
                        <th>Email</th>
                        <th>Date of Birth</th>
                        <th>Company Name</th>
                        <th>Contact</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['R_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['FName'] . ' ' . $row['LName']); ?></td>
                        <td><?php echo htmlspecialchars($row['Email']); ?></td>
                        <td><?php echo htmlspecialchars($row['DoB']); ?></td>
                        <td><?php echo htmlspecialchars($row['CName']); ?></td>
                        <td><?php echo htmlspecialchars($row['Contact']); ?></td>
                        <td>
                            <a href="a_recruiters.php?delete=<?= $row['R_id'] ?>" class="status rejected"
                                onclick="return confirm('Are you sure you want to delete this recruiter?');">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p>No Recruiters Found.</p>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>