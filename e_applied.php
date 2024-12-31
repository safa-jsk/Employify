<?php
session_start();
require_once 'DBconnect.php'; // Include the database conection file

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php?message=" . urlencode("You need to log in to view applicants."));
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
        SELECT ss.S_id, ss.A_id, s.S_id as SeekerName, a.Name as JobName 
        FROM seeker_seeks ss
        JOIN seeker s ON ss.S_id = s.S_id
        JOIN applications a ON ss.A_id = a.A_id
        WHERE a.R_id = ?");
    $candidates_query->bind_param("s", $recruiter_id);
} else {
    $candidates_query = $con->prepare("
        SELECT ss.S_id, ss.A_id, s.S_id as SeekerName, a.Name as JobName 
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
    <title>Post a Job - Employify</title>
</head>

<body>
    <?php include 'includes/e_navbar.php'; ?>
    <?php include 'includes/e_sidebar.php'; ?>

    <div class="candidates-container mt-5">
        <h1 class="mb-4">Applicants</h1>

        <!-- Filter Dropdown -->
        <form method="GET" action="e_applied.php" class="mb-4">
            <div class="mb-3">
                <label for="jobFilter" class="form-label">Filter by Job</label>
                <select id="jobFilter" name="job_id" class="form-control">
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
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Candidate ID</th>
                    <th>Candidate Name</th>
                    <th>Job ID</th>
                    <th>Job Name</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($candidates_result->num_rows > 0) : ?>
                    <?php while ($candidate = $candidates_result->fetch_assoc()) : ?>
                        <tr>
                            <td><?= htmlspecialchars($candidate['S_id']) ?></td>
                            <td><?= htmlspecialchars($candidate['SeekerName']) ?></td>
                            <td><?= htmlspecialchars($candidate['A_id']) ?></td>
                            <td><?= htmlspecialchars($candidate['JobName']) ?></td>
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