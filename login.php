<!-- popup login page -->
<?php
session_start();

require_once 'DBconnect.php'; // use $con

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare and check user
    $stmt = $con->prepare("SELECT 'job_seeker' AS role, password FROM seeker
                            WHERE Email = ?
                            UNION
                            SELECT 'employer' AS role, password FROM recruiter WHERE Email = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($role, $hashed_password);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            session_regenerate_id(true);

            $user_query = "SELECT S_id FROM seeker WHERE Email = '$username'";
            $user_id = mysqli_query($con, $user_query);
            $user_id = mysqli_fetch_assoc($user_id)['S_id'];
            $_SESSION['username'] = $user_id;
            $_SESSION['role'] = $role;

            if ($role === 'job_seeker') {
                header("Location: js_dashboard.php");
            } elseif ($role === 'employer') {
                header("Location: e_dashboard.php");
            } else {
                echo "<script>alert('Unknown role. Please contact admin.'); window.location.href='index.php';</script>";
            }
            exit();
        } else {
            echo "<script>alert('Invalid Password'); window.location.href='index.php';</script>";
        }
    } else {
        echo "<script>alert('User not found'); window.location.href='index.php';</script>";
    }

    $stmt->close();
    $con->close();
} else {
    header("Location: index.php");
    exit();
}
?>