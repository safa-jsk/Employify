<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once 'DBconnect.php';

$pageRole = 'employer';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== $pageRole) {
    echo "<script>alert('You must log in first!'); window.location.href = 'index.php';</script>";
    exit;
}

$recruiter_id = $_SESSION['username'];

// Fetch jobs posted by recruiter
$jobs_query = $con->prepare("SELECT A_id, Name 
                            FROM applications 
                            WHERE R_id = ?");

$jobs_query->bind_param("s", $recruiter_id);
$jobs_query->execute();
$jobs_result = $jobs_query->get_result();

$filter_job_id = $_GET['job_id'] ?? 'all';
$search_query = trim($_GET['query'] ?? '');
$filter = $_GET['filter'] ?? 'all';

// Dynamic Query for candidates
$candidates_query_str = "SELECT ss.S_id, ss.A_id, CONCAT(s.FName, ' ', s.LName) AS SeekerName, 
                         a.Name AS JobName, ss.Applied_Date, ss.Status,
                         CASE WHEN EXISTS (
                             SELECT 1 FROM recruiter_shortlist rs 
                             WHERE rs.S_id = ss.S_id AND rs.A_id = ss.A_id AND rs.R_id = ?
                         ) THEN 1 ELSE 0 END AS IsShortlisted";

if ($filter === 'skills') {
    $candidates_query_str .= ", s.Skills AS FilterColumn";
} elseif ($filter === 'education') {
    $candidates_query_str .= ", s.Education AS FilterColumn";
} elseif ($filter === 'experience') {
    $candidates_query_str .= ", s.Experience AS FilterColumn";
}

$candidates_query_str .= " FROM seeker_seeks ss
                          JOIN seeker s ON ss.S_id = s.S_id
                          JOIN applications a ON ss.A_id = a.A_id
                          WHERE a.R_id = ?";

if ($filter_job_id !== 'all') {
    $candidates_query_str .= " AND ss.A_id = ?";
}

if (($search_query)) {
    if ($filter === 'skills') {
        $like_query = "%{$search_query}%";
        $candidates_query_str .= " AND s.Skills LIKE ?";
    } elseif ($filter === 'education') {
        $like_query = "%{$search_query}%";
        $candidates_query_str .= " AND s.Education LIKE ?";
    } elseif ($filter === 'experience') {
        $like_query = "{$search_query}";
        $candidates_query_str .= " AND s.Experience >= ?";
    }
}

$candidates_query = $con->prepare($candidates_query_str);

// Binding parameters based on conditions
if ($filter_job_id === 'all' && !($search_query)) {
    $candidates_query->bind_param("ss", $recruiter_id, $recruiter_id);
} elseif ($filter_job_id === 'all' && ($search_query)) {
    if ($filter !== 'experience') {
        $candidates_query->bind_param("sss", $recruiter_id, $recruiter_id, $like_query);
    } else {
        $like_query = intval($like_query);
        $candidates_query->bind_param("ssi", $recruiter_id, $recruiter_id, $like_query);
    }
} elseif ($filter_job_id !== 'all' && !($search_query)) {
    $candidates_query->bind_param("ssi", $recruiter_id, $recruiter_id, $filter_job_id);
} elseif ($filter_job_id !== 'all' && ($search_query)) {
    if ($filter !== 'experience') {
        $candidates_query->bind_param("ssis", $recruiter_id, $recruiter_id, $filter_job_id, $like_query);
    } else {
        $like_query = intval($like_query);
        $candidates_query->bind_param("ssii", $recruiter_id, $recruiter_id, $filter_job_id, $like_query);
    }
}

$candidates_query->execute();
$candidates_result = $candidates_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <title>Applied Jobs</title>
</head>

<body>
    <?php include 'includes/e_navbar.php'; ?>
    <?php include 'includes/e_sidebar.php'; ?>

    <div class="dashboard_content">
        <h2>Applicants</h2>

        <form action="e_applied.php" method="GET" class="search-form">
            <div class="filter-container" style="display: flex; align-items: center; gap: 10px;">
                <select id="jobFilter" name="job_id" class="search-select">
                    <option value="all" <?= $filter_job_id === 'all' ? 'selected' : '' ?>>All Jobs</option>
                    <?php while ($job = $jobs_result->fetch_assoc()) : ?>
                        <option value="<?= htmlspecialchars($job['A_id']) ?>"
                            <?= $filter_job_id == $job['A_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($job['Name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <select name="filter" class="search-select">
                    <option value="skills" <?= $filter === 'skills' ? 'selected' : '' ?>>Skills</option>
                    <option value="experience" <?= $filter === 'experience' ? 'selected' : '' ?>>Experience</option>
                    <option value="education" <?= $filter === 'education' ? 'selected' : '' ?>>Education</option>
                </select>
            </div>

            <input type="text" name="query" placeholder="Search for candidates" class="search-input"
                value="<?= htmlspecialchars($search_query); ?>">
            <button type="submit" class="search-button">Search</button>
        </form>

        <table class="shortlisted-candidates-list">
            <thead>
                <tr>
                    <th>Job Name</th>
                    <th>Candidate Name</th>
                    <?php if ($filter !== 'all'): ?>
                        <th><?= ucfirst($filter) ?></th>
                    <?php endif; ?>
                    <th>Status</th>
                    <th>Shortlist</th>
                    <th>Reject</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($candidate = $candidates_result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($candidate['JobName']) ?></td>
                        <td><?= htmlspecialchars($candidate['SeekerName']) ?></td>
                        <?php if ($filter !== 'all'): ?>
                            <td><?= htmlspecialchars($candidate['FilterColumn']) ?></td>
                        <?php endif; ?>
                        <td>
                            <?php
                            if (is_null($candidate['Status'])) {
                                echo '<span style="color: #ffbe00;">On-Hold</span>';
                            } elseif ($candidate['Status'] == 1) {
                                echo '<span style="color: #009700;">Accepted</span>';
                            } elseif ($candidate['Status'] == 0) {
                                echo '<span style="color: #dc3545;">Red</span>';
                            } else {
                                echo 'Shortlisted';
                            }
                            ?>
                        </td>
                        <td>
                            <?php if ($candidate['IsShortlisted']): ?>
                                <button class="applied-button" disabled>Shortlisted</button>
                            <?php else: ?>
                                <a href="e_shortlist.php?A_id=<?= $candidate['A_id'] ?>&S_id=<?= $candidate['S_id'] ?>"
                                    class="status accepted">Shortlist</a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!is_null($candidate['Status'])): ?>
                                <button class="applied-button" disabled>Rejected</button>
                            <?php else: ?>
                                <a href="e_applied_reject.php?A_id=<?= $candidate['A_id'] ?>&S_id=<?= $candidate['S_id'] ?>"
                                    class="status rejected">Reject</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<?php
$jobs_query->close();
$candidates_query->close();
$con->close();
?>