<?php

include('../connections/config.php');

//check if user is logged in
if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

//check if page is not forcefully accessed
if (!isset($_POST['ID'])) {

    $_SESSION['staff-not-found'] = "Staff Not Found.";
    header("Location: manage-staffs.php");
} else {

    $id = $_POST['ID'];

    $sql = "SELECT * FROM staffs WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);

    //check if material exist
    if ($count > 0) {

        while ($row = mysqli_fetch_assoc($result)) {
            $last_name = $row['last_name'];
            $first_name = $row['first_name'];
            $access = $row['access'];
        }
    }

    $profile = $_POST['profile'];
    $activity = "Delete " . $access . " - " . $first_name . " " . $last_name;

    //check if delete button is clicked
    if (isset($_POST['delete'])) {

        $sql = "DELETE FROM staffs WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);

        unlink("../staff_images/$profile");

        //check if delete process is true
        if ($result == TRUE) {

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

            $_SESSION['delete-staffs'] = "Staff Removed Successfully!";
            header("Location: manage-staffs.php");
        } else {

            $_SESSION['failed-to-delete'] = "Failed to Remove Staff.";
            header("Location: manage-staffs.php");
        }
    }
}
