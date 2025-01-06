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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <title>Employify</title>
</head>

<body>
    <?php include 'includes/js_navbar.php'; ?>
    <?php include 'includes/js_sidebar.php'; ?>

    <main class="search-container">
        <h2>Search Your Desired Job</h2>
        <form action="" method="get" class="search-form">
            <select name="criteria" class="search-select">
                <option value="all"
                    <?php echo (isset($_GET['criteria']) && $_GET['criteria'] === 'all') ? 'selected' : ''; ?>>All
                </option>
                <option value="name"
                    <?php echo (isset($_GET['criteria']) && $_GET['criteria'] === 'name') ? 'selected' : ''; ?>>Name
                </option>
                <option value="company"
                    <?php echo (isset($_GET['criteria']) && $_GET['criteria'] === 'company') ? 'selected' : ''; ?>>
                    Company</option>
                <option value="field"
                    <?php echo (isset($_GET['criteria']) && $_GET['criteria'] === 'field') ? 'selected' : ''; ?>>Field
                </option>
                <option value="salary"
                    <?php echo (isset($_GET['criteria']) && $_GET['criteria'] === 'salary') ? 'selected' : ''; ?>>Salary
                </option>
            </select>

            <input type="text" name="search"
                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                class="form-control" placeholder="Search for jobs">
            <button type="submit" class="filter-button">Search</button>
        </form>

        <div class="search-card">
            <table class="search-list">
                <thead>
                    <tr>
                        <th>Application ID</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Deadline</th>
                        <th>Field</th>
                        <th>Salary</th>
                        <th>Apply</th>
                        <th>Bookmark</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $username = $_SESSION['username']; // Assuming the user is logged in and username is in session

                    $criteria = isset($_GET['criteria']) ? $_GET['criteria'] : 'all';
                    $search = isset($_GET['search']) && !empty(trim($_GET['search'])) ? trim($_GET['search']) : null;

                    // Prepare query with additional checks for applied and bookmarked jobs
                    $query = "SELECT a.*, r.CName, 
                                    (SELECT COUNT(*) FROM seeker_seeks ss WHERE ss.S_id = ? AND ss.A_id = a.A_id) AS has_applied,
                                    (SELECT COUNT(*) FROM seeker_bookmarks sb WHERE sb.S_id = ? AND sb.A_id = a.A_id) AS is_bookmarked
                                    FROM applications a 
                                    INNER JOIN recruiter r ON a.R_id = r.R_id 
                                    WHERE A.status=1";

                    $params = [$username, $username];
                    $types = "ss";

                    if ($criteria !== 'all' && $search) {
                        switch ($criteria) {
                            case 'name':
                                $query .= " AND a.Name LIKE ?";
                                $params[] = "%$search%";
                                $types .= "s";
                                break;
                            case 'company':
                                $query .= " AND r.CName LIKE ?";
                                $params[] = "%$search%";
                                $types .= "s";
                                break;
                            case 'field':
                                $query .= " AND a.Field LIKE ?";
                                $params[] = "%$search%";
                                $types .= "s";
                                break;
                            case 'salary':
                                $query .= " AND a.Salary >= ?";
                                $params[] = $search;
                                $types .= "i";
                                break;
                        }
                    } else if ($search) {
                        $query .= " AND CONCAT(a.Name, a.Field, a.Salary, a.Description) LIKE ?";
                        $params[] = "%$search%";
                        $types .= "s";
                    }

                    $stmt = $con->prepare($query);
                    $stmt->bind_param($types, ...$params);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    ?>

                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($items = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($items['A_id']); ?></td>
                                <td><?= htmlspecialchars($items['Name']); ?></td>
                                <td><?= htmlspecialchars($items['CName']); ?></td>
                                <td><?= htmlspecialchars($items['Deadline']); ?></td>
                                <td><?= htmlspecialchars($items['Field']); ?></td>
                                <td><?= htmlspecialchars($items['Salary']); ?></td>
                                <!-- Apply Button -->
                                <td>
                                    <?php if ($items['has_applied'] > 0): ?>
                                        <button class="applied-button" disabled>Applied</button>
                                    <?php else: ?>
                                        <a href="js_apply.php?A_id=<?= htmlspecialchars($items['A_id']); ?>&criteria=<?= urlencode($criteria); ?>&search=<?= urlencode($search ?? ''); ?>"
                                            class="search-button" name="apply">Apply</a>

                                    <?php endif; ?>
                                </td>

                                <!-- Bookmark Button -->
                                <td>
                                    <?php if ($items['is_bookmarked'] > 0): ?>
                                        <button class="applied-button" disabled>Bookmarked</button>
                                    <?php else: ?>
                                        <a href="js_bookmark.php?A_id=<?= htmlspecialchars($items['A_id']); ?>&criteria=<?= urlencode($criteria); ?>&search=<?= urlencode($search ?? ''); ?>"
                                            class="search-button" name="bookmark">Bookmark</a>

                                    <?php endif; ?>
                                </td>

                                <td>
                                    <a href="?A_id=<?= $items['A_id']; ?>#jobDetailsPopup" class="view-button">Details</a>
                                </td>


                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9">No Record Found</td>
                        </tr>
                    <?php endif; ?>

                </tbody>

                <?php
                $stmt->close();
                ?>

                </tbody>
            </table>
        </div>

        <!-- Popup Modal for view profile -->
        <?php if (isset($_GET['A_id']) && !empty($_GET['A_id'])): ?>
            <div id="jobDetailsPopup" class="view_job_popup">
                <div class="view_job_popup-content">
                    <a href="#" class="view_job_close-btn">&times;</a>

                    <?php
                    $A_id = intval($_GET['A_id']);
                    $stmt = $con->prepare("SELECT A.*, R.CName FROM applications A 
                                            INNER JOIN recruiter R ON A.R_id = R.R_id 
                                            WHERE A.A_id = ?");
                    $stmt->bind_param("i", $A_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0):
                        $job = $result->fetch_assoc();
                    ?>
                        <div class="view_job_profile-details">
                            <h2>Job Details</h2>
                            <p><strong>ID:</strong> <?= htmlspecialchars($job["A_id"]); ?></p>
                            <p><strong>Name:</strong> <?= htmlspecialchars($job["Name"]); ?></p>
                            <p><strong>Company:</strong> <?= htmlspecialchars($job["CName"]); ?></p>
                            <p><strong>Deadline:</strong> <?= htmlspecialchars($job["Deadline"]); ?></p>
                            <p><strong>Field:</strong> <?= htmlspecialchars($job["Field"]); ?></p>
                            <p><strong>Salary:</strong> <?= htmlspecialchars($job["Salary"]); ?></p>
                            <p><strong>Description:</strong> <?= htmlspecialchars($job["Description"]); ?></p>
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
    </main>


    <script>
        // Close popups when clicking outside
        window.onclick = function(event) {
            const modals = ['jobDetailsPopup'];
            modals.forEach((id) => {
                const modal = document.getElementById(id);
                if (modal && event.target === modal) {
                    modal.style.display = "none";
                    // Remove the hash from the URL
                    history.pushState("", document.title, window.location.pathname);
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
                    // Update the URL with the modal id (hash)
                    history.pushState(null, '', '#' + targetId);
                }
            });
        });

        // Close popup when close button is clicked
        document.querySelectorAll('.view_job_close-btn').forEach((btn) => {
            btn.addEventListener('click', function(event) {
                event.preventDefault();
                const popup = this.closest('.view_job_popup');
                if (popup) {
                    popup.style.display = 'none';
                    // Remove the hash from the URL
                    history.pushState("", document.title, window.location.pathname);
                }
            });
        });
    </script>

    <?php
    if (isset($_GET['message']) && !empty($_GET['message'])) {
        $message = htmlspecialchars($_GET['message']);
        echo "<script> alert('$message'); </script>";
    }
    ?>
</body>

</html>