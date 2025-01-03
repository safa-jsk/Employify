<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once 'DBconnect.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'job_seeker') {
    header("Location: e_account.php");
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
    <link rel="stylesheet" type="text/css" href="./css/bootstrap-icons.css">
    <title>Dashboard</title>
</head>

<body>
    <!-- <form method="POST" action="includes/update_profile.php"> -->

    <?php include 'includes/js_navbar.php'; ?>
    <?php include 'includes/js_sidebar.php'; ?>
    <?php include 'js_fetch.php'; ?>
    <?php include 'js_change_password.php'; ?>

    <div class="account-container">
        <div class="profile-section">
            <div class="profile-pic">
                <!-- Display Role based on the session role -->
                <?php 
                if (isset($_SESSION['role']) && $_SESSION['role'] == 'job_seeker') {
                    echo '<h4>Job Seeker</h4>';
                } else {
                    echo '<h4>No Role Assigned</h4>';
                }
                ?>
                <!-- Display Gender-Specific Avatar -->
                <?php
                $avatar_url = "https://www.w3schools.com/w3images/avatar3.png"; // Default avatar
                if (isset($job_recruiter['Gender'])) {
                    if (strtolower($job_recruiter['Gender']) == 1) {
                        $avatar_url = "https://www.w3schools.com/w3images/avatar2.png"; // Male avatar
                    } elseif (strtolower($job_recruiter['Gender']) == 0) {
                        $avatar_url = "https://www.w3schools.com/w3images/avatar6.png"; // Female avatar
                    }
                }
                ?>

                <img src="<?php echo $avatar_url; ?>" alt="Profile Picture">
                <h3><?php echo htmlspecialchars($job_seeker['FName'] . ' ' . $job_seeker['LName']); ?></h3>
                <p><?php echo htmlspecialchars($job_seeker['Email'] ?? ''); ?></p>
            </div>
        </div>
        <div class="form-section">
            <h4>Profile Information</h4>
            <form method="POST" action="js_update_profile.php">
                <div class="form-row">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="fname"
                            value="<?php echo htmlspecialchars($job_seeker['FName'] ?? ''); ?>"
                            placeholder="First Name">
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="lname"
                            value="<?php echo htmlspecialchars($job_seeker['LName'] ?? ''); ?>" placeholder="Last Name">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email"
                            value="<?php echo htmlspecialchars($job_seeker['Email'] ?? ''); ?>" placeholder="Email">
                    </div>

                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="tel" name="contact"
                            value="<?php echo htmlspecialchars($job_seeker['Contact'] ?? ''); ?>" placeholder="+880..">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Skills</label>
                        <textarea name="skills"
                            placeholder="e.g., JavaScript, Python, SQL"><?php echo htmlspecialchars($job_seeker['Skills'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Experience</label>
                        <textarea name="experience"
                            placeholder="e.g., 3 years in Software Development"><?php echo htmlspecialchars($job_seeker['Experience'] ?? ''); ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label>Education</label>
                    <textarea name="education"
                        placeholder="e.g., B.Sc. in Computer Science"><?php echo htmlspecialchars($job_seeker['Education'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" value="<?php echo htmlspecialchars($job_seeker['DoB'] ?? ''); ?>">
                </div>



                <button class="edit-button" type="submit" name="js_edit">Save Changes</button>
                <br>

            <!-- Change Password Button -->
            <button  class="edit-button-changepass" type="button" id="changePasswordBtn">Change Password</button>
            </form>
        </div>

    <!-- Change Password Popup -->
    <div id="changePasswordPopup" class="popup">
        <div class="popup-content">
            <a href="#" class="close-btn">&times;</a>
            <h4>Change Password</h4>
            <form method="POST" action="js_change_password.php">
                <input type="password" name="old_password" placeholder="Current Password" required>
                <input type="password" name="new_password" placeholder="New Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
                <button type="submit" name="change_password">Update Password</button>
            </form>
        </div>
    </div>

    <script>
        // Open Change Password Popup
        document.getElementById('changePasswordBtn').addEventListener('click', function() {
            document.getElementById('changePasswordPopup').style.display = 'flex';
        });

        // Close popups when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('changePasswordPopup');
            if (event.target === modal) {
                modal.style.display = "none";
            }
        };

        // Close popup when close button is clicked
        document.querySelectorAll('.close-btn').forEach((btn) => {
            btn.addEventListener('click', function(event) {
                event.preventDefault();
                const popup = this.closest('.popup');
                if (popup) {
                    popup.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>