<?php
session_start();
require_once 'DBconnect.php';

// Ensure the recruiter is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$recruiter_id = $_SESSION['username'];

// Fetch all jobs posted by the recruiter
$jobs_query = $con->prepare("SELECT A_id, Name FROM applications WHERE R_id = ?");
$jobs_query->bind_param("s", $recruiter_id);
$jobs_query->execute();
$jobs_result = $jobs_query->get_result();

$filter_job_id = $_GET['job_id'] ?? 'all'; // Default filter to 'all'

// Fetch candidates based on the selected job
if ($filter_job_id === 'all') {
    $candidates_query = $con->prepare("
        SELECT ss.S_id, ss.A_id, CONCAT(s.FName, ' ', s.LName) as SeekerName, a.Name as JobName, ss.Applied_Date
        FROM seeker_seeks ss
        JOIN seeker s ON ss.S_id = s.S_id
        JOIN applications a ON ss.A_id = a.A_id
        WHERE a.R_id = ?");
    $candidates_query->bind_param("s", $recruiter_id);
} else {
    $candidates_query = $con->prepare("
        SELECT ss.S_id, ss.A_id, CONCAT(s.FName, ' ', s.LName) as SeekerName, a.Name as JobName, ss.Applied_Date
        FROM seeker_seeks ss
        JOIN seeker s ON ss.S_id = s.S_id
        JOIN applications a ON ss.A_id = a.A_id
        WHERE a.R_id = ? AND ss.A_id = ?");
    $candidates_query->bind_param("si", $recruiter_id, $filter_job_id);
}
$candidates_query->execute();
$candidates_result = $candidates_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css" />
    <title>Employify</title>
</head>

<body>
    <?php include 'includes/e_navbar.php'; ?>
    <?php include 'includes/e_sidebar.php'; ?>

    <div class="candidates-container mt-5">
        <h2>Applicants</h2>

        <!-- Filter Dropdown -->
        <form method="GET" action="e_applied.php" class="search-form">
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
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>

        <!-- Applicants Table -->
        <table class="shortlisted-candidates-list">
            <thead>
                <tr>
                    <th>Job ID</th>
                    <th>Job Name</th>
                    <th>Candidate ID</th>
                    <th>Candidate Name</th>
                    <th>Applied Date</th>
                    <th>Shortlist</th>
                    <th>Reject</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($candidates_result->num_rows > 0) : ?>
                    <?php while ($candidate = $candidates_result->fetch_assoc()) : ?>
                        <tr>
                            <td><?= htmlspecialchars($candidate['A_id']) ?></td>
                            <td><?= htmlspecialchars($candidate['JobName']) ?></td>
                            <td><?= htmlspecialchars($candidate['S_id']) ?></td>
                            <td><?= htmlspecialchars($candidate['SeekerName']) ?></td>
                            <td><?= htmlspecialchars($candidate['Applied_Date']) ?></td>
                            <td><a href="e_shortlist.php?A_id=<?= $candidate['A_id'] ?>&S_id=<?= $candidate['S_id'] ?>"
                                    class="btn btn-success">Shortlist</a></td>
                            <td><a href="e_applied_reject.php?A_id=<?= $candidate['A_id'] ?>&S_id=<?= $candidate['S_id'] ?>"
                                    class="btn btn-danger">Reject</a></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4" class="text-center">No applicants found for the selected job.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<?php
// Close database conections
$jobs_query->close();
$candidates_query->close();
mysqli_close($con);
?>