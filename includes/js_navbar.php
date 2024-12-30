<?php
$server = "localhost";
$user = "root";
$pass = "";
$db = "employify";

$con = mysqli_connect($server, $user, $pass, $db);
if (!$con) {
    die("Error" . mysqli_connect_error());
}

session_start();

$username = $_SESSION['username'];

?>

<header class="navbar">
    <div class="logo">
        <h1><a href="index.php"><img src="images/logo.png"></a></h1>
    </div>
    <div class="nav-links">
        <a href="js_search.php">Find a Job</a>
        <div class="dropdown">
            <button class="dropbtn"><?php echo htmlspecialchars($username); ?> ▾</button>
            <div class="dropdown-content">
                <a href="js_account.php">Account Details</a>
                <a href="index.php">Logout</a>
            </div>
        </div>
    </div>
    </nav>
</header>