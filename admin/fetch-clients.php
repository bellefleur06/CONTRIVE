<?php

include('../connections/config.php');

if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

if (isset($_POST['client_id'])) {

    $id = $_POST['client_id'];

    $sql =  "SELECT * FROM clients WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);

    echo json_encode($row);
}
