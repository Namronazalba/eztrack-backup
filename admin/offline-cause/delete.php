<?php
if (isset($_GET['id'])) {
include '../connection.php';
$id = $_GET['id'];
$sql = "DELETE FROM tbl_cause_of_offline WHERE cause_id='$id'";
   if(mysqli_query($dbc,$sql)){
    session_start();
    $_SESSION["delete"] = "Content Deleted Successfully!";
    header("Location: index.php");
   }else{
    die("Something went wrong");
   }
}else{
    echo "Content does not exist";
}