<?php

include('../connections/config.php');

if (isset($_POST['get_option'])) {
    $project_id = $_POST['get_option'];
    $row1 = array();
    $result = mysqli_query($conn, "SELECT * FROM clients WHERE id = $project_id");

    while ($row = mysqli_fetch_array($result)) {
        $row1[] = $row;
    }
    die(json_encode($row1));
}
