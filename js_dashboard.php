<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once 'DBconnect.php';

// Ensure correct user is logged in
$pageRole = 'job_seeker';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== $pageRole) {
    echo "<script>alert('You must log in first!'); window.location.href = 'index.php';</script>";
    exit;
}

$username = $_SESSION['username'];

//Applied Jobs Count
$query_applied = "SELECT COUNT(*) AS applied_jobs_count FROM seeker_seeks WHERE S_id = ?";
$stmt_applied = $con->prepare($query_applied);
if (!$stmt_applied) {
    die("Error in query preparation: " . $con->error);
}

$stmt_applied->bind_param("s", $username);
$stmt_applied->execute();
$result_applied = $stmt_applied->get_result();
$applied_jobs_count = $result_applied->fetch_assoc()['applied_jobs_count'] ?? 0;
$stmt_applied->close();

//Bookmarked Jobs Count
$query_bookmarked = "SELECT COUNT(*) AS bookmarked_jobs_count 
                     FROM seeker_bookmarks 
                     WHERE S_id = ?";

$stmt_bookmarked = $con->prepare($query_bookmarked);
if (!$stmt_bookmarked) {
    die("Error in query preparation: " . $con->error);
}

$stmt_bookmarked->bind_param("s", $username);
$stmt_bookmarked->execute();
$result_bookmarked = $stmt_bookmarked->get_result();
$bookmarked_jobs_count = $result_bookmarked->fetch_assoc()['bookmarked_jobs_count'] ?? 0;
$stmt_bookmarked->close();

// Total accepted count
$query_accepted = "SELECT COUNT(*) AS total_accepted
                    FROM seeker_seeks s
                    INNER JOIN applications a 
                    ON s.A_id = a.A_id
                    WHERE s.S_id = ? AND s.Status = 1";

$stmt_accepted = $con->prepare($query_accepted);
if (!$stmt_accepted) {
    die("Error in query preparation: " . $con->error);
}

$stmt_accepted->bind_param("s", $username);
$stmt_accepted->execute();
$result_accepted = $stmt_accepted->get_result();
$total_accepted = $result_accepted->fetch_assoc()["total_accepted"] ?? 0;
$stmt_accepted->close();

// Extracting skills for recommendations
$query_skills = "SELECT Skills FROM seeker WHERE S_id = ?";
$stmt_skills = $con->prepare($query_skills);
$stmt_skills->bind_param("s", $username);
$stmt_skills->execute();
$result_skills = $stmt_skills->get_result();
$skillsRow = $result_skills->fetch_assoc();
$skills = $skillsRow['Skills'] ?? ''; // Skills as a comma-separated string

$skillsArray = array_map('trim', explode(',', $skills)); // Convert to array

// Build query based on skills
if (!empty($skillsArray)) {
    $descriptionConditions = [];
    foreach ($skillsArray as $skill) {
        $descriptionConditions[] = "A.Description LIKE CONCAT('%', ?, '%')";
    }
    $descriptionQueryPart = implode(' OR ', $descriptionConditions);

    $query_recommended = "SELECT DISTINCT A.A_id, A.Name, A.Field, A.deadline
                          FROM applications A 
                          JOIN seeker S ON S.S_id = ? 
                          WHERE A.Status = 1 
                          AND (FIND_IN_SET(TRIM(A.Field), TRIM(S.Skills)) > 0 
                               OR ($descriptionQueryPart))
                          ORDER BY A.Posted_Date DESC;";
} else {
    $query_recommended = "SELECT DISTINCT A.A_id, A.Name, A.Field, A.deadline
                          FROM applications A 
                          JOIN seeker S ON S.S_id = ? 
                          WHERE A.Status = 1 
                          AND FIND_IN_SET(TRIM(A.Field), TRIM(S.Skills)) > 0
                          ORDER BY A.Posted_Date DESC;";
}

$stmt_recommended = $con->prepare($query_recommended);

if (!empty($skillsArray)) {
    $types = str_repeat('s', count($skillsArray) + 1); // 1 for S_id + skills
    $params = array_merge([$username], $skillsArray);
    $stmt_recommended->bind_param($types, ...$params);
} else {
    $stmt_recommended->bind_param("s", $username);
}

