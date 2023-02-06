<?php
// Connect to the database
require_once("conection.php");
if (isset($_GET['vid'])){
    $account_id = $_GET['account_id'];
    $vid = $_GET['vid'];
    $query_detail = "SELECT * FROM tbl_cvehicles WHERE vid = $vid";
    $result = mysqli_query($dbc, $query_detail);
    $data = mysqli_fetch_array($result);
}
?>
<!doctype html>
<html lang="en">
<body class="p-3 m-0 border-0 bd-example bgcolor">
    <!-- navbar -->
    <?php include 'navbar.php'; ?>
    <!-- content -->
    <div class="container">
    <p class="fs-5 text-black mt-4">Service Task / <span class="text-secondary">Isolation</span></p>
        <div class="card" style="box-shadow: 0px 3px 11px rgba(0, 0, 0, 0.25);">
            <div class="card-header text-white text-center" style="background: #7280A5; border-radius: 5px 5px 20px 20px;">
            <p style="font-size:14px; margin-bottom: 17px" class="mt-2 fw-bold text-white">PLATE NUMBER</p>
                <h2 class="card-text mb-2" style="margin-top: -20px; font-style: italic; font-size: 38px"><?= $data['vplatenum']?></h2>
            </div>
            <?php
            include('session.php');
            require_once("conection.php");

            // Get VID of Vehicle
            if (isset($_GET['vid'])) {
                $vid = $_GET['vid'];
            }

            // SESSION USER ID AND USERNAME
            $user_id = $_SESSION['user_id'];
            $user_technician_name = $_SESSION['username'];

            // get id from troubleshoot report //
            $getId = "SELECT * FROM tbl_trblesht_report t1
             WHERE t1.id = (SELECT MAX(t2.id)
             FROM tbl_trblesht_report t2
             WHERE technician_id = '$user_id' AND vid = '$vid')";
            $result = mysqli_query($dbc, $getId);
            $data = mysqli_fetch_array($result);
            $id = $data['id'];
            $departure_time = strtotime($data['departure_time']);

            // initializing variables
            $arrival_time = $work_start = $reason_offline = $cause_offline_select = $address = "";
            $arrival_time_err = $work_start_err = $reason_offline_err = $cause_offline_err = $address_err = "";
            $cause_offline_data = "";


            if (isset($_POST['submit'])) {
                // Validate arrival time
                $input_arrival_time = $_POST['arrival_time'];
                if(empty($input_arrival_time)){
                    $arrival_time_err = "Arrival time is required!";
                }else{
                    $arrival_time = $input_arrival_time;
                }   
                $arrival_time_convert = strtotime($arrival_time);
                // Absolute value of time difference in seconds
                $diff = abs($arrival_time_convert - $departure_time);

                // Convert $diff to minutes

                $tmins = $diff / 60;
                // Get hours
                $traveling_time_hrs = floor($tmins / 60);

                // Get minutes
                $traveling_time_mins = $tmins % 60;

                // Get traveling time
                $traveling_time = "HH:" . $traveling_time_hrs . " " . "MM:" . $traveling_time_mins;

                // Validate  work time start
                $input_work_start = $_POST['work_start'];
                if(empty($input_work_start)){
                    $work_start_err = "Work time start required!";
                }else{
                    $work_start = $input_work_start;
                }

                // Validate reason offline
                $input_reason_offline = (isset($_POST['reason_offline']));
                if(empty($input_reason_offline)){
                    $reason_offline_err = "Reason for offline is required!";
                }else{
                    $reason_offline = $_POST['reason_offline'];
                    // Get only the name in reason offline input
                    $reason_offline = substr($reason_offline, 1, 90);
                }

                // Get only the name in cause offline input
                // Validate cause offline
                $input_cause_offline = (isset($_POST['cause_offline']));
                if(empty($input_cause_offline)){
                    $cause_offline_err = "Cause of offline required!";
                }else{
                    $cause_offline_select = $input_cause_offline;
                    $cause_offline_data = $_POST['cause_offline'];
                }
                $string = preg_match_all("/[0-9]/", $cause_offline_data);
                if ($string == 1) {
                    $cause_offline = substr($cause_offline_data, 1, 90);
                    $get_cause_offline_id = substr($cause_offline_data, 0, 1);
                    $_SESSION['cause_offline_id'] = $get_cause_offline_id;
                } else {
                    $cause_offline = substr($cause_offline_data, 2, 90);
                    $get_cause_offline_id = substr($cause_offline_data, 0, 2);
                    $_SESSION['cause_offline_id'] = $get_cause_offline_id;
                }

                // Validate address
                $input_address = ($_POST['address']);
                if(empty($input_address)){
                    $address_err = "Address is required!";
                }else{
                    $address = $input_address;
                }

                if ($cause_offline == "Standby/No trip (Intentionally disconnect/remove of vehicle battery)") {
                    $vstats = 1;
                    mysqli_query($dbc, "UPDATE tbl_cvehicles SET vstatus='$vstats' where vid = $vid");
                    // Breakdown
                } elseif ($cause_offline == "Breakdown/Repair (Intentionally disconnect/remove of vehicle battery)") {
                    $vstats = 2;
                    mysqli_query($dbc, "UPDATE tbl_cvehicles SET vstatus='$vstats' where vid = $vid");
                } else {
                    $vstats = 0;
                    mysqli_query($dbc, "UPDATE tbl_cvehicles SET vstatus='$vstats',troubleshoot_sched = '' where vid = $vid");
                }

                // UPDATE DOLE ACTION TABLE
                $sqltrbl = "INSERT INTO tbl_doleaction(vid, checked_by, datime_checked, troubleshoot_id, vstatus)
                VALUES	('$vid','','','$id','$vstats')";

                if (!mysqli_query($dbc, $sqltrbl)) {
                    die('Error: ' . mysqli_connect_error());
                }

                // UPDATE TROUBLESHOOT REPORT
                $update_data = 'UPDATE tbl_trblesht_report
                SET timeof_arrival ="' . $arrival_time . '",
                traveling_time = "' . $traveling_time . '", vehicle_status ="' . $vstats . '",
                work_time_start ="' . $work_start . '", reason_offline="' . $reason_offline . '",
                cause_offline ="' . $cause_offline . '", complete_address="' . $address . '", date_submitted = NOW() WHERE id ="' . $id . '"';
                mysqli_query($dbc, $update_data);

                // QUERY LAST RECORD OF CURRENT USER
                $sql_record = "SELECT t1.* FROM tbl_trblesht_report t1
                WHERE t1.id = (SELECT MAX(t2.id)
                FROM tbl_trblesht_report t2
                WHERE technician_id = '$user_id' AND vid = '$vid')";
                $result1 = mysqli_query($dbc, $sql_record);
                $data1 = mysqli_fetch_array($result1);
                $_SESSION['data_id'] = $data1['id'];
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
            $sql = "SELECT t1.*
            FROM tbl_trblesht_report t1
            WHERE t1.id = (SELECT MAX(t2.id)
            FROM tbl_trblesht_report t2
            WHERE id = '$data_id' AND technician_id = '$user_id' AND vid = '$vid')";

            $result = mysqli_query($dbc, $sql);
            $total = mysqli_num_rows($result);
            $data = mysqli_fetch_array($result);

            if (
                !empty($data['timeof_arrival']) && !empty($data['work_time_start'])
                && !empty($data['reason_offline']) && !empty($data['cause_offline']) && !empty($data['complete_address'])
            ) {
                header('location: troubleshooting.php?account_id='.$account_id.'&vid='.$vid);
            }
            ?>
            <!-- content -->
            <div class="card-body">
                <!-- form -->
                <form method="post" action="">
                    <div class="form-group mb-2">
                        <label style="margin-bottom: -2px; font-size: 20px; font-weight:400" for="arrival_time" class="form-label">Arrival Time</label>
                        <input type="time" id="arrival_time" name="arrival_time" class="form-control fs-5 <?php echo (!empty($arrival_time_err)) ? 'is-invalid' : ''; ?>" value="<?= (isset($arrival_time)) ? $arrival_time : ''; ?>">
                        <span class="invalid-feedback"><?php echo $arrival_time_err;?></span>
                    </div>
                    <div class="form-group mb-2">
                        <label style="margin-bottom: -2px; font-size: 20px; font-weight:400" for="work_start" class="form-label">Work Time Start</label>
                        <input type="time" id="work_start" name="work_start" class="form-control fs-5 <?php echo (!empty($work_start_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $work_start; ?>">
                        <span class="invalid-feedback"><?php echo $work_start_err;?></span>
                    </div>
                    <!-- Select offline reason in table-->
                    <?php
                    include 'head.php';
                    require_once("conection.php");
                    $reason_for_offline = "SELECT * FROM tbl_reason_for_offline";
                    $reason_for_offline_query = mysqli_query($dbc, $reason_for_offline);
                    ?>
                    <div class="mb-2">
                        <label style="margin-bottom: -2px; font-size: 20px; font-weight:400" for="arrival_time" class="form-label">Reason for Offline</label>
                        <select style="font-size:20px" class="form-select <?php echo (!empty($cause_offline_err)) ? 'is-invalid' : ''; ?>" id="reason_category" name="reason_offline" value="<?= (isset($reason_for_offline)) ? $reason_for_offline : ''; ?>">
                            <option selected disabled value="">Select reason for offline</option>
                            <?php while ($data = mysqli_fetch_assoc($reason_for_offline_query)) : ?>
                                <option value="<?php echo $data['reason_id'] . $data['reason_for_offline_name']; ?>"><?php echo $data['reason_for_offline_name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $reason_offline_err;?></span>
                    </div>
                    <!-- Select cause based on offline reason -->
                    <div class="mb-2">
                        <label style="margin-bottom: -2px; font-size: 20px; font-weight:400" for="arrival_time" class="form-label">Cause Of Offline</label>
                        <select style="font-size:20px" class="form-select <?php echo (!empty($reason_offline_err)) ? 'is-invalid' : ''; ?>" id="cause_category" name="cause_offline" value="<?= (isset($cause_offline)) ? $cause_offline : ''; ?>">
                            <option selected disabled value=""> Select cause of Offline </option>
                        </select>
                        <span class="invalid-feedback"><?php echo $cause_offline_err;?></span>
                    </div>
                    <div class="mb-3">
                        <label style="margin-bottom: -2px; font-size: 20px; font-weight:400" for="address" class="form-label">Address</label>
                        <textarea style="font-size:20px" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>" id="exampleFormControlTextarea1" name="address" rows="3"></textarea>
                        <span class="invalid-feedback"><?php echo $address_err; ?></span>
                    </div>
                    <div class="d-grid col-5 mx-auto mb-2">
                        <input type="submit" name="submit" value="Save" class="btn btn-outline-primary"  onclick="return confirm('Are you sure you want to submit this form?');">
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script src="script/script.js"></script>
</body>
</html>