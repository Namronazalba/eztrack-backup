<?php
// Include config file
require_once "../connection.php";
 
// Define variables and initialize with empty values
$last_name=$first_name=$address=$contact_no=$gender=$role=$username=$password=$confirm_password ="";
$last_name_err=$first_name_err=$address_err=$contact_no_err=$gender_err=$role_err=$username_err=$password_err=$confirm_password_err="";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate last name
    $input_last_name = trim($_POST["last_name"]);
    if(empty($input_last_name)){
        $last_name_err = "Please enter your last name";     
    } else{
        $last_name = $input_last_name;
    }
    //validate first name
    $input_firstname = trim($_POST['first_name']);
    if(empty($input_firstname)){
      $first_name_err = "First name is required";
    }else{
      $first_name = $input_firstname;
    }
    //validate address
    $input_address = trim($_POST['address']);
    if(empty($input_address)){
      $address_err = "Address is required";
    }else{
      $address = $input_address ;
    }
    //validate contact
    $input_contact = $_POST['contact_no'];
    if(empty($input_contact)){
      $contact_no_err = "Contact number is required";
    }elseif(preg_match("/[a-z]/i", $input_contact)){
      $contact_no_err = "Contact number must be number only";
    }elseif(strlen($_POST["contact_no"]) < 11){
      $contact_no_err = "Contact number must be 11 digits";
    }else{
      $contact_no = $input_contact;
    }
    //validate gender
    $input_gender =  trim($_POST['gender']);
    if(empty($input_gender)){
      $gender_err = "Gender is required";
    }else{
      $gender = $input_gender;
    }
    //validate role 
    $input_role =  trim($_POST['role']);
    if(empty($input_role)){
      $role_err = "Role is required";
    }else{
      $role = $input_role;
    }
    //validate username
    $input_username = $_POST['username'];
    if(empty($input_username)){
      $username_err = "Username is requied";
    }else{
      $username = $input_username;
    }
    // Validate password
    if(empty(trim($_POST["password"]))){
    $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
    $password_err = "Password must have atleast 6 characters.";
    } else{
    $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
    $confirm_password_err = "Please confirm password.";     
    } else{
    $confirm_password = trim($_POST["confirm_password"]);
    if(empty($password_err) && ($password != $confirm_password)){
      $confirm_password_err = "Password did not match.";
    }
    }

    // Check input errors before inserting in database
    if(empty($last_name_err) && empty($first_name_err) && empty($address_err) && empty($contact_no_err) && empty($gender_err) 
    && empty($role_err) && empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        // Prepare an update statement
$sql = "UPDATE tbl_user_technician SET last_name=?, first_name=?, address=?, contact_no=?, gender=?, role=?, username=?, password=? WHERE id=?";
         
        if($stmt = mysqli_prepare($dbc, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssssss",$param_lname,$param_fname,$param_address,$param_contact,
            $gender,$role,$param_username,$param_pass,$param_id);
            
            // Set parameters
            $param_lname = $last_name;
            $param_fname = $first_name;
            $param_address = $address;
            $param_contact = $contact_no;
            $param_gender = $gender;
            $param_role = $role;
            $param_username = $username;
            $param_pass = password_hash($password, PASSWORD_DEFAULT);
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
// Close connection
mysqli_close($dbc);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM tbl_user_technician WHERE id = ?";
        if($stmt = mysqli_prepare($dbc, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $last_name = $row["last_name"];
                    $first_name = $row["first_name"];
                    $address = $row["address"];
                    $contact_no = $row["contact_no"];
                    $gender = $row["gender"];
                    $role = $row["role"];
                    $username = $row["username"];
                    $password = $row["password"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($dbc);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
<!--doctype, head, css dbc, js link-->
<?php include '../layouts/link.php'; ?>

<body>
  <!-- navbar -->
  <?php include '../layouts/navbar.php'; ?>
  <div class="container-fluid">
    <div class="row">
    <!--Sidebar -->
    <?php include 'sidebar.php'; ?>
      <!-- Main -->
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2  border-bottom">
          <h2 class="s-header">EDIT USER</h2>
          <div class="b-mt">
            <a href="index.php" class="btn btn-secondary btn-sm b-width">Back</a>
          </div>
        </div>
        <?php
              if (isset($_GET["id"])) {
                include '../connection.php';
                $id = $_GET["id"];
                $sql1 = "SELECT * FROM tbl_user_technician WHERE id=$id";
                $result = mysqli_query($dbc, $sql1);
                $data = mysqli_fetch_array($result);
              ?>
        <div class="p-2 mt-2 mb-4">
          <div class="card bx-shadow">
            <div class="card-body p-4">
                <form  action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                  <div class="row">
                    <div class="col">
                      <div class="form-group mb-3">
                      <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control <?php echo (empty($last_name)) ? 'is-invalid' : ''; ?>" name="last_name" 
                        value="<?php echo $last_name; ?>" placeholder="Enter last name" autocomplete="off">
                        <span class="" style="color: red;"><?php echo $last_name_err;?></span>
                      </div>
                    </div>
                    <div class="col">
                      <div class="form-group mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control <?php echo (empty($first_name)) ? 'is-invalid' : ''; ?>" name="first_name" 
                        value="<?php echo $first_name; ?>" placeholder="Enter first name" autocomplete="off">
                        <span class="" style="color: red;"><?php echo $first_name_err;?></span>
                      </div>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Address</Address></label>
                    <input type="text" class="form-control <?php echo (empty($address)) ? 'is-invalid' : ''; ?>" name="address" 
                    placeholder="Enter address" value="<?php echo $address; ?>">
                    <span class="" style="color: red;"><?php echo $address_err;?></span>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="form-group mb-3">
                        <label class="form-label">Contact No.</label>
                        <input type="text" class="form-control <?php echo (empty($contact_no)) ? 'is-invalid' : ''; ?>" name="contact_no" 
                        placeholder="Enter contact no." autocomplete="off" value="<?php echo $contact_no ?>" maxlength='11'>
                        <span class="" style="color: red;"><?php echo $contact_no_err;?></span>
                      </div>
                    </div>
                    <div class="col">
                      <div class="form-group">
                        <label class="form-label">Gender</label>
                        <select class="form-select <?php echo (empty($gender)) ? 'is-invalid' : ''; ?>" name="gender" value="<?php echo $gender; ?>"> 
                          <option disabled selected value="">Select Gender</option>
                          <option value="Male"
                          <?php
                            if ($data['gender'] == 'Male') {
                              echo "selected";
                            }
                            ?>>Male</option>
                          <option value="Female"
                          <?php
                            if ($data['gender'] == 'Female') {
                              echo "selected";
                            }
                            ?>>Female</option>
                        </select>
                        <span class="" style="color: red;"><?php echo $gender_err;?></span>
                      </div>
                    </div>
                  </div>
                    <div class="col">
                      <div class="form-group">
                        <label class="form-label">Role</label>
                        <select class="form-select <?php echo (empty($role)) ? 'is-invalid' : ''; ?>" name="role" value="<?php echo $role; ?>">
                          <option disabled selected>Select Role</option>
                          <option value="Admin"
                          <?php
                            if ($data['role'] == 'Admin') {
                              echo "selected";
                            }
                          ?>>Admin</option>
                          <option value="Technician"
                          <?php
                            if ($data['role'] == 'Technician') {
                              echo "selected";
                            }
                          ?>>Technician</option>
                        </select>
                        <span class="" style="color: red;"><?php echo $role_err;?></span>
                      </div>
                    </div>
                    <div class="row mb-3">
                  <div class="col">
                    <div class="form-group">
                      <label class="form-label">Username</label>
                      <input type="text" class="form-control <?php echo (empty($username)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>"
                      name="username" placeholder="Enter username" autocomplete="off">
                      <span class="" style="color: red;"><?php echo $username_err;?></span>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="form-group mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control <?php echo (empty($password)) ? 'is-invalid' : ''; ?>" name="password" 
                        placeholder="Enter password" id="myInput1" autocomplete="off">
                        <span class="" style="color: red;"><?php echo $password_err;?></span>
                      </div>
                    </div>
                    <div class="col">
                      <div class="form-group mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control <?php echo (!empty($confirm_password)) ? 'is-invalid' : ''; ?>" 
                        name="confirm_password" placeholder="Confirm password" id="myInput2" autocomplete="off">
                        <span class="" style="color: red;"><?php echo $confirm_password_err;?></span>
                      </div>
                    </div>
                  </div>

              </div>
                  <div class="form-check">
                    <input type="checkbox" onclick="myFunction()">
                    <label class="form-check-label">Show Password</label>
                  </div>
                  <div class="d-flex justify-content-center">
                    <button class="btn btn-success w-50 mt-2" type="submit" name="update" ><span>Update</span></button>
                  </div>
                         <?php } ?>
                </form>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</body>
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

</html>