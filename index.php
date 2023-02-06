<?php 
include('session.php');
require_once('session_technician.php');
require('conection.php');
require('function.php');
$user_id = $_SESSION['user_id'];
// CLEAR SESSION DATA ID and CAUSE_OFFLINE_ID
$_SESSION['data_id'] = '';
$_SESSION['cause_offline_id'] = '';

// UPDATE STATUS OF VEHICLE WHEN OFFLINE
$query_offline_v = "SELECT * FROM `tbl_cvehicles`
WHERE accnt_id = 22 AND service_status = 'Done'
OR accnt_id = 43 AND service_status = 'Done'";
$result_offline_v = mysqli_query($dbc, $query_offline_v);

$offline = 0;
$online = 0;

while ($row = $result_offline_v->fetch_assoc()) {
  $imei_vehicle =  $row['imei'];
  $vehicle_id = $row['vid'];

  $query_last_log = "SELECT gps_date,address FROM $imei_vehicle
  ORDER BY gps_date DESC LIMIT 1";

  $result_last_log = mysqli_query($dbc, $query_last_log);
  $row1 = mysqli_fetch_array($result_last_log);
  $gps_date = $row1['gps_date'];
  $last_gps_date = (strtotime($gps_date));
  $now = (strtotime("now"));
  $sub = $now - $last_gps_date;
  // if gps_date is greater than or equal to 259200secs or 3 days from now update the status of vehicle
  if ($sub >= 259200) {
    $service_status_offline = "no service";
    $query = 'UPDATE tbl_cvehicles
      SET service_status = "' . $service_status_offline . '" WHERE vid = "' . $vehicle_id . '"';
    if (!mysqli_query($dbc, $query)) {
      echo ("Error description: " . mysqli_error($dbc));
    }
  }
}
// QUERY OFFLINE VEHICLE OF DOLE AND XDE
$query_all_vehicle = "SELECT * FROM `tbl_cvehicles`
WHERE accnt_id = 22 AND service_status = 'no service'
OR accnt_id = 43 AND service_status = 'no service'";
$result_offline_vehicle = mysqli_query($dbc, $query_all_vehicle);

$offline = 0;
$online = 0;
while ($row = $result_offline_vehicle->fetch_assoc()) {
  $imei_vehicle =  $row['imei'];
  $vehicle_id = $row['vid'];
  $vpltnum = $row['vplatenum'];
  $service_status = $row['service_status'];

  $query_last_log = "SELECT gps_date FROM $imei_vehicle WHERE log_id=(SELECT MAX(log_id) FROM `$imei_vehicle`)";
  // $query_last_log = "SELECT gps_date,address FROM $imei_vehicle
  // ORDER BY gps_date DESC LIMIT 1";

  $result_last_log = mysqli_query($dbc, $query_last_log);

  $row1 = mysqli_fetch_array($result_last_log);

  $gps_date = $row1['gps_date'];

  $duration = fordate($gps_date);

  $string = preg_match_all("/[0-9]/", $duration);

  if ($string == 1) {

    $number_hour = substr($duration, 0, 1);
    $word_hour = substr($duration, 2, 90);
  } else {

    $number_hour = substr($duration, 0, 2);
    $word_hour = substr($duration, 3, 90);
  }

  if (((($number_hour >= 3 && $word_hour == 'days ago') || $word_hour == 'week ago' || $word_hour == 'weeks ago' || $word_hour == 'month ago' || $word_hour == 'months ago' || $word_hour == 'year ago' || $word_hour == 'years ago') && $service_status == 'no service')) {
    $offline++;
  }
}


//QUERY ALL PENDING TASK
$query_all_pending_task = "SELECT * FROM tbl_trblesht_report
WHERE task_status = 'Unfinished' AND technician_id = '$user_id'";
$query_run_all_pending_task = mysqli_query($dbc, $query_all_pending_task);

//QUERY ALL FINISHED
$query_all_finished_task = "SELECT * FROM tbl_trblesht_report
WHERE task_status = 'Finished' AND technician_id = '$user_id'";
$query_run_all_finished_task = mysqli_query($dbc, $query_all_finished_task);
?>

<!doctype html>
<html lang="en">
<!-- head -->
<?php include 'head.php'; ?>

<body class="p-3 m-0 border-0 bd-example bgcolor">
  <!-- navbar -->
  <?php include 'navbar.php'; ?>
  <!-- content -->
  <div id="dashboard-desktop" class="container">
    <p class="fs-5 text-black mt-4">Home / <span class="text-secondary">Dashboard</span></p>
    <div class="row">
      <div class="col-12 col-lg-4">
        <div class="card mb-2 border-0">
          <div class="card-body">
            <div class="p-2">
              <?php
              if ($offline > 0) { ?>
                <p class="num fs-1" data-val="<?= $offline ?>" style="margin-left: 18px"></p>
              <?php } else { ?>
                <p class="fs-1" style="margin-top:0px; margin-left: 20px">0</p>
              <?php } ?>
              <h6 class="fw-bold" style="margin-top:-25px; margin-left: 20px">Total Service Task</h6>
            </div>
            <img style="height: 90%; width: 30%; position:absolute; margin-left: 240px; margin-top: -105px" src="uploads/service-task.png" alt="4014703-driver-mobile-phone-repair-screw-service-wrench-112878" alt="">
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-4">
        <div class="card mb-2 border-0 ">
          <div class="card-body">
            <div class="p-2">
              <?php
              if (mysqli_num_rows($query_run_all_pending_task) > 0) { ?>
                <p class="num fs-1" data-val="<?= mysqli_num_rows($query_run_all_pending_task) ?>" style="margin-left: 18px"></p>
              <?php } else { ?>
                <p class="fs-1" style="margin-top:0px; margin-left: 20px">0</p>
              <?php } ?>
              <h6 class="fw-bold" style="margin-top:-25px; margin-left: 20px">Total Pending Task</h6>
            </div>
            <img style="height: 90%; width: 30%; position:absolute; margin-left: 240px; margin-top: -105px" src="uploads/pending-task.png" alt="4014703-driver-mobile-phone-repair-screw-service-wrench-112878" alt="">
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-4">
        <div class="card mb-2 border-0 ">
          <div class="card-body">
            <div class="p-2">
            <?php
              if (mysqli_num_rows($query_run_all_finished_task) > 0) { ?>
                <p class="num fs-1" data-val="<?= mysqli_num_rows($query_run_all_finished_task) ?>" style="margin-left: 18px"></p>
              <?php } else { ?>
                <p class="fs-1" style="margin-top:0px; margin-left: 20px">0</p>
              <?php } ?>
              <h6 class="fw-bold" style="margin-top:-25px; margin-left: 21px">Total finished Task</h6>
            </div>
            <img style="height: 90%; width: 30%; position:absolute; margin-left: 240px; margin-top: -105px" src="uploads/finished-task.png" alt="4014703-driver-mobile-phone-repair-screw-service-wrench-112878" alt="">
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- DASHBOARD FOR MOBILE -->
  <?php require_once 'mobile_pages/index.php'; ?>
</body>

</html>