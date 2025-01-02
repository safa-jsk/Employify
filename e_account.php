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

    <?php include 'includes/e_navbar.php'; ?>
    <?php include 'includes/e_sidebar.php'; ?>
    <?php include 'e_fetch.php'; ?>

    <div class="account-container">
        <div class="profile-section">
            <div class="profile-pic">
                <img src="https://www.w3schools.com/w3images/avatar3.png">
                <h3><?php echo htmlspecialchars($job_recruiter['FName'] . ' ' . $job_recruiter['LName']); ?></h3>
                <p><?php echo htmlspecialchars($job_recruiter['Email'] ?? ''); ?></p>
            </div>
        </div>
        <div class="form-section">
            <h4>Profile Information</h4>
            <form method="POST" action="e_update_profile.php">
                <div class="form-row">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="fname"
                            value="<?php echo htmlspecialchars($job_recruiter['FName'] ?? ''); ?>"
                            placeholder="First Name">
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="lname"
                            value="<?php echo htmlspecialchars($job_recruiter['LName'] ?? ''); ?>" placeholder="Last Name">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email"
                            value="<?php echo htmlspecialchars($job_recruiter['Email'] ?? ''); ?>" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="tel" name="contact"
                            value="<?php echo htmlspecialchars($job_recruiter['Contact'] ?? ''); ?>" placeholder="+880..">
                    </div>
                </div>

                <div class="form-group">
                    <label>Company Name</label>
                    <input type="text" name="cname"
                            value="<?php echo htmlspecialchars($job_recruiter['CName'] ?? ''); ?>" placeholder="Company Name">
                </div>

                <div class="form-group">
                    <label>Company Description</label>
                    <textarea name="company_description"
                        placeholder="Company info..."><?php echo htmlspecialchars($job_recruiter['CDescription'] ?? ''); ?></textarea>
                </div>


                <button class="option-button" type="submit" name="e_edit">Save Changes</button>
            </form>
        </div>

</body>

</html>