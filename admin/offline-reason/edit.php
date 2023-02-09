<?php
session_start();
// Include config file
require_once '../connection.php';
 
// Define variables and initialize with empty values
$offline_reason = "";
$offline_reason_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $input_offline_reason = trim($_POST["offline_reason"]);
    if(empty($input_offline_reason)){
        $offline_reason_err = "This field must required.";
    }else{
        $offline_reason = $input_offline_reason;
    }
    
    
    // Check input errors before inserting in database
    if(empty($offline_reason_err)){
        // Prepare an update statement
        $sql = "UPDATE tbl_reason_for_offline SET reason_for_offline_name=? WHERE reason_id=?";
         
        if($stmt = mysqli_prepare($dbc, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_offline_reason, $param_id);
            
            // Set parameters
            $param_offline_reason = $offline_reason;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                $_SESSION['success'] = "Updated successfully";
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
        $sql = "SELECT * FROM tbl_reason_for_offline WHERE reason_id = ?";
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
                    $offline_reason = $row["reason_for_offline_name"];
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
<!--doctype, head, css link, js link-->
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
                    <h2 class="s-header">EDIT ACTION TAKEN</h2>
                    <div class="d-flex justify-content-center">
                        <a href="index.php" class="btn btn-secondary btn-sm b-width b-mt">Back</a>
                    </div>
                </div>
                <div class="p-2 mt-3 mb-4">
                    <div class="card bx-shadow">
                        <div class="card-body p-4">
                            <form method="POST" action="edit.php">

                                    <div class="form-group mb-3">
                                        <label class="form-label">Offline reason</label>
                                        <input type="text" name="offline_reason" value="<?php echo $offline_reason ?>" class="form-control <?php echo (!empty($offline_reason_err)) ? 'is-invalid' : ''; ?>" placeholder="Enter Content">
                                        <span class="invalid-feedback"><?php echo $offline_reason_err;?></span>
                                    </div>
                                    <input type="hidden" value="<?php echo $id; ?>" name="id">
                                    <div class="d-flex justify-content-center">
                                        <input type="submit" value="Update" name="update" class="btn btn-success w-50" onclick="return confirm('Are you sure you want to save changes?');">
                                    </div>
              
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>