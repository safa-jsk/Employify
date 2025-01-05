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

// Fetch jobs posted by the logged-in user
$query_posted_jobs = "SELECT * FROM applications WHERE R_id = ?";
$stmt_posted_jobs = $con->prepare($query_posted_jobs);
$stmt_posted_jobs->bind_param("s", $username);
$stmt_posted_jobs->execute();
$result_posted_jobs = $stmt_posted_jobs->get_result();
$stmt_posted_jobs->close();

// Handle job updating via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_job'])) {
    $A_id = $_POST['A_id'];
    $name = $_POST['Name'];
    $field = $_POST['Field'];
    $deadline = $_POST['Deadline'];
    $salary = $_POST['Salary'];
    $description = $_POST['Description'];
    $status = isset($_POST['Status']) ? 1 : 0;

    $update_query = "UPDATE applications 
                        SET Name = ?, Field = ?, Deadline = ?, Salary = ?, Description = ?, Status = ? WHERE A_id = ?";
    $stmt_update = $con->prepare($update_query);
    $stmt_update->bind_param("sssssss", $name, $field, $deadline, $salary, $description, $status, $A_id);
    $stmt_update->execute();
    $stmt_update->close();

    $_SESSION['msg'] = "Job updated successfully!";
    header("Location: e_posted-jobs.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <title>Posted Jobs</title>
</head>

<body>
    <?php include 'includes/e_navbar.php'; ?>
    <?php include 'includes/e_sidebar.php'; ?>

    <div class="dashboard_content">
        <h2>Posted Jobs</h2>

        <!-- Display Jobs List -->
        <table class="shortlisted-candidates-list">
            <thead>
                <tr>
                    <th>Job ID</th>
                    <th>Job Name</th>
                    <th>Field</th>
                    <th>Posted Date</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Salary</th>
                    <th>Description</th>
                    <th>Total Applicants</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_posted_jobs->fetch_assoc()): ?>
                    <?php
                    $query_total_applicants = "SELECT COUNT(*) AS total_applicants FROM seeker_seeks 
                                                WHERE A_id = ?";
                    $stmt_total_applicants = $con->prepare($query_total_applicants);
                    $stmt_total_applicants->bind_param("s", $row['A_id']);
                    $stmt_total_applicants->execute();
                    $result_total_applicants = $stmt_total_applicants->get_result();
                    $total_applicants = $result_total_applicants->fetch_assoc()['total_applicants'];
                    $stmt_total_applicants->close();
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['A_id']); ?></td>
                        <td><?= htmlspecialchars($row['Name']); ?></td>
                        <td><?= htmlspecialchars($row['Field']); ?></td>
                        <td><?= htmlspecialchars($row['Posted_Date']); ?></td>
                        <td><?= htmlspecialchars($row['Deadline']); ?></td>
                        <td>
                            <?php if (is_null($row['Status'])): ?>
                                <span class="status on-hold">On Hold</span>
                            <?php elseif ($row['Status'] == 0): ?>
                                <span class="status rejected">Inactive</span>
                            <?php else: ?>
                                <span class="status accepted">Active</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars(number_format($row['Salary'])); ?> USD</td>
                        <td><?= htmlspecialchars($row['Description']); ?></td>
                        <td><?= htmlspecialchars($total_applicants); ?></td>
                        <td>
                            <button class="status accepted edit-job-btn" data-id="<?= $row['A_id']; ?>"
                                data-name="<?= htmlspecialchars($row['Name']); ?>"
                                data-field="<?= htmlspecialchars($row['Field']); ?>"
                                data-deadline="<?= htmlspecialchars($row['Deadline']); ?>"
                                data-salary="<?= htmlspecialchars($row['Salary']); ?>"
                                data-description="<?= htmlspecialchars($row['Description']); ?>"
                                data-status="<?= $row['Status']; ?>">
                                Edit
                            </button>
                        </td>
                        <td><a href="e_remove_job.php?A_id=<?= $row['A_id'] ?>" class="status rejected">Delete</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Edit Job Popup -->
    <div id="editJobPopup" class="popup">
        <div class="popup-content">
            <a href="#" class="close-btn">&times;</a>
            <h4>Edit Job</h4>
            <form method="POST" action="e_posted-jobs.php">
                <input type="hidden" id="jobId" name="A_id" />
                <input type="text" id="jobName" name="Name" placeholder="Job Name" required />
                <input type="text" id="jobField" name="Field" placeholder="Field" required />
                <input type="date" id="jobDeadline" name="Deadline" required />
                <input type="number" id="jobSalary" name="Salary" placeholder="Salary" required />
                <textarea id="jobDescription" name="Description" placeholder="Description" rows="4" required></textarea>
                <div>
                    <label class="status-label">
                        <input type="checkbox" id="jobStatus" name="Status" class="form-check-input" />
                        <p class="status-text">Active Status</p>
                    </label>
                </div>

                <button type="submit" name="update_job" class="search-button">Update Job</button>
                <br>

            </form>

        </div>
    </div>

    <script>
        // Open Edit Job Popup and populate fields
        document.querySelectorAll('.edit-job-btn').forEach(button => {
            button.addEventListener('click', function() {
                const popup = document.getElementById('editJobPopup');
                document.getElementById('jobId').value = this.dataset.id;
                document.getElementById('jobName').value = this.dataset.name;
                document.getElementById('jobField').value = this.dataset.field;
                document.getElementById('jobDeadline').value = this.dataset.deadline;
                document.getElementById('jobSalary').value = this.dataset.salary;
                document.getElementById('jobDescription').value = this.dataset.description;
                document.getElementById('jobStatus').checked = this.dataset.status == "1";
                popup.style.display = 'flex';
            });
        });


        // Close popups when clicking outside
        window.onclick = function(event) {
            const popup = document.getElementById('editJobPopup');
            if (event.target === popup) {
                popup.style.display = "none";
            }
        };

        // Close popup when close button is clicked
        document.querySelector('.close-btn').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('editJobPopup').style.display = 'none';
        });
    </script>

    <?php
    if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])) {
        $msg = htmlspecialchars($_SESSION['msg']);
        echo "<script> alert('$msg'); </script>";
        unset($_SESSION['msg']);
    }
    ?>
</body>

</html>