<?php
if(($_SESSION['role'] != "Technician") ) {
    header("Location: index2.php");
    exit;
}
?>