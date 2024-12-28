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

    <?php include 'includes/js_navbar.php'; ?>
    <?php include 'includes/js_sidebar.php'; ?>

    <div class="account-container">
        <div class="profile-section">
            <div class="profile-pic">
                <img src="https://www.w3schools.com/w3images/avatar2.png">
                <h3>Employee1</h3>
                <p>employee1@gmail.com</p>
            </div>
        </div>
        <div class="form-section">
            <h4>Profile Information</h4>
            <form>
                <div class="form-row">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" placeholder="Sam">
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" placeholder="Maxwell">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" placeholder="employee1@gmail.com">
                    </div>

                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="tel" placeholder="+880..">
                    </div>

                </div>



                <div class="form-row">
                    <div class="form-group">
                        <label>Skills</label>
                        <textarea placeholder="e.g., JavaScript, Python, SQL"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Experience</label>
                        <textarea placeholder="e.g., 3 years in Software Development"></textarea>
                    </div>
                </div>


                <div class="form-group">
                    <label>Education</label>
                    <textarea placeholder="e.g., B.Sc. in Computer Science"></textarea>
                </div>

                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date">
                </div>

                <button type="edit">Edit</button>
            </form>
        </div>
    </div>

</body>

</html>