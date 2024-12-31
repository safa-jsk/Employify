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

    <div class="account-container">
        <div class="profile-section">
            <div class="profile-pic">
                <img src="https://www.w3schools.com/w3images/avatar2.png">
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

                <button class="edit-btn" type="submit" name="edit">Save Changes</button>
            </form>
        </div>


    </div>

</body>

</html>