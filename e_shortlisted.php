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

// Fetch shortlisted candidates
$query_shortlisted_candidates = "
    SELECT sc.S_id, sc.A_id, a.Name AS Job_Name, a.Field, a.Deadline, CONCAT(s.FName, ' ', s.LName) AS Seeker_Name, s.Email
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
if (!$stmt_shortlisted_candidates) {
    die("Error in query preparation: " . $con->error);
}

$stmt_shortlisted_candidates->bind_param($types, ...$parameters);
$stmt_shortlisted_candidates->execute();
$result_shortlisted_candidates = $stmt_shortlisted_candidates->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
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
                    <th>Job ID</th>
                    <th>Job Name</th>
                    <th>Field</th>
                    <th>Candidate Name</th>
                    <th>Email</th>
                    <th>Deadline</th>
                    <th>Profile</th>
                    <th>Accept</th>
                    <th>Reject</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_shortlisted_candidates->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['A_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['Job_Name']); ?></td>
                    <td><?php echo htmlspecialchars($row['Field']); ?></td>
                    <td><?php echo htmlspecialchars($row['Seeker_Name']); ?></td>
                    <td><?php echo htmlspecialchars($row['Email']); ?></td>
                    <td><?php echo htmlspecialchars($row['Deadline']); ?></td>
                    <td><a href="?S_id=<?php echo $row['S_id']; ?>#profile-popup" class="view-button">View Profile</a>
                    </td>
                    <td><a href="e_accept.php?A_id=<?php echo $row['A_id']; ?>&S_id=<?php echo $row['S_id']; ?>"
                            class="accept-button">Accept</a></td>
                    <td><a href="e_shortlist_reject.php?A_id=<?php echo $row['A_id']; ?>&S_id=<?php echo $row['S_id']; ?>"
                            class="remove-button">Reject</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
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
                    <th>Reject</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_shortlisted_candidates->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['A_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['Job_Name']); ?></td>
                    <td><?php echo htmlspecialchars($row['Field']); ?></td>
                    <td><?php echo htmlspecialchars($row['Seeker_Name']); ?></td>
                    <td><?php echo htmlspecialchars($row['Email']); ?></td>
                    <td><?php echo htmlspecialchars($row['Deadline']); ?></td>
                    <td><a href="?S_id=<?php echo $row['S_id']; ?>#profile-popup" class="view-button">View Profile</a>
                    </td>
                    <?php if ($row['Status'] === 'accepted'): ?>
                    <td><button class="applied-button" disabled>Accepted</button></td>
                    <?php else: ?>
                    <td><a href="e_accept.php?A_id=<?php echo $row['A_id']; ?>&S_id=<?php echo $row['S_id']; ?>"
                            class="accept-button">Accept</a></td>
                    <?php endif; ?>
                    <td><a href="e_shortlist_reject.php?A_id=<?php echo $row['A_id']; ?>&S_id=<?php echo $row['S_id']; ?>"
                            class="remove-button">Reject</a></td>
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
            <?php
                $seeker_id = $_GET['S_id'];
                $stmt = $con->prepare("SELECT FName, LName, Gender, Email, Experience, Education, Skills, Contact 
                                   FROM seeker 
                                   WHERE S_id = ?");
                $stmt->bind_param("s", $seeker_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $seeker = $result->fetch_assoc();
                    echo "<p><strong>Name:</strong> " . htmlspecialchars($seeker['FName'] . ' ' . $seeker['LName']) . "</p>";
                    echo "<p><strong>Gender:</strong> " . ($seeker['Gender'] == 1 ? 'Male' : 'Female') . "</p>";
                    echo "<p><strong>Email:</strong> " . htmlspecialchars($seeker['Email']) . "</p>";
                    echo "<p><strong>Experience:</strong> " . htmlspecialchars($seeker['Experience']) . " years</p>";
                    echo "<p><strong>Education:</strong> " . htmlspecialchars($seeker['Education']) . "</p>";
                    echo "<p><strong>Skills:</strong> " . htmlspecialchars($seeker['Skills']) . "</p>";
                    echo "<p><strong>Contact:</strong> " . htmlspecialchars($seeker['Contact']) . "</p>";
                } else {
                    echo "<p>Profile not found.</p>";
                }

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


</body>

</html>