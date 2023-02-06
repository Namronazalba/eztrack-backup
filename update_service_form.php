<?php
require('conection.php');
session_start();
// SESSION USER ID AND USERNAME
$user_id = $_SESSION['user_id'];
$user_technician_name = $_SESSION['username'];
$vid = $_GET['vid'];
$data_id = $_GET['id'];
$account_id = $_GET['account_id'];
$query_update_service = "SELECT * FROM tbl_trblesht_report WHERE id = $data_id";
$result = mysqli_query($dbc, $query_update_service);

if (isset($_POST['submit_button']) && isset($_FILES['my_image'])) {
    $address = $_POST['address'];
    $date_performed = $_POST['date_performed'];
    $date_work_end = $_POST['date_work_end'];
    $work_time_start = $_POST['work_time_start'];
    $work_time_end = $_POST['work_time_end'];
    $checked_by = $_POST['checked_by'];
    $contact_num = $_POST['contact_num'];
    $coordinated_by = $_POST['coordinated_by'];
    $reason_for_offline = $_POST['reason_for_offline'];
    $action_taken_data = $_POST['action_taken'];

    // UPDATE tbl_cvehicles
    $cause_offline_data = $_POST['cause_of_offline'];
    $cause_offline = preg_replace("/\d/u", "", $cause_offline_data);
    // Standby
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


    $timeof_arrival = $_POST['timeof_arrival'];
    $arrival_time_convert = strtotime($_POST['timeof_arrival']);
    $departure_time = $_POST['departure_time'];
    $departure_time_convert = strtotime($_POST['departure_time']);
    // Absolute value of time difference in seconds
    $diff = abs($arrival_time_convert - $departure_time_convert);

    // Convert $diff to minutes
    $tmins = $diff / 60;

    // Get hours
    $traveling_time_hrs = floor($tmins / 60);

    // Get minutes
    $traveling_time_mins = $tmins % 60;
    // Get traveling time
    $traveling_time = "HH:" . $traveling_time_hrs . " " . "MM:" . $traveling_time_mins;

    // GET ONLY THE NAME OF ACTION TAKEN IN ACTION TAKEN FIELD
    $string = preg_match_all("/[0-9]/", $action_taken_data);

    if ($string == 1) {
        $action_taken = substr($action_taken_data, 1, 90);
        $action_taken_id = substr($action_taken_data, 0, 1);
    } else {
        $action_taken = substr($action_taken_data, 2, 90);
        $action_taken_id = substr($action_taken_data, 0, 2);
    }

    // GET IMAGE DETAILS
    $img_name = $_FILES['my_image']['name'];
    $img_size = $_FILES['my_image']['size'];
    $tmp_name = $_FILES['my_image']['tmp_name'];
    $error = $_FILES['my_image']['error'];


    // GET PATH INFO
    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
    $img_ex_lc = strtolower($img_ex);

    // ALLOWED EXTENSION
    $allowed_exs = array("jpg", "jpeg", "png", "gif");


    // UPDATE TROUBLESHOOTING STATUS IN REPORT
    if (
        $action_taken_id == '1' || $action_taken_id == '3' || $action_taken_id == '4' || $action_taken_id == '5' || $action_taken_id == '6' ||
        $action_taken_id == '7' || $action_taken_id == '8' || $action_taken_id == '10' || $action_taken_id == '14' || $action_taken_id == '15' ||
        $action_taken_id == '16' || $action_taken_id == '18'
    ) {
        // IF IMAGE IS SIZE IS GREATER THAN 20000000 AND NOT PROPER EXTENSION IMG WONT UPDATE
        if ($img_size < 20000000 && in_array($img_ex_lc, $allowed_exs)) {
            $task_status = "Finished";
            mysqli_query($dbc, 'UPDATE tbl_trblesht_report SET task_status="' . $task_status . '", date_submitted = NOW()
        WHERE vid = "' . $vid . '" AND id = "' . $data_id  . '" AND technician_id = "' . $user_id . '"');

            $service_status = "Done";
            mysqli_query($dbc, 'UPDATE tbl_cvehicles
        SET service_status = "' . $service_status . '" WHERE vid = "' . $vid . '" AND accnt_id = "' . $account_id . '"');

            // INSERT NEW DATA
            $troubleshooting_status = "Fixed";
            $query_insert = "INSERT INTO tbl_trblesht_report (vid, complete_address, date_performed, date_work_end,
        departure_time, timeof_arrival, traveling_time, reason_offline, cause_offline, work_time_start, work_time_end,
        vehicle_status, checked_by, contact_num, technician, technician_id, coordinated_by, action_taken, troubleshooting_status, task_status, date_submitted)
        VALUES ('" . $vid  . "', '" . $address  . "', '" . $date_performed . "', '" . $date_work_end . "', '" . $departure_time . "',
        '" . $timeof_arrival . "', '" . $traveling_time . "', '" . $reason_for_offline . "', '" . $cause_offline . "',
        '" . $work_time_start . "', '" . $work_time_end . "', '" . $vstats . "', '" . $checked_by . "', '" . $contact_num . "', '" . $user_technician_name . "',
        '" . $user_id . "', '" . $coordinated_by . "', '" . $action_taken . "', '" . $troubleshooting_status . "', '" . $task_status . "', now())";
            mysqli_query($dbc, $query_insert);
        }
    } elseif ($action_taken_id == '2' || $action_taken_id == '9' || $action_taken_id == '17' || $action_taken_id == '11') {
        if ($img_size < 20000000 && in_array($img_ex_lc, $allowed_exs)) {
            $task_status = "Unfinished";
            mysqli_query($dbc, 'UPDATE tbl_trblesht_report SET task_status="' . $task_status . '", date_submitted = NOW()
        WHERE vid = "' . $vid . '" AND id = "' . $data_id . '" AND technician_id = "' . $user_id . '"');

            $service_status = "Ongoing";
            mysqli_query($dbc, 'UPDATE tbl_cvehicles
        SET service_status = "' . $service_status . '" WHERE vid = "' . $vid . '" AND accnt_id = "' . $account_id . '"');

            // INSERT NEW DATA
            $troubleshooting_status = "Not Fixed";
            $query_insert = "INSERT INTO tbl_trblesht_report (vid, complete_address, date_performed, date_work_end,
        departure_time, timeof_arrival, traveling_time, reason_offline, cause_offline, work_time_start, work_time_end,
        vehicle_status, checked_by, contact_num, technician, technician_id, coordinated_by, action_taken, troubleshooting_status, task_status, date_submitted)
        VALUES ('" . $vid  . "', '" . $address  . "', '" . $date_performed . "', '" . $date_work_end . "', '" . $departure_time . "',
        '" . $timeof_arrival . "', '" . $traveling_time . "', '" . $reason_for_offline . "', '" . $cause_offline . "',
        '" . $work_time_start . "', '" . $work_time_end . "', '" . $vstats . "', '" . $checked_by . "', '" . $contact_num . "', '" . $user_technician_name . "',
        '" . $user_id . "', '" . $coordinated_by . "', '" . $action_taken . "', '" . $troubleshooting_status . "', '" . $task_status . "', now())";
            mysqli_query($dbc, $query_insert);
        }
    } else {
        if ($img_size < 20000000 && in_array($img_ex_lc, $allowed_exs)) {
            $task_status = "Finished";
            mysqli_query($dbc, 'UPDATE tbl_trblesht_report SET task_status="' . $task_status . '", date_submitted = NOW()
        WHERE vid = "' . $vid . '" AND id = "' . $data_id  . '" AND technician_id = "' . $user_id . '"');

            $service_status = "Done";
            mysqli_query($dbc, 'UPDATE tbl_cvehicles
        SET service_status = "' . $service_status . '" WHERE vid = "' . $vid . '" AND accnt_id = "' . $account_id . '"');

            // INSERT NEW DATA
            $troubleshooting_status = "For GPS transfer";
            $query_insert = "INSERT INTO tbl_trblesht_report (vid, complete_address, date_performed, date_work_end,
            departure_time, timeof_arrival, traveling_time, reason_offline, cause_offline, work_time_start, work_time_end,
            vehicle_status, checked_by, contact_num, technician, technician_id, coordinated_by, action_taken, troubleshooting_status, task_status, date_submitted)
            VALUES ('" . $vid  . "', '" . $address  . "', '" . $date_performed . "', '" . $date_work_end . "', '" . $departure_time . "',
            '" . $timeof_arrival . "', '" . $traveling_time . "', '" . $reason_for_offline . "', '" . $cause_offline . "',
            '" . $work_time_start . "', '" . $work_time_end . "', '" . $vstats . "', '" . $checked_by . "', '" . $contact_num . "', '" . $user_technician_name . "',
            '" . $user_id . "', '" . $coordinated_by . "', '" . $action_taken . "', '" . $troubleshooting_status . "', '" . $task_status . "', now())";
                mysqli_query($dbc, $query_insert);
        }
    }

    if ($img_size < 20000000 && in_array($img_ex_lc, $allowed_exs)) {
        // get id from troubleshoot report //
        $getId = "SELECT * FROM tbl_trblesht_report t1
        WHERE t1.id = (SELECT MAX(t2.id)
        FROM tbl_trblesht_report t2
        WHERE technician_id = '$user_id' AND vid = '$vid')";

        $result_last_data = mysqli_query($dbc, $getId);
        $data = mysqli_fetch_array($result_last_data);
        $id = $data['id'];

        // UPDATE DOLE ACTION TABLE
        $sqltrbl = "INSERT INTO tbl_doleaction(vid, checked_by, datime_checked, troubleshoot_id, vstatus)
                 VALUES	('$vid','','','$id','$vstats')";

        if (!mysqli_query($dbc, $sqltrbl)) {
            die('Error: ' . mysqli_connect_error());
        }

        //UPDATE REPORT IF UNFINISHED

        if ($data['task_status'] == 'Unfinished'){
            mysqli_query($dbc, "UPDATE tbl_trblesht_report SET task_status ='Finished', date_submitted = NOW() where vid = '$vid' AND id = '$data_id'");
        }
    }

    echo "<pre>";
    print_r($_FILES['my_image']);
    echo "</pre>";

    $img_name = $_FILES['my_image']['name'];
    $img_size = $_FILES['my_image']['size'];
    $tmp_name = $_FILES['my_image']['tmp_name'];
    $error = $_FILES['my_image']['error'];

    if ($error === 0) {
        if ($img_size > 20000000) {
            $em = "Sorry, your file is too large.";
            header("Location: update_service.php?id=$data_id" . "&vid=$vid" . "&account_id=$account_id" . "&error=$em");
        } else {
            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);

            $allowed_exs = array("jpg", "jpeg", "png", "gif");

            if (in_array($img_ex_lc, $allowed_exs)) {
                $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
                $img_upload_path = 'uploads/' . $new_img_name;
                compressImage($tmp_name, $img_upload_path, 40);

                // UPDATE IMAGE
                $sql = 'UPDATE tbl_trblesht_report
                        SET image_url ="' . $new_img_name . '", date_submitted = NOW() WHERE id ="' . $id . '"';

                mysqli_query($dbc, $sql);
                header("Location: index.php");
            } else {
                $em = "You can't upload files of this type";
                header("Location: update_service.php?id=$data_id" . "&vid=$vid" . "&account_id=$account_id" . "&error=$em");
            }
        }
    } else {
        $em = "Image can't be blank!";
        header("Location: update_service.php?id=$data_id" . "&vid=$vid" . "&account_id=$account_id" . "&error=$em");
    }
} else {
    header("Location: update_service.php?id=$data_id" . "&vid=$vid" . "&account_id=$account_id" . "&error=$em");
}



function compressImage($source, $destination, $quality)
{
    $info = getimagesize($source);
    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($source);
    elseif ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($source);
    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source);
    imagejpeg($image, $destination, $quality);

    return $destination;
}
