<?php
session_start();
// initializing variables
$id = '';
$username = "";
$password_1 = "";
$password_2 = "";
$errors = array(); 
$error1 = array(); 
$error2 = array(); 
$error3 = array(); 
$error4 = array(); 
// connect to the database
require_once("conection.php");

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $username = mysqli_real_escape_string($dbc, $_POST['username']);
  $password_1 = mysqli_real_escape_string($dbc, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($dbc, $_POST['password_2']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) { 
    array_push($error1, "Username is required"); 
    }else{
         if (empty($password_1)) { 
          array_push($error3, "Password is required"); 
          } else {
                  if ( ! preg_match("/[a-z]/i", $_POST["password_1"])) {
                  array_push($error3, "Must contain at least one letter");
                  } else {
                      if ( ! preg_match("/[0-9]/", $_POST["password_1"])) {
                      array_push($error3, "Must contain at least one number");
                      } else {
                         if (empty($_POST["password_2"])) {
                         array_push($error4, "Password confirmation is required");
                         } else {
                                if ($password_1 != $password_2) {
                                array_push($error4, "Password don't match");
                                }
                          }
                    }
              }
        }      
  }

        
  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM tbl_user_technician WHERE username='$username' LIMIT 1";
  $result = mysqli_query($dbc, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { //check if username exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }
  }
  // Merge all possible errors
  $check_error = array_merge($errors, $error1,$error2,$error3,$error4);
  // Finally, register user if there are no errors in the form
  if (count($check_error) == 0) {
		$username = $_POST['username'];
		$password = $_POST['password_1'];
    //encrypt the password before saving in the database
    $options = array("cost"=>4);
		$hashPassword = password_hash($password_1,PASSWORD_BCRYPT,$options);
    		
		$sql = "insert into tbl_user_technician (username, password) value( '".$username."', '".$hashPassword."')";
		$result = mysqli_query($dbc, $sql);
    if($result) {
      $_SESSION['success'] = "Registration successfully";
      header('location: index.php');
		}
  }
}           
?>