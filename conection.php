<?php

define ('host', 'localhost');
define ('user', 'root');
define ('password', ''); 
define ('dbase', 'db_ezpht');

$dbc = @mysqli_connect(host, user, password, dbase) or die('Could not connect to database' .mysqli_connect_error());

?>