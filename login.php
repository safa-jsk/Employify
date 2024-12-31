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
                            SELECT 'employer' AS role, password FROM recruiter 
                            WHERE Email = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($role, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            session_regenerate_id(true);
            
            if ($role === 'job_seeker'){
                $user_query = "SELECT S_id FROM seeker WHERE Email = ?";
            } elseif ($role === 'employer'){
                $user_query = "SELECT R_id FROM recruiter WHERE Email = ?";
            }

            $stmt_user = $con->prepare($user_query);
            $stmt_user->bind_param("s", $username);
            $stmt_user->execute();
            $stmt_user->store_result();
            $stmt_user->bind_result($user_id);
            $stmt_user->fetch();
            
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