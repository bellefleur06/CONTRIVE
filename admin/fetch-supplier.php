<?php

include('../connections/config.php');

$output = '';

$sql = "SELECT * FROM suppliers WHERE category_id = '" . $_POST['categoryId'] . "'";
$result = mysqli_query($conn, $sql);

$output = '<option disabled selected>-- Select Supplier --</option>';

while ($row = mysqli_fetch_array($result)) {
    $output .= '<option value="' . $row["name"] . '">' . $row["name"] . '</option>';
}

echo $output;
