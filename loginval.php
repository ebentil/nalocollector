<?php
session_start();
require_once 'core/connection.php';
$user = $_POST['username'];
$pass = $_POST['pass'];


$execute = mysqli_query($con, "select * from users where username='$user' and password='$pass'");
if (mysqli_num_rows($execute) > 0) {
	$_SESSION['agent'] = $user;
	echo "yes";
} else {
	echo "no";
}
?>
