<?php
$server = "localhost";
$user = "root";
$pass = "";
$db = "employify";

$con = mysqli_connect($server, $user, $pass, $db);
if (!$con) {
    die("Error" . mysqli_connect_error());
}
?>
