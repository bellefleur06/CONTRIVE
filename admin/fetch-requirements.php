<?php

include('../connections/config.php');

if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

if (isset($_POST['requirementId'])) {

    $sql =  "SELECT * FROM requirements WHERE id = '" . $_POST['requirementId'] . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);

    echo json_encode($row);
}
