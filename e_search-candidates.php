<?php
session_start();
require_once 'DBconnect.php';

// Ensure only employers can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'employer') {
    header("Location: index.php");
    exit();
}

// Handle search query
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = trim($_GET['search']);
    $stmt = $con->prepare("SELECT S_id, FName, LName, Skills, Email, Experience, Education, Contact
                           FROM seeker 
                           WHERE skills LIKE ?");
    $like_query = "%{$search_query}%";
    $stmt->bind_param("s", $like_query);
} else {
    $stmt = $con->prepare("SELECT S_id, FName, LName, Skills, Email, Experience, Education, Contact
                           FROM seeker");
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Search Candidates</title>
</head>

<body>
    <?php include 'includes/e_navbar.php'; ?>
    <?php include 'includes/e_sidebar.php'; ?>

    <main class="search-container">
        <form action="e_search-candidates.php" method="GET" class="search-form">
            <select name="filter" class="search-select">
                <option value="all">All</option>
                <option value="<?php echo htmlspecialchars($search_query); ?>">Skills</option>
            </select>
            <input type="text" name="query" placeholder="Search for candidates" class="search-input">
            <button type="submit" class="search-button">Search</button>
        </form>

        <div class="filtered-section">
            <?php if ($result->num_rows > 0): ?>
                <table class="filtered-job-list">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Skills</th>
                            <th>Experience</th>
                            <th>Education</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['FName'] . ' ' . $row['LName']); ?></td>
                                <td><?php echo htmlspecialchars($row['Email']); ?></td>
                                <td><?php echo htmlspecialchars($row['Skills']); ?></td>
                                <td><?php echo htmlspecialchars($row['Experience']); ?></td>
                                <td><?php echo htmlspecialchars($row['Education']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No Candidates Found.</p>
            <?php endif; ?>
        </div>
    </main>

</body>

</html>

<?php
$stmt->close();
$con->close();
?>