$stmt_recommended->execute();
$result_recommended = $stmt_recommended->get_result();


// Fetch job details based on the job_id
$stmt = $con->prepare("SELECT A.A_id, A.Name AS Post, A.Description, A.Salary, A.Deadline, A.Field,
                       C.CName AS Company, C.Contact, C.Email
                       FROM applications A
                       INNER JOIN recruiter C ON A.R_id = C.R_id 
                       WHERE A.A_id = ? AND A.Status = 1");

if (isset($_GET['data-job-id']) && !empty($_GET['data-job-id'])) {
    $A_id = $_GET['data-job-id'];
    $stmt->bind_param("i", $A_id);
    $stmt->execute();
    $result = $stmt->get_result();
}


if ($result->num_rows > 0) {
    $job = $result->fetch_assoc();

    $jobTitle = htmlspecialchars($job['Post']);
    $jobField = htmlspecialchars($job['Field']);
    $jobSalary = htmlspecialchars($job['Salary']);
    $jobDeadline = htmlspecialchars($job['Deadline']);
    $jobDescription = htmlspecialchars($job['Description']);
    $companyName = htmlspecialchars($job['Company']);
    $companyContact = htmlspecialchars($job['Contact']);
    $companyEmail = htmlspecialchars($job['Email']);
}

$stmt->close();

//Applied Jobs List
$query_applied_list = "SELECT a.Name, r.CName,a.Deadline, ss.Applied_Date, ss.Status
                       FROM seeker_seeks ss
                       INNER JOIN applications a ON ss.A_id = a.A_id
                       INNER JOIN recruiter r ON a.R_id = r.R_id
                       WHERE ss.S_id = ?
                       ORDER BY ss.Status DESC, a.Deadline ASC";

$stmt_applied_list = $con->prepare($query_applied_list);
if (!$stmt_applied_list) {
    die("Error in query preparation: " . $con->error);
}
$stmt_applied_list->bind_param("s", $username);
$stmt_applied_list->execute();
$result_applied_list = $stmt_applied_list->get_result();

//Bookmarked Jobs List
$query_bookmarked_list = "SELECT a.Name, a.Deadline
                          FROM seeker_bookmarks sb
                          INNER JOIN applications a ON sb.A_id = a.A_id
                          WHERE sb.S_id = ?";

$stmt_bookmarked_list = $con->prepare($query_bookmarked_list);
if (!$stmt_bookmarked_list) {
    die("Error in query preparation: " . $con->error);
}

$stmt_bookmarked_list->bind_param("s", $username);
$stmt_bookmarked_list->execute();
$result_bookmarked_list = $stmt_bookmarked_list->get_result();

$con->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="style.css" />
    <title>Dashboard</title>
</head>

<body>
    <?php include 'includes/js_navbar.php'; ?>
    <?php include 'includes/js_sidebar.php'; ?>

    <div class="dashboard_content">
        <!-- Section 1: Summary Cards -->
        <div class="dashboard_section" id="summary-section">
            <div class="job_card">
                <h3>Number of Applied Jobs</h3>
                <p id="applied-jobs-count">
                    <?php echo htmlspecialchars($applied_jobs_count); ?>
                </p>
            </div>
            <div class="job_card">
                <h3>Total Bookmarks</h3>
                <p id="bookmarked-jobs-count">
                    <?php echo htmlspecialchars($bookmarked_jobs_count); ?>
                </p>
            </div>
            <div class="job_card">
                <h3>Accepted Applications</h3>
                <p id="accepted-applications-count">
                    <?php echo htmlspecialchars($total_accepted); ?>
                </p>
            </div>
        </div>

        <!-- Section 2: Recommended Jobs List Based on Skills -->
        <h2>Recommended Jobs</h2>
        <div class="dashboard_section scrollable" id="job-recommendations-section">
            <table class="job-recommendations">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Field</th>
                        <th>Deadline</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_recommended->num_rows > 0): ?>
                        <?php while ($row = $result_recommended->fetch_assoc()): ?>
                            <tr>
                                <td> <?= htmlspecialchars($row['A_id']); ?> </td>
                                <td> <?= htmlspecialchars($row['Name']); ?> </td>
                                <td> <?= htmlspecialchars($row['Field']); ?> </td>
                                <td> <?= htmlspecialchars($row['deadline']); ?> </td>
                                <td><a href="?data-job-id=<?= $row['A_id']; ?>#jobDetailsModal" class="view-details">View
                                        Details</a></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No job recommendations based on your skills.</td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>

        <!-- Job Details Modal -->
        <?php if (isset($_GET['data-job-id']) && !empty($_GET['data-job-id'])): ?>
            <div id="jobDetailsModal" class="popup">
                <div class="popup-content">
                    <?php if (isset($job) && $job): ?>
                        <p><strong>Company Name:</strong> <?= htmlspecialchars($job['Company']); ?></p>
                        <p><strong>Contact:</strong><?= htmlspecialchars($job['Contact']); ?></p>
                        <p><strong>Email:</strong><?= htmlspecialchars($job['Email']); ?></p>
                        <p><strong>Post:</strong><?= htmlspecialchars($job['Post']); ?></p>
                        <p><strong>Field:</strong><?= htmlspecialchars($job['Field']); ?></p>
                        <p><strong>Salary:</strong><?= htmlspecialchars($job['Salary']); ?></p>
                        <p><strong>Deadline:</strong><?= htmlspecialchars($job['Deadline']); ?></p>
                        <p><strong>Description:</strong><?= htmlspecialchars($job['Description']); ?></p>
                    <?php else: ?>
                        <p>No job details found.</p>
                    <?php endif; ?>

                </div>
            </div>
        <?php endif; ?>

        <!-- Section 3: Applied Jobs List -->
        <h2>Upcoming Applied Jobs</h2>
        <div class="dashboard_section scrollable" id="applied-jobs-section">
            <table class="applied-jobs-list">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Deadline</th>
                        <th>Applied Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_applied_list->num_rows > 0) {
                        while ($row = $result_applied_list->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row['Name']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['CName']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['Deadline']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['Applied_Date']) . '</td>';

                            if (is_null($row['Status'])) {
                                echo '<td><span class="status on-hold">On Hold</span></td>';
                            } elseif ($row['Status'] == 0) {
                                echo '<td><span class="status rejected">Rejected</span></td>';
                            } elseif ($row['Status'] == 1) {
                                echo '<td><span class="status accepted">Accepted</span></td>';
                            }
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="5">You have not applied to any jobs yet.</td></tr>';
                    } ?>
                </tbody>
            </table>
        </div>

        <!-- Section 4: Bookmarks List -->
        <h2>Upcoming Bookmarked Jobs</h2>
        <div class="dashboard_section scrollable" id="bookmarks-section">
            <table class="bookmarked-jobs-list">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Deadline</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_bookmarked_list->num_rows > 0) {
                        while ($row = $result_bookmarked_list->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row['Name']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['Deadline']) . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="2">You have no bookmarked jobs yet.</td></tr>';
                    } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Close popups when clicking outside
        window.onclick = function(event) {
            const modals = ['jobDetailsModal'];
            modals.forEach((id) => {
                const modal = document.getElementById(id);
                if (event.target === modal) {
                    modal.style.display = "none";
                }
                // Remove the hash from the URL
                history.pushState("", document.title, window.location.pathname);
            });
        };

        // Function to open popups when links are clicked
        document.querySelectorAll('a[href^="#"]').forEach((link) => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const modal = document.getElementById(targetId);
                if (modal) {
                    modal.style.display = 'flex';
                }
            });
        });

        // Close popup when close button is clicked
        document.querySelectorAll('.close-btn').forEach((btn) => {
            btn.addEventListener('click', function(event) {
                event.preventDefault();
                const popup = this.closest('.popup');
                if (popup) {
                    popup.style.display = 'none';
                }
                // Remove the hash from the URL
                history.pushState("", document.title, window.location.pathname);
            });
        });
    </script>

    <script>
        document.querySelectorAll('.close-btn').forEach(btn => {
            btn.addEventListener('click', () => history.pushState("", document.title, window.location.pathname));
        });
    </script>

</body>

</html>