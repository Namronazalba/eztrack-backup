
 <?php
    include('session.php');
    require_once("conection.php");
    if (isset($_GET['account_id'])) {
        $account_id = $_GET['account_id'];
    }
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
    $data = mysqli_fetch_assoc($result);
    $id = $data['id'];



    if (isset($_POST['submit']) && isset($_FILES['my_image'])) {
        $date_end = $_POST['date_end'];
        $time_end = $_POST['time_end'];
        $action_taken_data = $_POST['action_taken'];
        // GET ONLY THE NAME OF ACTION TAKEN IN ACTION TAKEN FIELD
        $string = preg_match_all("/[0-9]/", $action_taken_data);

        if ($string == 1) {
            $action_taken = substr($action_taken_data, 1, 90);
            $action_taken_id = substr($action_taken_data, 0, 1);
        } else {
            $action_taken = substr($action_taken_data, 2, 90);
            $action_taken_id = substr($action_taken_data, 0, 2);
        }

        $witness = $_POST['witness'];
        $contact = $_POST['contact'];
        $coordinated_by = $_POST['coordinated_by'];


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

        // SET troubleshooting status according to action_taken

        if (
            $action_taken_id == '1' || $action_taken_id == '3' || $action_taken_id == '4' || $action_taken_id == '5' || $action_taken_id == '6' ||
            $action_taken_id == '7' || $action_taken_id == '8' || $action_taken_id == '10' || $action_taken_id == '14' || $action_taken_id == '15' ||
            $action_taken_id == '16' || $action_taken_id == '18'
        ) {
            // IF IMAGE IS SIZE IS GREATER THAN 20000000 AND NOT PROPER EXTENSION IMG WONT UPDATE
            if ($img_size < 20000000 && in_array($img_ex_lc, $allowed_exs)) {
                $troubleshooting_status = "Fixed";
                $task_status = "Finished";
                mysqli_query($dbc, 'UPDATE tbl_trblesht_report
            SET date_work_end ="' . $date_end . '",
            work_time_end ="' . $time_end . '", action_taken ="' . $action_taken . '",
            checked_by ="' . $witness . '", contact_num = "' . $contact . '",
            coordinated_by = "' . $coordinated_by . '", troubleshooting_status = "' . $troubleshooting_status . '",
            task_status="' . $task_status . '", date_submitted = NOW() WHERE vid = "' . $vid . '" AND id = "' . $id . '" AND technician_id = "' . $user_id . '"');


                $service_status = "Done";
                mysqli_query($dbc, 'UPDATE tbl_cvehicles
            SET service_status = "' . $service_status . '" WHERE vid = "' . $vid . '" AND accnt_id = "' . $account_id . '"');
            }
        } elseif ($action_taken_id == '2' || $action_taken_id == '9' || $action_taken_id == '17' || $action_taken_id == '11') {
            if ($img_size < 20000000 && in_array($img_ex_lc, $allowed_exs)) {
                $troubleshooting_status = "Not Fixed";
                $task_status = "Unfinished";
                mysqli_query($dbc, 'UPDATE tbl_trblesht_report
            SET date_work_end ="' . $date_end . '",
            work_time_end ="' . $time_end . '", action_taken ="' . $action_taken . '",
            checked_by ="' . $witness . '", contact_num = "' . $contact . '",
            coordinated_by = "' . $coordinated_by . '", troubleshooting_status = "' . $troubleshooting_status . '",
            task_status="' . $task_status . '", date_submitted = NOW() WHERE vid = "' . $vid . '" AND id = "' . $id . '" AND technician_id = "' . $user_id . '"');

                $service_status = "Ongoing";
                mysqli_query($dbc, 'UPDATE tbl_cvehicles
            SET service_status = "' . $service_status . '" WHERE vid = "' . $vid . '" AND accnt_id = "' . $account_id . '"');
            }
        } else {
            if ($img_size < 20000000 && in_array($img_ex_lc, $allowed_exs)) {
                $troubleshooting_status = "For GPS transfer";
                $task_status = "Finished";
                mysqli_query($dbc, 'UPDATE tbl_trblesht_report
            SET date_work_end ="' . $date_end . '",
            work_time_end ="' . $time_end . '", action_taken ="' . $action_taken . '",
            checked_by ="' . $witness . '", contact_num = "' . $contact . '",
            coordinated_by = "' . $coordinated_by . '", troubleshooting_status = "' . $troubleshooting_status . '",
            task_status="' . $task_status . '", date_submitted = NOW() WHERE vid = "' . $vid . '" AND id = "' . $id . '" AND technician_id = "' . $user_id . '"');

                $service_status = "Done";
                $update_service_status = 'UPDATE tbl_cvehicles
            SET service_status = "' . $service_status . '" WHERE vid = "' . $vid . '" AND accnt_id = "' . $account_id . '"';
                mysqli_query($dbc, $update_service_status);
            }
        }


        // UPLOAD IMAGE
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
                header("Location: troubleshooting.php?account_id=$account_id" . "&vid=$vid" . "&error=$em");
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
                    SET image_url ="' . $new_img_name . '", date_submitted = NOW()  WHERE id ="' . $id . '"';

                    mysqli_query($dbc, $sql);
                    header("Location: index.php");
                    // if ($result) {
                    //     $_SESSION['success'] = "added successfully";
                    //     header('location: index.php');
                    // }

                } else {
                    $em = "You can't upload files of this type";
                    header("Location: troubleshooting.php?account_id=$account_id" . "&vid=$vid" . "&error=$em");
                }
            }
        } else {
            $em = "unknown error occurred!";
            header("Location: troubleshooting.php?account_id=$account_id" . "&vid=$vid" . "&error=$em");
        }
    } else {
        header("Location: troubleshooting.php?account_id=$account_id" . "&vid=$vid" . "&error=$em");
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
