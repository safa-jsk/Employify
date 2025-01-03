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

$username = $_SESSION['username'];

// Fetch all jobs posted by the recruiter for the filter dropdown
$query_jobs = "SELECT A_id, Name FROM applications WHERE R_id = ?";
$stmt_jobs = $con->prepare($query_jobs);
$stmt_jobs->bind_param("s", $username);
$stmt_jobs->execute();
$result_jobs = $stmt_jobs->get_result();
$stmt_jobs->close();

// Fetch shortlisted candidates with optional filters
$query_shortlisted_candidates = "
    SELECT sc.S_id, sc.A_id, a.Name AS Job_Name, a.Field, a.Deadline, 
           CONCAT(s.FName, ' ', s.LName) AS Seeker_Name, s.Email,
           ss.Status 
    FROM recruiter_shortlist sc
    INNER JOIN applications a ON sc.A_id = a.A_id
    INNER JOIN seeker s ON sc.S_id = s.S_id
    LEFT JOIN seeker_seeks ss ON sc.A_id = ss.A_id AND sc.S_id = ss.S_id
    WHERE sc.R_id = ?";

$parameters = [$username];
$types = "s";

// Apply filters for job and search term
if (isset($_GET['job_id']) && !empty($_GET['job_id'])) {
    $job_id = $_GET['job_id'];
    $query_shortlisted_candidates .= " AND sc.A_id = ?";
    $parameters[] = $job_id;
    $types .= "s";
}

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = "%" . $_GET['search'] . "%";
    $query_shortlisted_candidates .= " AND (CONCAT(s.FName, ' ', s.LName) LIKE ? OR a.Name LIKE ?)";
    $parameters[] = $search_term;
    $parameters[] = $search_term;
    $types .= "ss";
}

$query_shortlisted_candidates .= " ORDER BY s.FName ASC";

$stmt_shortlisted_candidates = $con->prepare($query_shortlisted_candidates);
$stmt_shortlisted_candidates->bind_param($types, ...$parameters);
$stmt_shortlisted_candidates->execute();
$result_shortlisted_candidates = $stmt_shortlisted_candidates->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Shortlisted Candidates</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include 'includes/e_navbar.php'; ?>
    <?php include 'includes/e_sidebar.php'; ?>

    <div class="dashboard_content">
        <h2>Shortlisted Candidates</h2>
        <form action="" method="get" class="search-form">
            <select name="job_id" class="search-select">
                <option value="" selected>All Jobs</option>
                <?php while ($job = $result_jobs->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($job['A_id']); ?>"
                        <?= isset($_GET['job_id']) && $_GET['job_id'] == $job['A_id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($job['Name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <input type="text" name="search" placeholder="Search for candidates"
                value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" class="filter-button">Filter</button>
        </form>

        <?php if ($result_shortlisted_candidates->num_rows > 0): ?>
            <table class="shortlisted-candidates-list">
                <thead>
                    <tr>
                        <th>Job ID</th>
                        <th>Job Name</th>
                        <th>Field</th>
                        <th>Candidate Name</th>
                        <th>Email</th>
                        <th>Deadline</th>
                        <th>Profile</th>
                        <th>Accept</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_shortlisted_candidates->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['A_id']); ?></td>
                            <td><?= htmlspecialchars($row['Job_Name']); ?></td>
                            <td><?= htmlspecialchars($row['Field']); ?></td>
                            <td><?= htmlspecialchars($row['Seeker_Name']); ?></td>
                            <td><?= htmlspecialchars($row['Email']); ?></td>
                            <td><?= htmlspecialchars($row['Deadline']); ?></td>
                            <td><a href="?S_id=<?= $row['S_id']; ?>#profile-popup" class="view-button">View Profile</a></td>
                            <td>
                                <?php if ($row['Status'] == "1"): ?>
                                    <button class="applied-button" disabled>Accepted</button>
                                <?php else: ?>
                                    <a href="e_accept.php?A_id=<?= $row['A_id']; ?>&S_id=<?= $row['S_id']; ?>"
                                        class="status accepted">Accept</a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="e_shortlist_remove.php?A_id=<?= $row['A_id']; ?>&S_id=<?= $row['S_id']; ?>"
                                    class="status rejected">Remove</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No shortlisted candidates found.</p>
        <?php endif; ?>
    </div>

    <!-- Popup Modal for view profile -->
    <?php if (isset($_GET['S_id']) && !empty($_GET['S_id'])): ?>
        <div id="profile-popup" class="popup">
            <div class="popup-content">
                <a href="#" class="close-btn">&times;</a>
                <div class="profile-pic">
                    <?php
                    // Default avatar
                    $avatar_url = "https://www.w3schools.com/w3images/avatar3.png";

                    // Fetch seeker profile details
                    $seeker_id = $_GET['S_id'];
                    $stmt = $con->prepare("
                    SELECT FName, LName, Gender, Email, Experience, Education, Skills, Contact 
                    FROM seeker 
                    WHERE S_id = ?
                ");
                    $stmt->bind_param("s", $seeker_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0):
                        $seeker = $result->fetch_assoc();

                        // Set avatar based on gender
                        if ($seeker['Gender'] == 1) {
                            $avatar_url = "https://www.w3schools.com/w3images/avatar2.png"; // Male avatar
                        } elseif ($seeker['Gender'] == 0) {
                            $avatar_url = "https://www.w3schools.com/w3images/avatar6.png"; // Female avatar
                        }
                    ?>
                        <img src="<?= htmlspecialchars($avatar_url); ?>" alt="Profile Picture">
                </div>

                <div class="profile-details">
                    <p><strong>Name:</strong> <?= htmlspecialchars($seeker['FName'] . ' ' . $seeker['LName']); ?></p>
                    <p><strong>Gender:</strong> <?= ($seeker['Gender'] == 1) ? 'Male' : 'Female'; ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($seeker['Email']); ?></p>
                    <p><strong>Experience:</strong> <?= htmlspecialchars($seeker['Experience']); ?> years</p>
                    <p><strong>Education:</strong> <?= htmlspecialchars($seeker['Education']); ?></p>
                    <p><strong>Skills:</strong> <?= htmlspecialchars($seeker['Skills']); ?></p>
                    <p><strong>Contact:</strong> <?= htmlspecialchars($seeker['Contact']); ?></p>
                </div>
            <?php else: ?>
                <p>Profile not found.</p>
            <?php
                    endif;
                    $stmt->close();
            ?>
            </div>
        </div>
    <?php endif; ?>


    <script>
        // Close popups when clicking outside
        window.onclick = function(event) {
            const modals = ['profile-popup'];
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