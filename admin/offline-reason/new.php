<?php
// Include config file
require_once '../connection.php';
// Define variables and initialize with empty values
$offline_reason = "";
$offline_reason_err = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate offline reason
    $input_offline_reason = trim($_POST["offline_reason"]);
    if(empty($input_offline_reason)){
        $offline_reason_err = "Please enter a offline reason.";
    } else{
        $offline_reason = $input_offline_reason;
    }
    
    // Check input errors before inserting in database
    if(empty($offline_reason_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO tbl_reason_for_offline (reason_for_offline_name) VALUES (?)";
         
        if($stmt = mysqli_prepare($dbc, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_offline_reason);
            
            // Set parameters
            $param_offline_reason = $offline_reason;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
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
                    <h2 class="s-header">NEW OFFLINE REASON</h2>
                    <div class="d-flex justify-content-center">
                        <a href="index.php" class="btn btn-secondary btn-sm b-width b-mt">Back</a>
                    </div>
                </div>
                <div class="p-2 mt-3 mb-4">
                    <div class="card bx-shadow">
                        <div class="card-body p-4">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <div class="form-group mb-3">
                                    <label class="form-label">Offline Reason</label>
                                    <input type="text" name="offline_reason" class="form-control <?php echo (!empty($offline_reason_err)) ? 'is-invalid' : ''; ?>" placeholder="Enter Content">
                                    <span class="invalid-feedback"><?php echo $offline_reason_err;?></span>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <input type="submit" value="Create" name="create" class="btn btn-success w-50">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>