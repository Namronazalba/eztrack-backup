<?php
// Include config file
require_once '../connection.php';
//Query reason for offline
$reason_for_offline = "SELECT * FROM tbl_reason_for_offline";
$reason_for_offline_query = mysqli_query($dbc, $reason_for_offline);
 
// Define variables and initialize with empty values
$offline_reason = $offline_cause = "";
$offline_reason_err = $offline_cause_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $offline_cause = mysqli_real_escape_string($dbc, $_POST['offline_cause']);
    
    // Validate offline reason
    $input_offline_reason = isset($_POST["select_offline_reason"]);
    if(empty($input_offline_reason)){
        $offline_reason_err = "Please select offline reason.";
    } else{
        $offline_reason = $input_offline_reason;
    }
    
    //Validate offline cause
    $input_offline_cause = trim($_POST["offline_cause"]);
    if(empty($input_offline_cause)){
        $offline_cause_err = "Offline cause is required.";     
    } else{
        $offline_cause = $input_offline_cause;
    }
    
    // Check input errors before inserting in database
    if(empty($offline_reason_err) && empty($offline_cause_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO tbl_cause_of_offline (reason_for_offline_id, cause_of_offline_name) VALUES (?,?)";
         
        if($stmt = mysqli_prepare($dbc, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_offline_reason,$param_offline_cause);
            
            // Set parameters
            $param_offline_reason = $offline_reason;
            $param_offline_cause = $offline_cause;
            
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
                    <h2 class="s-header">NEW OFFLINE CAUSE</h2>
                    <div class="d-flex justify-content-center">
                        <a href="index.php" class="btn btn-secondary btn-sm b-mt b-width">Back</a>
                    </div>
                </div>
                <div class="p-2 mt-2 mb-4">
                    <div class="card bx-shadow">
                        <div class="card-body p-4">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <div class="form-group mb-3">
                                    <label class="form-label">Offline Reason</label>
                                    <select class="form-select <?php echo (!empty($offline_reason_err)) ? 'is-invalid' : ''; ?>" name="select_offline_reason">
                                        <option selected disabled value="">Select offline reason</option>
                                        <?php while ($data = mysqli_fetch_assoc($reason_for_offline_query)) : ?>
                                            <option value="<?php echo $data['reason_id'] ?>"> <?php echo $data['reason_for_offline_name']; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                    <span class="invalid-feedback"><?php echo $offline_reason_err;?></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Offline Cause</label>
                                    <input type="text" class="form-control <?php echo (!empty($offline_cause_err)) ? 'is-invalid' : ''; ?>" name="offline_cause" 
                                    placeholder="Enter Content" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" value="<?php echo $offline_cause; ?>">
                                    <span class="invalid-feedback"><?php echo $offline_cause_err;?></span>
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