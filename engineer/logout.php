<?php

include('../connections/config.php');

$sql = "UPDATE staffs SET last_logout = now() WHERE id = '{$_SESSION['id']}'";
$result = mysqli_query($conn, $sql);

//update last logout date and time
if ($result = TRUE) {
} else {
    echo "<script>alert('Error in Recording Logs')</script>";
}

session_unset();
session_destroy();

header('Location: ../index.php');
