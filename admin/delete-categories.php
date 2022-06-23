<?php

include('../connections/config.php');

//check if user is logged in
if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

//check if page is not forcefully accessed
if (!isset($_GET['ID'])) {

    $_SESSION['category-not-found'] = "Category Not Found.";
    header('Location: manage-categories.php');
} else {

    $id = $_GET['ID'];

    $sql = "SELECT * FROM categories WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);

    //check if category exist
    if ($count > 0) {

        while ($row = mysqli_fetch_assoc($result)) {
            $name = $row['name'];
        }
    }

    $activity = "Delete Category Record of " . $name;

    $sql = "DELETE FROM categories WHERE id = $id";
    $result = mysqli_query($conn, $sql);

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

        $_SESSION['delete-categories'] = "Category Removed Successfully!";
        header("Location: manage-categories.php");
    } else {

        $_SESSION['failed-to-delete'] = "Failed to Remove Category.";
        header("Location: manage-categories.php");
    }
}
