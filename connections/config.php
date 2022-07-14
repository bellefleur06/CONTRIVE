<?php

// $server = "localhost";
// $user = "root";
// $pass = "";
// $database = "contrive_database";

$server = "sql113.epizy.com";
$user = "epiz_32159037";
$pass = "USn3p3xFOtKH72k";
$database = "epiz_32159037_contrive_database";

$conn = mysqli_connect($server, $user, $pass, $database);

//check if db connection string is correct
if (!$conn) {
    echo "<script>alert('Connection Failed.')</script>";
}

session_start();