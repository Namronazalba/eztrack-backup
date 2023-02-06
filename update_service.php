<!doctype html>
<html lang="en">

<!-- head -->
<?php
require('conection.php');
include 'head.php';
session_start();
$user_id = $_SESSION['user_id'];
$vid = $_GET['vid'];
$data_id = $_GET['id'];
$account_id = $_GET['account_id'];
$query_update_service = "SELECT * FROM tbl_trblesht_report
    LEFT JOIN tbl_cvehicles
    ON tbl_trblesht_report.vid = tbl_cvehicles.vid
    WHERE id = $data_id";
$result = mysqli_query($dbc, $query_update_service);
// DATA
$row = mysqli_fetch_array($result);
$cause_offline = $row['cause_offline'];

$reason_for_offline = $row['reason_offline'];

// QUERY CAUSE OF OFFLINE
$cause_of_offline = "SELECT * FROM  tbl_cause_of_offline
LEFT JOIN tbl_reason_for_offline
ON tbl_cause_of_offline.reason_for_offline_id = tbl_reason_for_offline.reason_id
WHERE reason_for_offline_name = '$reason_for_offline'";

$result_cause_of_offline = mysqli_query($dbc, $cause_of_offline);


// QUERY ACTION TAKEN
$query_action_taken = "SELECT * FROM tbl_action_taken
    INNER JOIN tbl_pivot_cause_action
    ON tbl_action_taken.action_id = tbl_pivot_cause_action.action_taken_id
    INNER JOIN tbl_cause_of_offline
    ON tbl_cause_of_offline.cause_id = tbl_pivot_cause_action.cause_of_offline_id
    INNER JOIN tbl_trblesht_report
    ON tbl_trblesht_report.cause_offline = tbl_cause_of_offline.cause_of_offline_name
    WHERE id = '$data_id' 
    ";

$result_action_taken = mysqli_query($dbc, $query_action_taken);
?>
<body class="p-3 m-0 border-0 bd-example bgcolor">
    <!-- navbar -->
    <?php include 'navbar.php'; ?>
    <!-- content -->
    <style>
        #preview {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 35vh;
        }
        @media(max-width: 576px) {
          .update-service-desktop {
               display: none;
          }
        } 
    </style>
    <div class="update-service-desktop container">
    <p class="fs-5 text-black mt-4">Pending Task / <span class="text-secondary">Update Service</span></p>
        <div class="card" style="box-shadow: 0px 3px 11px rgba(0, 0, 0, 0.25);">
            <div class="card-header text-white text-center" style="background: #7280A5; border-radius: 5px 5px 20px 20px;">
                <p class="mt-2">Plate No:</p>
                <h2 class="card-text p-2 " style="margin-top: -20px;"><?= $row['vplatenum'] ?></h2>
            </div>
            <div class="card-body p-5">
                <form action="update_service_form.php?id=<?= $data_id; ?>&vid=<?= $vid; ?>&account_id=<?= $account_id; ?>" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="witness" class="form-label">Address Checked</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="mb-3">
                        <label for="date_performed" class="form-label">Date Performed</label>
                        <input type="date" class="form-control" id="date_performed" name="date_performed" required>
                    </div>
                    <div class="mb-3">
                        <label for="date_work_end" class="form-label">Date Work End</label>
                        <input type="date" class="form-control" id="date_work_end" name="date_work_end" required>
                    </div>
                    <div class="mb-3">
                        <label for="departure_time" class="form-label">Departure Time</label>
                        <input type="time" class="form-control" id="departure_time" name="departure_time" required>
                    </div>
                    <div class="mb-3">
                        <label for="timeof_arrival" class="form-label">Time of Arrival</label>
                        <input type="time" class="form-control" id="timeof_arrival" name="timeof_arrival" required>
                    </div>
                    <div class="mb-3">
                        <label for="work_time_start" class="form-label">Work Time Start</label>
                        <input type="time" class="form-control" id="work_time_start" name="work_time_start" required>
                    </div>
                    <div class="mb-3">
                        <label for="work_time_end" class="form-label">Work Time End</label>
                        <input type="time" class="form-control" id="work_time_end" name="work_time_end" required>
                    </div>
                    <div class="mb-3">
                        <label for="coordinated_by" class="form-label">Coordinated by</label>
                        <input type="text" class="form-control" id="coordinated_by" name="coordinated_by" required>
                    </div>
                    <div class="mb-3">
                        <label for="checked_by" class="form-label">Checked by</label>
                        <input type="text" class="form-control" id="checked_by" name="checked_by" required>
                    </div>
                    <div class="mb-3">
                        <label for="contact_num" class="form-label">Contact num</label>
                        <input type="number" class="form-control" id="contact_num" name="contact_num" required>
                    </div>
                    <div class="mb-3">
                        <div id="preview" style="display: flex; align-items: center;"></div>
                        <?php if (isset($_GET['error'])) : ?>
                            <p><?php echo $_GET['error']; ?></p>
                        <?php endif ?>
                        <label for="file_upload" class="form-label">Upload Image</label>
                        <input type="file" name="my_image" id="image" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="reason_for_offline" class="form-label">Reason For Offline</label>
                        <input type="text" class="form-control" id="reason_category" name="reason_for_offline" value="<?= (isset($row['reason_offline'])) ? $row['reason_offline'] : ''; ?>" readonly>
                    </div>
                    <?php
                    if ($row['cause_offline'] == 'Standby/No trip (Intentionally disconnect/remove of vehicle battery)' ||
                    $row['cause_offline'] == 'Breakdown/Repair (Intentionally disconnect/remove of vehicle battery)'
                    ){ ?>
                    <div class="mb-2">
                        <label for="cause_of_offline" class="form-label">Cause Of Offline</label>
                        <select class="form-select" id="cause_category" name="cause_of_offline">
                            <option selected disabled value=""> Select Cause of Offline </option>
                            <?php
                            while ($data = $result_cause_of_offline->fetch_assoc()) { ?>
                                <option value="<?= $data['cause_id'].$data['cause_of_offline_name']; ?>"><?= $data['cause_of_offline_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                   <?php } else { ?>
                    <div class="mb-3">
                        <label for="cause_of_offline" class="form-label">Cause of Offline</label>
                        <input type="text" class="form-control" id="cause_of_offline" name="cause_of_offline" value="<?= (isset($row['cause_offline'])) ? $row['cause_offline'] : ''; ?>" readonly>
                    </div>
                <?php    } ?>
                  
                    <div class="mt-3 mb-3">
                        <label for="action_taken"><b>Action Taken</b></label>
                        <select class="form-select" id="action_category" aria-label="Default select example" name="action_taken" required>
                            <option selected disabled value=""><?= $row['action_taken']; ?></option>
                            <?php
                            while ($data = $result_action_taken->fetch_assoc()) { ?>
                                <option value="<?= $data['action_id'] . $data['action_taken_name']; ?>"><?= $data['action_taken_name']; ?></option>
                            <?php } ?>

                        </select>
                    </div>
                    <input type="submit" name="submit_button" class="btn btn-warning" value="Update">
                </form>
            </div>
        </div>
    </div>
    </div>
    <script src="script/script.js"></script>
     <?php include 'mobile_pages/update_service.php' ?>                           
</body>
</html>