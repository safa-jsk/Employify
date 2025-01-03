<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once 'DBconnect.php';

// Ensure only employers can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'employer') {
    header("Location: index.php");
    exit();
}

// Handle search query and filter
$search_query = '';
$filter = 'all';
if (isset($_GET['query']) && isset($_GET['filter'])) {
    $search_query = trim($_GET['query']);
    $filter = $_GET['filter'];

    if ($filter === 'all') {
        $stmt = $con->prepare("SELECT * FROM seeker 
                               WHERE skills LIKE ? 
                                  OR experience >= ? 
                                  OR education LIKE ?");
        $like_query = "%{$search_query}%";
        $stmt->bind_param("sis", $like_query, $like_query, $like_query);
    } elseif ($filter === 'name') {
        $stmt = $con->prepare("SELECT * FROM seeker WHERE FName LIKE ? OR LName LIKE ?");
        $like_query = "%{$search_query}%";
        $stmt->bind_param("ss", $like_query, $like_query);
    } elseif ($filter === 'skills') {
        $stmt = $con->prepare("SELECT * FROM seeker WHERE skills LIKE ?");
        $like_query = "%{$search_query}%";
        $stmt->bind_param("s", $like_query);
    } elseif ($filter === 'experience') {
        $stmt = $con->prepare("SELECT * FROM seeker WHERE experience >= ?");
        $like_query = "%{$search_query}%";
        $stmt->bind_param("i", $like_query);
    } elseif ($filter === 'education') {
        $stmt = $con->prepare("SELECT * FROM seeker WHERE education LIKE ?");
        $like_query = "%{$search_query}%";
        $stmt->bind_param("s", $like_query);
    }
} elseif ($filter === 'contact') {
    $stmt = $con->prepare("SELECT * FROM seeker WHERE contact LIKE ?");
    $like_query = "%{$search_query}%";
    $stmt->bind_param("s", $like_query);
} else {
    $stmt = $con->prepare("SELECT * FROM seeker");
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

    <div class="dashboard_content">
        <h2>Search Candidates</h2>
        <form action="e_search-candidates.php" method="GET" class="search-form">
            <select name="filter" class="search-select">
                <option value="all" <?= $filter === 'all' ? 'selected' : '' ?>>All</option>
                <option value="name" <?= $filter === 'name' ? 'selected' : '' ?>>Name</option>
                <option value="skills" <?= $filter === 'skills' ? 'selected' : '' ?>>Skills</option>
                <option value="experience" <?= $filter === 'experience' ? 'selected' : '' ?>>Experience</option>
                <option value="education" <?= $filter === 'education' ? 'selected' : '' ?>>Education</option>
                <option value="contact" <?= $filter === 'contact' ? 'selected' : '' ?>>Contact</option>
            </select>
            <input type="text" name="query" placeholder="Search for candidates" class="search-input"
                value="<?= htmlspecialchars($search_query); ?>">
            <button type="submit" class="search-button">Search</button>
        </form>


        <?php if ($result->num_rows > 0): ?>
            <table class="shortlisted-candidates-list">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Skills</th>
                        <th>Experience</th>
                        <th>Education</th>
                        <th>Contact</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['FName'] . ' ' . $row['LName']); ?></td>
                            <td><?php echo isset($row['Email']) ? htmlspecialchars($row['Email']) : ''; ?></td>
                            <td><?php echo isset($row['Skills']) ? htmlspecialchars($row['Skills']) : ''; ?></td>
                            <td><?php echo isset($row['Experience']) ? htmlspecialchars($row['Experience']) : ''; ?></td>
                            <td><?php echo isset($row['Education']) ? htmlspecialchars($row['Education']) : ''; ?></td>
                            <td><?php echo isset($row['Contact']) ? htmlspecialchars($row['Contact']) : ''; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No Candidates Found.</p>
        <?php endif; ?>

    </div>

</body>

</html>

<?php
$stmt->close();
$con->close();
?>