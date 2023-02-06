<?php
if (isset($_GET['id'])) {
include '../connection.php';
$id = $_GET['id'];
$sql = "DELETE FROM tbl_user_technician WHERE id='$id'";
   if(mysqli_query($dbc,$sql)){
    session_start();
    $_SESSION["delete"] = "User Deleted Successfully!";
    header("Location: index.php");
   }else{
    die("Something went wrong");
   }
}else{
    echo "User does not exist";
}