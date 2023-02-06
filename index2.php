<?php 
include('session.php');
include('session_admin.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Hello Admin</h1>
    <li class="nav-item">
        <a class="nav-link fs-4" href="logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
    </li>
</body>
</html>