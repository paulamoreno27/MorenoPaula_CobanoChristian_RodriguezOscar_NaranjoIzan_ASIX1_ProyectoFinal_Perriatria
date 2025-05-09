<?php

require_once "config.php";

$conn = mysqli_connect($servername , $username, $password, $dbname);

if (!$conn) {
    echo "<script>alert('Error connecting')</script>";
    die("Connection failed: " . mysqli_connect_error());
} 

?>