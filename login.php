<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
 
// Include config file
require_once "conection.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password, role 
                    FROM tbl_user_technician 
                    WHERE role in ('Admin','Technician') 
                    AND username = ?";
        
        if($stmt = mysqli_prepare($dbc, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password,$role);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password) && $role == "Admin"){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;          
                            $_SESSION['user_id'] = $row['id'];
                            $_SESSION['username'] = $username;
                            $_SESSION['role'] = "Admin";
                            header("location: admin/index.php");                            
                         } elseif (password_verify($password, $hashed_password) && $role == "Technician"){   
                            // Password is correct, so start a new session
                            session_start();                         
                             // Store data in session variables
                             $_SESSION["loggedin"] = true;
                             $_SESSION['user_id'] = $row['id'];
                             $_SESSION['username'] = $username;
                             $_SESSION['role'] = "Technician";
                             header("location: index.php");
                        }else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($dbc);
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/create-login.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="fontawesome/css/all.min.css"/>
  </head>
  <style>
    #error-message-container {
      position: fixed;
      top: 2%;
      right: 2%;
      width: 350px;
      display: flex;
      flex-direction: column;   
    }
    .message {
      padding: 0.8rem 1.5rem;
      margin: 0.5rem;
      width: 300px;
      border-radius: 10px;
      text-align: center;
      box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.7);
      color: white;
      animation: fade-in 3s ease;
      font-weight: 500;
      line-height: 1.3rem;      
    }
    .error {
      background-color: #B22222;
    }
    .fade-out {
      animation: fade-out 1s ease 1s forwards;
    }
    @keyframes fade-in {
      0% {
        opacity: 0;
      }
      100% {
        opacity: 1;
      }
    }
    @keyframes fade-out {
      0% {
        opacity: 1;
      }
      100% {
        opacity: 0;
      }
    }
    body {
      background: rgb(9,78,172);
background: linear-gradient(90deg, rgba(9,78,172,0.21612394957983194) 0%, rgba(121,9,11,0.2189250700280112) 98%);
    }
    .wrapper .title{
      height: 25px;
      background: white;
      border-radius: 5px 5px 0 0;
      color: #fff;
      font-size: 30px;
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 15px;
    }
    form .row input:focus{
    border-color: #B22222;
    box-shadow: inset 0px 0px 2px 2px rgba(255, 0, 0, 0.25);
    }
    .wrapper{
    width: 100%;
    background: #fff;
    border-radius: 5px;
    box-shadow: 0 19px 38px rgba(0,0,0,0.30), 0 15px 12px rgba(0,0,0,0.22);
    margin-top: -30px;
  }
  </style>
  <body>
    <div class="container">
      <div class="wrapper">
        <div class="title"><img src="uploads/eztech.png" height="150" width="200" style="margin-bottom: 35px;" alt=""></div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <div class="row" style="margin-top: 15px">
            <i style="background-color:#B22222" class="fas fa-user"></i>
            <input class="<?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" style="font-size: 20px" type="text" placeholder="Username" name="username" value="<?php echo $username; ?>"  autocomplete="off">
            <span class="invalid-feedback"><?php echo $username_err; ?></span>
          </div>
          <div class="row" style="margin-top: -10px;">
            <i style="background-color:#B22222" class="fas fa-lock"></i>
            <input class="<?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" style="font-size: 20px" type="password"  placeholder="Password" name="password" id="myInput1" autocomplete="off">
            <span class="invalid-feedback"><?php echo $password_err; ?></span>
          </div>
          <div style="margin-top: -10px" class="checkbox"><input type="checkbox" onclick="myFunction()"> Show Password</div>
          <div class="row">
              <button style="background-color: #B22222" id="failure-btn" class="submit" type="submit" name="login_user"><span>Login </span></button>
          </div>
        </form>
      </div>
    </div>
  </body>
  <script>
      function myFunction() {
        var x = document.getElementById("myInput1");
        if (x.type === "password") {
          x.type = "text";
        } else {
          x.type = "password";
        }
      }
  </script>
</html>
