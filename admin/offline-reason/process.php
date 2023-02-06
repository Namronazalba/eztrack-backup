<?php
include '../connection.php';
// process for creating
if (isset($_POST['create'])) {

    $content = $_POST['content'];

    $sql_insert = "INSERT INTO tbl_reason_for_offline (reason_for_offline_name) VALUES ('$content')";

    $result = mysqli_query($dbc, $sql_insert);

    if ($result) {
        session_start();
        $_SESSION["create"] = "Content Added Successfully!";
        header("Location: index.php");

    }else{
        die("Something went wrong" . mysqli_connect_error());
    }
}

// process for updating
if (isset($_POST['update'])) {

    $content = $_POST['content'];
    $id =  $_POST['id'];
    $sql_update = "UPDATE tbl_reason_for_offline SET reason_for_offline_name = '$content'  WHERE reason_id='$id'";

    $result = mysqli_query($dbc, $sql_update);

    if ($result) {
        session_start();
        $_SESSION["update"] = "Content Updated Successfully!";
        header("Location: index.php");

    }else{
        die("Something went wrong" . mysqli_connect_error());
    }
}