<?php

include('../connections/config.php');

if (isset($_POST['get_option'])) {
    $position_id = $_POST['get_option'];
    $row1 = array();
    $result = mysqli_query($conn, "SELECT * FROM positions WHERE id = $position_id");

    while ($row = mysqli_fetch_array($result)) {
        $row1[] = $row;
    }
    die(json_encode($row1));
}
