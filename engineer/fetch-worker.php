<?php

include('../connections/config.php');

$output = '';

$position_id =  $_POST['positionId'];

$sql = "SELECT * FROM workers WHERE position_id = '$position_id' AND status = 'Active' AND assigned = 'No'";
$result = mysqli_query($conn, $sql);

$output = '<option disabled selected>-- Select Member --</option>';

while ($row = mysqli_fetch_array($result)) {
    $output .= '<option value="' . $row["id"] . '">' . $row["first_name"] . " " . $row["last_name"] . '</option>';
}

echo $output;
