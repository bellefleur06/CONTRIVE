<?php

include('../connections/config.php');

//check if user is logged in
if (!isset($_SESSION['username'])) {

	header('Location: ../index.php');
}

//check if page is not forcefully accessed
$id = $_POST['delete_id'];

$sql = "SELECT * FROM requirements WHERE id = '$id'";
$result = mysqli_query($conn, $sql);
$count = mysqli_num_rows($result);

//check if material exist
if ($count > 0) {

	while ($row = mysqli_fetch_assoc($result)) {
		$project_name = $row['project'];
		$material_name = $row['name'];
	}
}

$material_id = $row['material_id'];
$quantity = $row['quantity'];

$sql = "SELECT * FROM materials WHERE id = '$material_id'";
$result = mysqli_query($conn, $sql);
$count = mysqli_num_rows($result);

//check if material exist
if ($count > 0) {

	while ($row = mysqli_fetch_assoc($result)) {
		$id = $row['id'];
		$stocks = $row['stocks'];
	}
}

$stocks = $row['stocks'];

$total_stocks = $quantity + $stocks;
$activity = "Removed Project Requirement For " . $project_name . " - " . $material_name;

$sql = "DELETE FROM requirements WHERE id = '$id'";
$result = mysqli_query($conn, $sql);

//check if delete process is true
if ($result == TRUE) {

	$sql = "UPDATE materials SET stocks = $total_stocks WHERE id = $material_id";
	$result = mysqli_query($conn, $sql);

	//update materials stock
	if ($result = TRUE) {
	} else {
		echo "<script>alert('Error in Updating Stocks')</script>";
	}

	$sql = "UPDATE staffs SET last_activity = now() WHERE id = '{$_SESSION['id']}'";
	$result = mysqli_query($conn, $sql);

	//update last activity date and time
	if ($result = TRUE) {
	} else {
		echo "<script>alert('Error in Updating Last Activity')</script>";
	}

	$sql = "INSERT INTO logs SET username = '{$_SESSION['username']}' , log_time = now(), activity = '$activity'";
	$result = mysqli_query($conn, $sql);

	//insert info into audit trail
	if ($result = TRUE) {
	} else {
		echo "<script>alert('Error in Recording Logs')</script>";
	}
}
