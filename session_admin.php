<?php
if(($_SESSION['role'] != "Admin") ) {
    header("Location: index2.php");
    exit;
}
?>