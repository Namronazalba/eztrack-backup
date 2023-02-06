<?php
if(($_SESSION['role'] != "Admin") ) {
    header("Location: admin/index.php");
    exit;
}
?>