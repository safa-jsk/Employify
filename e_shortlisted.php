<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once 'DBconnect.php';

// Ensure recruiter is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Fetch all jobs posted by the recruiter for the filter dropdown
$query_jobs = "SELECT A_id, Name FROM applications WHERE R_id = ?";
$stmt_jobs = $con->prepare($query_jobs);
if (!$stmt_jobs) {
    die("Error in query preparation: " . $con->error);
}
$stmt_jobs->bind_param("s", $username);
$stmt_jobs->execute();
$result_jobs = $stmt_jobs->get_result();
$stmt_jobs->close();

// Initialize shortlisted candidates query
$query_shortlisted_candidates = "
    SELECT sc.S_id, sc.A_id, a.Name AS Job_Name, a.Field, a.Deadline, s.FName AS Seeker_Name, s.Email
    FROM recruiter_shortlist sc
    INNER JOIN applications a ON sc.A_id = a.A_id
    INNER JOIN seeker s ON sc.S_id = s.S_id
    WHERE sc.R_id = ?";

// Add filters if applied
$filters = [];
$parameters = [$username];
$types = "s";

if (isset($_GET['job_id']) && !empty($_GET['job_id'])) {
    $job_id = $_GET['job_id'];
    $query_shortlisted_candidates .= " AND sc.A_id = ?";
    $filters[] = $job_id;
    $types .= "s";
}

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = "%" . $_GET['search'] . "%";
    $query_shortlisted_candidates .= " AND (s.FName LIKE ? OR a.Name LIKE ?)";
    $filters[] = $search_term;
    $filters[] = $search_term;
    $types .= "ss";
}

$query_shortlisted_candidates .= " ORDER BY s.FName ASC";

$stmt_shortlisted_candidates = $con->prepare($query_shortlisted_candidates);
if (!$stmt_shortlisted_candidates) {
    die("Error in query preparation: " . $con->error);
}

if (!empty($filters)) {
    $stmt_shortlisted_candidates->bind_param($types, ...$parameters, ...$filters);
} else {
    $stmt_shortlisted_candidates->bind_param("s", $username);
}
$stmt_shortlisted_candidates->execute();
$result_shortlisted_candidates = $stmt_shortlisted_candidates->get_result();
$stmt_shortlisted_candidates->close();

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Shortlisted Candidates</title>
</head>
<body>
<?php include 'includes/e_navbar.php'; ?>
<?php include 'includes/e_sidebar.php'; ?>

<div class="dashboard_content">
    <h2>Shortlisted Candidates</h2>

    <!-- Filter Form -->
    <form action="" method="get">
        <div class="search-form">
            <select name="job_id" class="search-select">
                <option value="" selected>All Jobs</option>
                <?php while ($job = $result_jobs->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($job['A_id']); ?>" 
                        <?php if (isset($_GET['job_id']) && $_GET['job_id'] == $job['A_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($job['Name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <input type="text" name="search"
                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                class="form-control" placeholder="Search for candidates">
            <button type="submit" class="search-button">Filter</button>
        </div>
    </form>

    <!-- Shortlisted Candidates Table -->
    <?php if ($result_shortlisted_candidates->num_rows > 0): ?>
        <table class="shortlisted-candidates-list">
            <thead>
                <tr>
                    <th>Candidate Name</th>
                    <th>Email</th>
                    <th>Job Name</th>
                    <th>Field</th>
                    <th>Deadline</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_shortlisted_candidates->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['Seeker_Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['Email']); ?></td>
                        <td><?php echo htmlspecialchars($row['Job_Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['Field']); ?></td>
                        <td><?php echo htmlspecialchars($row['Deadline']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No shortlisted candidates found.</p>
    <?php endif; ?>
</div>

</body>
</html>
