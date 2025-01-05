<?php
session_start();
require_once 'DBconnect.php';

// Ensure the admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('You not authorized to view this page!'); window.location.href = 'index.php';</script>";
    exit;
}

// Handle search query
$search_query = '';
if (isset($_GET['query'])) {
    $search_query = trim($_GET['query']);
    $stmt = $con->prepare("SELECT * FROM feedback 
                            WHERE Name LIKE ? OR Email LIKE ? OR 
                            Subject LIKE ? OR Message LIKE ?");
    $like_query = "%{$search_query}%";
    $stmt->bind_param("ssss", $like_query, $like_query, $like_query, $like_query);
} else {
    $stmt = $con->prepare("SELECT * FROM feedback");
}

$stmt->execute();
$result = $stmt->get_result();

// Handle deletion
if (isset($_GET['delete']) && $_GET['delete']) {
    $msg_id = intval($_GET['delete']);
    $delete_stmt = $con->prepare("DELETE FROM feedback WHERE msg_id = ?");
    $delete_stmt->bind_param("i", $msg_id);
    if ($delete_stmt->execute()) {
        header("Location: a_feedback.php?success=deleted");
        exit;
    } else {
        header("Location: a_feedback.php?error=delete_failed");
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
    <title>Admin Panel - Feedback</title>
</head>

<body>
    <?php include 'includes/a_navbar.php'; ?>
    <?php include 'includes/a_sidebar.php'; ?>
    
    <main class="search-container">
        <h3>Feedbacks</h3>
        <form action="a_feedback.php" method="GET" class="search-form">
            <input type="text" name="query" placeholder="Search for feedback" class="search-input"
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
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['msg_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['Name']); ?></td>
                                <td><?php echo htmlspecialchars($row['Email']); ?></td>
                                <td><?php echo htmlspecialchars($row['Subject']); ?></td>
                                <td><?php echo htmlspecialchars($row['Message']); ?></td>
                                <td>
                                    <a href="a_feedback.php?delete=<?= $row['msg_id'] ?>" class="status rejected"
                                        onclick="return confirm('Are you sure you want to delete this feedback?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No Feedback Found.</p>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>