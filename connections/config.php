<?php

$server = "localhost";
$user = "root";
$pass = "";
$database = "contrive_database";

$conn = mysqli_connect($server, $user, $pass, $database);

//check if db connection string is correct
if (!$conn) {
    echo "<script>alert('Connection Failed.')</script>";
}

session_start();