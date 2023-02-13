<?php
// Connect to the database
require_once("conection.php");
session_start();

// Include('traveling_action.php');
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// SESSION VARIABLE USER ID AND USERNAME
$user_id = $_SESSION['user_id'];
$user_technician_name = $_SESSION['username'];

// initializing variables
$date_start = $departure_time = "";
$date_start_err = $departure_time_err = "";

// Get VID of Vehicle
if (isset($_GET['vid'])) {
    $vid = $_GET['vid'];
}

if (isset($_GET['account_id'])) {
    $account_id = $_GET['account_id'];
}


if (isset($_SESSION['data_id']) && isset($_SESSION['user_id']) && isset($_GET['vid'])) {
    $data_id = $_SESSION['data_id'];
    $user_id = $_SESSION['user_id'];
    $vid = $_GET['vid'];
} else {
    $data_id = '';
    $user_id = '';
    $vid = '';
}

// Add new record
if (isset($_POST['submit'])) {

    // Validate start date
    $input_date_start = ($_POST['date_start']);
    if(empty($input_date_start)){
        $date_start_err = "Start date is required!";
    }else{
        $date_start = $input_date_start;
    }

    // Validate departure time
    $input_departure_time = ($_POST['departure_time']);
    if(empty($input_departure_time)){
        $departure_time_err = "Departure time is required!";
    }else{
        $departure_time = $input_departure_time;
    }
    // Add input if there are no errors in the form
    if (empty($date_start_err) && empty($departure_time_err)) {
        $vid = $_POST['vid'];
        $date_start = $_POST['date_start'];
        $departure_time = $_POST['departure_time'];
        $user_id = $_POST['user_id'];
        $sql = "insert into tbl_trblesht_report (date_performed, departure_time, vid, technician, technician_id, date_submitted) value( '" . $date_start . "', '" . $departure_time . "', '" . $vid . "', '" . $user_technician_name . "', '" . $user_id . "', now())";
        $result = mysqli_query($dbc, $sql);
        header('location: isolation.php?account_id='.$account_id.'&vid='.$vid);

        // SELECT LAST RECORD FOR OF REPORT AND GET ID
        $sql_record = "SELECT t1.* FROM tbl_trblesht_report t1
        WHERE t1.id = (SELECT MAX(t2.id)
        FROM tbl_trblesht_report t2
        WHERE technician_id = '$user_id' AND vid = '$vid')";

        $result1 = mysqli_query($dbc, $sql_record);
        $data1 = mysqli_fetch_array($result1);
        $_SESSION['data_id'] = $data1['id'];
    }
}


// SELECT LAST RECORD FOR OF REPORT AND CHECK IF EXISTING
$sql = "SELECT t1.*
FROM tbl_trblesht_report t1
WHERE t1.id = (SELECT MAX(t2.id)
FROM tbl_trblesht_report t2
WHERE id = '$data_id' AND technician_id = '$user_id' AND vid = '$vid')";

$result = mysqli_query($dbc, $sql);
$total = mysqli_num_rows($result);
$data = mysqli_fetch_array($result);

if (isset($_GET['vid'])){
    $vid = $_GET['vid'];
    $query_detail = "SELECT * FROM tbl_cvehicles WHERE vid = $vid";
    $result = mysqli_query($dbc, $query_detail);
    $datas = mysqli_fetch_array($result);
}
?>
<!doctype html>
<html lang="en">
<!-- head -->
<?php include 'head.php'; ?>
<body class="p-3 m-0 border-0 bd-example bgcolor">
    <!-- navbar -->
    <?php include 'navbar.php'; ?>
    <!-- content -->
    <div class="travelling-desktop-container container">
    <p class="fs-5 text-black mt-4">Service Task / <span class="text-secondary">Travelling</span></p>
        <div class="card" style="box-shadow: 0px 3px 11px rgba(0, 0, 0, 0.25);">
            <div class="card-header text-white text-center" style="background: #7280A5; border-radius: 5px 5px 20px 20px;">
                <p class="mt-2">Plate No:</p>
                <h2 class="card-text mb-2" style="margin-top: -20px; font-style: italic; font-size: 38px"><?= $datas['vplatenum']?></h2>
            </div>
            <div class="card-body">
                <!-- form -->
                <form method="post" action="">
                    <div class="form-group mb-3">
                        <label for="date_start" class="form-label">Start Date: *</label>
                        <input type="date" id="date_start" name="date_start" class="form-control <?php echo (!empty($date_start_err)) ? 'is-invalid' : ''; ?>">
                        <span class="invalid-feedback"><?php echo $date_start_err;?></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="departure_time" class="form-label">Departure Time: *</label>
                        <input type="time" id="departure_time" name="departure_time" class="form-control <?php echo (!empty($departure_time_err)) ? 'is-invalid' : ''; ?>">
                        <span class="invalid-feedback"><?php echo $departure_time_err;?></span>
                        <input type="hidden" name="vid" value="<?= (isset($_GET['vid'])) ? $_GET['vid'] : '' ?>">
                        <input type="hidden" name="user_id" value="<?= (isset($_SESSION['user_id'])) ? $_SESSION['user_id'] : '' ?>">
                    </div>
                    <button class="btn btn-success btn mt-2" type="Save" name="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <?php include 'mobile_pages/travelling.php' ?>
</body>
</html>