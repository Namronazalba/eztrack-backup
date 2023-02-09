<?php
session_start();
include '../connection.php';
if (isset($_GET['id'])) {
$id = $_GET['id'];
        $sql = "SELECT * FROM tbl_cause_of_offline";
        if($result = mysqli_query($dbc, $sql)){
            if(mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_array($result)){
                    $reason_offline_id = $row['reason_for_offline_id'];  
                    $array[] = $reason_offline_id;           
                    }
                // Free result set
                mysqli_free_result($result);
                if(!in_array($id, $array)){
                    $sql = "DELETE FROM tbl_reason_for_offline WHERE reason_id='$id'";
                    if(mysqli_query($dbc,$sql)){
                        $_SESSION['success'] = "Data removed successfully";
                        header("Location: index.php");
                        }else{
                        echo "Content does not exist";
                    }
                } else {
                    $_SESSION['success1'] = "Unable to delete this data is related to reason for offline";
                    header("Location: index.php");
                }
            }
        }

    }
?>