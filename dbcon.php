<?php



$hostname = 'localhost';
$username = 'root';
$dbpass = '';
$dbname = "customer_support";

$conn = mysqli_connect($hostname, $username, $dbpass, $dbname) or die("Database connection failed");
?>