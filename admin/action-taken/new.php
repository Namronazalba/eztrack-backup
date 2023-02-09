<?php
// Include connection file
include '../connection.php';
//Query user table
$query = "SELECT * FROM tbl_user_technician";
$result = mysqli_query($dbc, $query);
if (!$result) {
    die('Query Failed' . mysqli_connect_error());
}
// Define variables and initialize with empty values
$action_taken= "";
$action_taken_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate action taken
    $input_action_taken = trim($_POST["action_taken"]);
    if(empty($input_action_taken)){
        $action_taken_err = "Action taken is required";     
    } else{
        $action_taken = $input_action_taken;
    }

    // Check input errors before inserting in database
    if(empty($action_taken_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO tbl_action_taken (action_taken_name) VALUES (?)";
         
        if($stmt = mysqli_prepare($dbc, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_action_taken);
            
            // Set parameters
            $param_action_taken = $action_taken;
            
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

<!--doctype, head, css link, js link & title -->
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
                    <h2 class="s-header">NEW ACTION TAKEN</h2>
                    <div class="b-mt">
                        <a href="index.php" class="btn btn-secondary btn-sm btn-sm b-width">Back</a>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="card bx-shadow">
                        <div class="card-body p-4">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <div class="form-group mb-3">
                                    <label class="form-label"><b>Action Taken</b></label>
                                    <input type="text" name="action_taken" class="form-control <?php echo (!empty($action_taken_err)) ? 'is-invalid' : ''; ?>" 
                                    placeholder="Enter Content" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                    <span class="invalid-feedback"><?php echo $action_taken_err;?></span>
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