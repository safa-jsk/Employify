<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once 'DBconnect.php';

// Ensure correct user is logged in
$pageRole = 'employer';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== $pageRole) {
    echo "<script>alert('You must log in first!'); window.location.href = 'index.php';</script>";
    exit;
}

$recruiter_id = $_SESSION['username'];

// Fetch jobs posted by recruiter
$jobs_query = $con->prepare("SELECT A_id, Name FROM applications WHERE R_id = ?");
$jobs_query->bind_param("s", $recruiter_id);
$jobs_query->execute();
$jobs_result = $jobs_query->get_result();

$filter_job_id = $_GET['job_id'] ?? 'all';

// Fetch accepted candidates
if ($filter_job_id === 'all') {
    $accepted_query = $con->prepare("
        SELECT ss.S_id, ss.A_id, CONCAT(s.FName, ' ', s.LName) AS SeekerName, a.Name AS JobName,
               ss.Applied_Date, ss.Status
        FROM seeker_seeks ss
        JOIN seeker s ON ss.S_id = s.S_id
        JOIN applications a ON ss.A_id = a.A_id
        WHERE a.R_id = ? AND ss.Status = 1
    ");
    $accepted_query->bind_param("s", $recruiter_id);
} else {
    $accepted_query = $con->prepare("
        SELECT ss.S_id, ss.A_id, CONCAT(s.FName, ' ', s.LName) AS SeekerName, a.Name AS JobName,
               ss.Applied_Date, ss.Status
        FROM seeker_seeks ss
        JOIN seeker s ON ss.S_id = s.S_id
        JOIN applications a ON ss.A_id = a.A_id
        WHERE a.R_id = ? AND ss.A_id = ? AND ss.Status = 1
    ");
    $accepted_query->bind_param("si", $recruiter_id, $filter_job_id);
}

$accepted_query->execute();
$accepted_result = $accepted_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Accepted Candidates</title>
</head>
<body>
    <?php include 'includes/e_navbar.php'; ?>
    <?php include 'includes/e_sidebar.php'; ?>

    <div class="dashboard_content">
        <h2>Accepted Candidates</h2>

        <!-- Filter Dropdown -->
        <form method="GET" action="e_accepted.php" class="search-form">
            <div class="filter-container">
                <select id="jobFilter" name="job_id" class="search-select">
                    <option value="all" <?= $filter_job_id === 'all' ? 'selected' : '' ?>>All Jobs</option>
                    <?php while ($job = $jobs_result->fetch_assoc()) : ?>
                        <option value="<?= htmlspecialchars($job['A_id']) ?>"
                            <?= $filter_job_id == $job['A_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($job['Name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="filter-button">Filter</button>
        </form>

        <!-- Accepted Candidates Table -->
        <table class="accepted-candidates-list">
            <thead>
                <tr>
                    <th>Job Name</th>
                    <th>Candidate Name</th>
                    <th>Applied Date</th>
                    <th>Status</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($accepted_result->num_rows > 0) : ?>
                    <?php while ($candidate = $accepted_result->fetch_assoc()) : ?>
                        <tr>
                            <td><?= htmlspecialchars($candidate['JobName']) ?></td>
                            <td><?= htmlspecialchars($candidate['SeekerName']) ?></td>
                            <td><?= htmlspecialchars($candidate['Applied_Date']) ?></td>
                            <td>Accepted</td>
                            <td>
                                <a href="e_accepted_remove.php?A_id=<?= $candidate['A_id'] ?>&S_id=<?= $candidate['S_id'] ?>" 
                                   class="status rejected">Remove</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-center">No accepted candidates found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$jobs_query->close();
$accepted_query->close();
$con->close();
?>
