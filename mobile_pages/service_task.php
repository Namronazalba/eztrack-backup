<?php

// UPDATE STATUS OF VEHICLE WHEN OFFLINE
$query_offline_v = "SELECT * FROM `tbl_cvehicles`
WHERE accnt_id = 22 AND service_status = 'Done'
OR accnt_id = 43 AND service_status = 'Done'";
$result_offline_v = mysqli_query($dbc, $query_offline_v);

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

if (isset($_GET['account_id'])) {
    $account_id = $_GET['account_id'];

    if (isset($_GET['platenum'])) {
        $platenum = $_GET['platenum'];
    } else {
        $platenum = '';
    }
    $alldata_offline = "";
    $alldata_online = "";

    $num_per_page = 5;
    if (isset($_GET['offline-vehicle'])) {
        $page_offline_vehicle = $_GET['offline-vehicle'];
    } else {
        $page_offline_vehicle = 1;
    }

    $start_from_page_offline_vehicle = ($page_offline_vehicle - 1) * 5;

    $query_offline_v_all = "SELECT * FROM tbl_cvehicles WHERE accnt_id = $account_id AND service_status = 'no service'";

    $result_offline_v_all = mysqli_query($dbc, $query_offline_v_all);


    while ($row = $result_offline_v_all->fetch_assoc()) {
        $imei_vehicle =  $row['imei'];
        $vehicle_id = $row['vid'];
        $vpltnum = $row['vplatenum'];
        $service_status = $row['service_status'];

        // $query_last_log = "SELECT gps_date,address FROM $imei_vehicle
        // ORDER BY gps_date DESC LIMIT 1";
        $query_last_log = "SELECT gps_date, address FROM $imei_vehicle WHERE log_id=(SELECT MAX(log_id) FROM $imei_vehicle)";
        $result_last_log = mysqli_query($dbc, $query_last_log);

        $row1 = mysqli_fetch_array($result_last_log);
        $gps_date = $row1['gps_date'];
        $gps_address = $row1['address'];
        $gps_date_to_time = strtotime($row1['gps_date']);

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
            $address_offline = str_replace(',', '.', $gps_address);
            $show = "<a href='details.php?account_id=$account_id&vid=$vehicle_id'><input type='button' class='btn btn-sm btn-outline-info' value='View'/></a>";
            $alldata_offline .= "$imei_vehicle,$vehicle_id,$vpltnum,$address_offline,$duration, $show, $gps_date_to_time,";
        } else {
            $address_online = str_replace(',', '.', $gps_address);
            $alldata_online .= "$imei_vehicle,$vehicle_id,$vpltnum,$address_online,$duration,";
        }
    }


    $data = "$alldata_offline";
    $string = rtrim($data, ",");
    $myArray = explode(',', $string);

    // SORT VEHICLES ORDER BY GPS DATE ASC
    $imei = 0;
    $vid = 1;
    $platenumber = 2;
    $address = 3;
    $gps_last_date_convert = 4;
    $button = 5;
    $gps_last_date = 6;

    $array = array();
    for ($i = 0; $i < count($myArray) / 7; $i++) {
        $array[] = array(
            $myArray[$imei],  $myArray[$vid],  $myArray[$platenumber], $myArray[$address],
            $myArray[$gps_last_date_convert],  $myArray[$button],  $myArray[$gps_last_date]
        );

        $imei += 7;
        $vid += 7;
        $platenumber += 7;
        $address += 7;
        $gps_last_date_convert += 7;
        $button += 7;
        $gps_last_date += 7;
    }
    $last_report = array_column($array, 6);
    array_multisort($last_report, SORT_ASC, $array);
}
// declare variable for data and array
if (empty($myArray)) {
    $data = "";
    $myArray = array();
}

?>
<!doctype html>
<html lang="en">
<!-- head -->
<?php include 'head.php'; ?>
<style>
    .table-responsive {
        max-height: 400px;
        box-shadow: 0px 3px 11px rgba(0, 0, 0, 0.25);
    }
