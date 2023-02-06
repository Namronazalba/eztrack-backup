<?php
// database connection
include '../connection.php';

// process for creating user
if (isset($_POST['create'])) {
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $address = $_POST['address'];
    $contact_no = $_POST['contact_no'];
    $gender =  $_POST['gender'];
    $role =  $_POST['role'];
    $username = $_POST['username'];
    $password_1 = $_POST['password_1'];
    $password_2 = $_POST['password_2'];
    $options = array("cost" => 4);
    $hashPassword = password_hash($password_1, PASSWORD_BCRYPT, $options);

    $sql_insert = "INSERT INTO tbl_user_technician (last_name, first_name, address, contact_no, gender, role, username, password)
                   VALUES ('$last_name', '$first_name', '$address', '$contact_no', '$gender', '$role', '$username', '$hashPassword')";

    $result = mysqli_query($dbc, $sql_insert);

    if ($result) {
        session_start();
        $_SESSION["create"] = "User Added Successfully!";
        header("Location: index.php");

    }else{
        die("Something went wrong" . mysqli_connect_error());
    }
}

// process for updating user
if (isset($_POST['update'])) {

    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $address = $_POST['address'];
    $contact_no = $_POST['contact_no'];
    $gender =  $_POST['gender'];
    $role =  $_POST['role'];
    $username = $_POST['username'];
    $password_1 = $_POST['password_1'];
    $password_2 = $_POST['password_2'];

    $id =  $_POST['id'];
    $options = array("cost" => 4);
    $hashPassword = password_hash($password_1, PASSWORD_BCRYPT, $options);

    $sql_update = "UPDATE tbl_user_technician
                   SET last_name = '$last_name',
                       first_name = '$first_name',
                       address = '$address',
                       contact_no = '$contact_no',
                       gender = '$gender',
                       role = '$role',
                       username = '$username',
                       password = '$hashPassword'
                   WHERE id='$id'";

    $result = mysqli_query($dbc, $sql_update);

    if ($result) {
        session_start();
        $_SESSION["update"] = "User Updated Successfully!";
        header("Location: index.php");
    } else {
        die("Something went wrong" . mysqli_connect_error());
    }
}