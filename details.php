<?php
include('session.php');
require('conection.php');
require('function.php');
if (isset($_GET['vid'])){
    $vid = $_GET['vid'];
    $account_id = $_GET['account_id'];
    $query_detail = "SELECT * FROM tbl_cvehicles WHERE vid = $vid";
    $result = mysqli_query($dbc, $query_detail);
    $data = mysqli_fetch_array($result);
    $imei = $data['imei'];
    $query_last_log = "SELECT * FROM $imei ORDER BY gps_date DESC LIMIT 1";
    $result_last_log = mysqli_query($dbc, $query_last_log);
    $data_last_log = mysqli_fetch_array($result_last_log);   
}
?>
<!doctype html>
<html lang="en">
<!-- head -->
<?php include 'head.php';?>
<body class="p-3 m-0 border-0 bd-example bgcolor">
    <!-- navbar -->
    <?php include 'navbar.php'; ?>
    <!-- content -->
    <div class="container-details-desktop container">
    <p class="fs-5 text-black mt-4">Service Task / <span class="text-secondary">Details</span></p>
        <div class="card" style="box-shadow: 0px 3px 11px rgba(0, 0, 0, 0.25);">
            <div class="card-header text-white text-center" style="background: #7280A5; border-radius: 5px 5px 20px 20px;">
                <p class="mt-2">Plate No:</p>
                <h2 class="card-text p-2 " style="margin-top: -20px;"><?= $data['vplatenum']?></h2>
            </div>
            <div class="card-body p-5">
                <p><b class="fw-bolder">LOCATION:</b> <?= $data_last_log['address']?></p>
                <p><b class="fw-bolder">DATE OFFLINE:</b> <?= fordate($data_last_log['gps_date'])?></p>
                <p><b class="fw-bolder">IMEI:</b> <?= $data['imei']?></p>
                <p><b class="fw-bolder">SIM CARD #:</b> <?= $data['simnum']?></p>
                <a href="traveling.php?account_id=<?= $account_id; ?>&vid=<?= $data['vid']?>" class="btn btn-success">Acknowledge</a>
            </div>
        </div>
    </div>
    <!-- FOR MOBILE PAGE DEVICES -->
    <?php require 'mobile_pages/details.php'; ?>
</body>
</html>