</style>
<body class="p-3 m-0 border-0 bd-example bgcolor">
    <div class="container-mobile">
        <p class="fs-5 text-black mt-4">Service Task / <span class="text-secondary">Offline Vehicles</span></p>
        <div class="card px-2 py-4 mb-1">
            <!-- Search bar-->
            <div class="container">
                <div class="row">
                    <div class="col-12 mb-2">
                        <div class="input-group">
                            <input type="text" id="input" name="Search" class="form-control text-center fw-bold text-uppercase fs-5" placeholder="Search...">
                        </div>
                    </div>
                    <div class="col-12">
                        <form action="" method="GET">
                            <div class="input-group">
                                <select class="form-select fs-5" aria-label="Default select example" name="account_id" required>
                                    <option selected disabled value="">Select Account</option>
                                    <option value="22" <?= (isset($_GET['account_id']) && $_GET['account_id'] == 22) ? 'selected' : ''; ?>>Dole Philippines</option>
                                    <option value="43" <?= (isset($_GET['account_id']) && $_GET['account_id'] == 43) ? 'selected' : ''; ?>>XDE Logistics</option>
                                </select>
                                <button type="submit" class="btn btn-primary" style="margin-left: 3px;" name="submit-button">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
            // QUERY ALL THE OFFLINE VEHICLES ACCORDING TO ACCOUNT ID
            if (isset($_GET['account_id'])) {
                $topdf = "";
                if (array_key_exists(4, $myArray)) {
                    echo "<div class='table-responsive mb-5'>
                        <table id=\"customers\" class=\"table table-striped table-hover text-center\" style=\"box-shadow: 0px 3px 11px rgba(0, 0, 0, 0.25);\">
                        <tr class='text-white' style=\"height:40px;background-color: #7280A5;font-weight: 600;\">
                        <th style='display:none'>VID</th>
                        <th style='width:100px; color:white;padding-bottom:17px;padding-top:17px; line-height: 1'>PLATE NUMBER</th>
                        <th style='width:300px; color:white;padding-top:18px'>LOCATION</th>
                        <th style='width:300px; color:white; line-height: 1;padding-top:13px'>LAST REPORT</th>
                        <th style='width:70px; color:white;padding-top:17px'>ACTION</th>
                        </tr>
                        <tbody id='table'>
                        ";
                    $x = 1;
                    $sch = 1;
                    $z = 1;
                    foreach ($array as $data) {
                        foreach ($data as $value) {
                            if ($x == 1) {
                                echo "<tr>";
                            }
                            if ($x <= 7) {
                                if ($x == 1 || $x == 2 || $x == 7) {
                                    echo "<td style='display:none'>$value</td>";
                                } else {
                                    echo "
                        <td class='fw-bold' style='vertical-align:middle;'>$value</td> ";
                                }
                            }
                            if ($x == 7) {
                                echo "</tr>";
                                $sch++;
                                $x = 1;
                            } else {
                                $x++;
                            }
                        }
                    }
                }
                echo "</tbody></table></div>";
            } else {
                echo "
                    <table class='table table-striped table-hover text-center mb-5' style='box-shadow: 0px 3px 11px rgba(0, 0, 0, 0.25);'>
                    <tr class='text-white' style='height:40px;background-color: #7280A5;font-weight: 600'>
                        <th style='display:none'>VID</th>
                        <th style='width:200px; color:white;'>PLATE NUMBER</th>
                        <th style='width:300px; color:white;padding-top:20px'>LOCATION</th>
                        <th style='width:300px; color:white;'>LAST REPORT</th>
                        <th style='width:70px; color:white;padding-top:20px'>ACTION</th>
                    </tr>
                    <tr>
                        <td colspan=5 style='text-align:center; font-weight:700'>No Data</td>
                    </tr>
                </table>";
            }

            ?>
    </div>
</body>
<script src="jquery/jquery-3.6.2.min.js"></script>
<script>
    $(document).ready(function() {
        $("#input").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#table tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
</html>