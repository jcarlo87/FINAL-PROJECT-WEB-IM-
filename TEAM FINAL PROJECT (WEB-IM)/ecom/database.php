<?php
//use to connect to the database
$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "dbecommerce";
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
if (!$conn) {
    die("Something went wrong;");
}

?>