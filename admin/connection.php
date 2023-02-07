<?php
   $db_host = 'localhost';
   $db_username = 'root';
   $db_password = '';
   $db_name = "db_ezpht";

   $dbc = mysqli_connect($db_host, $db_username, $db_password, $db_name);
   if(!$dbc){
      die('Connection Failed:' . mysqli_connect_error());
   }
?>