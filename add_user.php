<?php include('user_action.php') ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/create-login.css">
    <link rel="stylesheet" href="css/add_user.css">
    <link rel="stylesheet" href="fontawesome/css/all.min.css"/>
  </head>
  <body>
    <div class="container">
      <div class="wrapper">
        <div class="title"><i class='fas fa-user-plus'></i></div>
            <form method="post" action="add_user.php">
            <?php include('include_errors/errors.php'); ?>
                <div class="row">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" value="<?php echo $username; ?>" placeholder="Input username" autocomplete="off">
                    <?php include('include_errors/error1.php'); ?>
                </div>
                <div class="row">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password_1" value="<?php echo $password_1; ?>" placeholder="Input password" id="myInput1" autocomplete="off">
                    <?php include('include_errors/error3.php'); ?>
                </div>
                <div class="row">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password_2" value="<?php echo $password_2; ?>" placeholder="Confirm password" id="myInput2" autocomplete="off">
                    <?php include('include_errors/error4.php'); ?>
                </div>
                  <div class="checkbox"><input type="checkbox" onclick="myFunction()">  Show Password</div>
                <div class="row ">
                    <button class="submit" type="submit" name="reg_user"><span>Add user </span></button>
                </div>
            </form>
      </div>
    </div>
    <script>
      function myFunction() {
        var x = document.getElementById("myInput1");
        var y = document.getElementById("myInput2");
        if (x.type === "password" && y.type === "password") {
          x.type = "text";
          y.type = "text";
        } else {
          x.type = "password";
          y.type = "password";
        }
      }
    </script>
  </body>
</html>
