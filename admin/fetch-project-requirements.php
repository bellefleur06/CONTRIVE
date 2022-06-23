<?php

include('../connections/config.php');

if ($_REQUEST['id']) {
    $sql = "SELECT * projects, requirements WHERE requirements.project_id = '" . $_REQUEST['id'] . "'";
    $result = mysqli_query($conn, $sql);
    $reqData = array();

    while ($req = mysqli_fetch_assoc($result)) {
        $reqData = $req;
    }
    echo json_encode($reqData);
} else {

    echo 0;
}
