<?php
session_start();

// Allow access to index.php without redirecting based on the session
if (basename($_SERVER['PHP_SELF']) !== 'index.php') {
    if (isset($_SESSION['username']) && isset($_SESSION['role'])) {
        // Redirect based on the role
        if ($_SESSION['role'] === 'job_seeker') {
            header("Location: js_dashboard.php"); // Job seeker dashboard
            exit();
        } elseif ($_SESSION['role'] === 'employer') {
            header("Location: e_dashboard.php"); // Employer dashboard
            exit();
        } else {
            echo "<script>alert('Unknown role. Please contact admin.');</script>";
        }
    }
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
    <!-- <div class="background"></div> -->

    <!-- Header -->
    <header class="navbar">
        <div class="logo">
            <h1><a href="index.php"><img src="images/logo.png" alt="Employify Logo"></a></h1>
        </div>
        <nav>
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#" class="contact-btn">Contact</a></li>
                <?php if (isset($_SESSION['username']) && isset($_SESSION['role'])): ?>
                    <?php if ($_SESSION['role'] === 'job_seeker'): ?>
                        <li><a href="js_dashboard.php">Login</a></li>
                    <?php elseif ($_SESSION['role'] === 'employer'): ?>
                        <li><a href="e_dashboard.php">Login</a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li><a href="#loginModal">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>


    <!-- Main Section -->
    <main class="index-container">
        <div class="body-section" id="home">
            <h1>DON'T MISS THE JOB OF YOUR DREAMS!</h1>
            <p>Your Next Step Starts Here—For Dream Jobs and Star Employees.<br><strong>Just one click away!</strong>
            </p>
            <div class="account-selection">
                <a href="#registerJsPopup" class="option-button">Find a Job</a>
                <a href="#registerEmployerPopup" class="option-button">Find a Candidate</a>
            </div>
        </div>
    </main>

    <!-- Login Popup -->
    <div id="loginModal" class="popup">
        <div class="popup-content">
            <a href="#" class="close-btn">&times;</a>
            <h4>Login</h4>
            <form action="login.php" method="POST">
                <input type="email" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
                <div class="password-links">
                    <a href="#forgotPasswordPopup">Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Forgot Password Popup -->
    <div id="forgotPasswordPopup" class="popup">
        <div class="popup-content">
            <a href="#" class="close-btn">&times;</a>
            <h4>Reset Password</h4>
            <form action="forgot_password.php" method="POST">
                <input type="email" name="email" placeholder="Enter your Email" required>
                <input type="password" name="new_password" placeholder="New Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <input type="date" name="dob" placeholder="Date of Birth" required>
                <button type="submit" name="reset_password">Reset Password</button>
            </form>
        </div>
    </div>


    <!-- Job Seeker Registration Popup -->
    <div id="registerJsPopup" class="popup">
        <div class="popup-content">
            <a href="#" class="close-btn">&times;</a>
            <h4>Register</h4>
            <form action="js_register.php" method="POST">
                <input type="text" name="s_id" placeholder="Username" required>
                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="last_name" placeholder="Last Name" required>
                <select name="gender" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                <input type="date" name="dob" placeholder="Date of Birth" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="text" name="skills" placeholder="Skills">
                <input type="text" name="experience" placeholder="Experience">
                <input type="text" name="education" placeholder="Education">
                <button type="submit" name="register_js">Register</button>
            </form>
        </div>
    </div>

    <!-- Employer Registration Popup -->
    <div id="registerEmployerPopup" class="popup">
        <div class="popup-content">
            <a href="#" class="close-btn">&times;</a>
            <h4>Register</h4>
            <form action="e_register.php" method="POST">
                <input type="text" name="r_id" placeholder="Username" required>
                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="last_name" placeholder="Last Name" required>
                <select name="gender" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                <input type="date" name="dob" placeholder="Date of Birth" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="text" name="company_name" placeholder="Company Name" required>
                <input type="text" name="company_description" placeholder="Company Description">
                <input type="text" name="contact_number" placeholder="Contact Number" required>
                <button type="submit" name="e_register">Register</button>
            </form>
        </div>
    </div>

    <script>
        // Close popups when clicking outside
        window.onclick = function(event) {
            const modals = ['loginModal', 'registerJsPopup', 'registerEmployerPopup'];
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
            });
        });
    </script>

    <!-- Features Section -->
    <section class="features-section" id="services">
        <div class="feature-container">
            <div class="feature-optn">
                <img src="images/search-job.png">
                <h3>Search Millions of Jobs</h3>
            </div>
            <div class="feature-optn">
                <img src="images\manage.png">
                <h3>Easy to Manage Jobs</h3>
            </div>
            <div class="feature-optn">
                <img src="images/top-career.png">
                <h3>Top Careers</h3>
            </div>
            <div class="feature-optn">
                <img src="images/expert-cand.png">
                <h3>Search Expert Candidates</h3>
            </div>

        </div>
    </section>
</body>

</html>