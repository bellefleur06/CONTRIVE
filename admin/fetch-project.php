<?php

include('../connections/config.php');

if (isset($_POST['get_option'])) {
    $project_id = $_POST['get_option'];
    $row1 = array();
    $result = mysqli_query($conn, "SELECT * FROM staffs, clients, projects WHERE staffs.id = projects.engineer_id AND projects.client_name = clients.name AND projects.id = $project_id");

    while ($row = mysqli_fetch_array($result)) {
        $row1[] = $row;
    }
    die(json_encode($row1));
}
