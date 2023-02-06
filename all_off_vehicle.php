<?php 

function all_off_vehicle($account_id, $dbc, $platenum){
    $alldata_offline = "";
    $alldata_online = "";
    $query_offline_v = "SELECT * FROM tbl_cvehicles WHERE accnt_id = $account_id
    AND vplatenum LIKE '%$platenum%'
    ";

    $result_offline_v = mysqli_query($dbc, $query_offline_v);
    while ($row = $result_offline_v->fetch_assoc()) {
        $imei_vehicle =  $row['imei'];
        $vehicle_id = $row['vid'];
        $vpltnum = $row['vplatenum'];
    
        $query_last_log = "SELECT gps_date,address FROM $imei_vehicle
            ORDER BY gps_date DESC LIMIT 1";
    
        $result_last_log = mysqli_query($dbc, $query_last_log);
    
        $row1 = mysqli_fetch_array($result_last_log);
    
        $gps_date = $row1['gps_date'];
        $gps_address = $row1['address'];
    
        $duration = fordate($gps_date);
    
        $string = preg_match_all("/[0-9]/", $duration);
    
        if ($string == 1) {
    
            $number_hour = substr($duration, 0, 1);
            $word_hour = substr($duration, 2, 90);
        } else {
    
            $number_hour = substr($duration, 0, 2);
            $word_hour = substr($duration, 3, 90);
        }
    
        if ((($number_hour >= 3 && $word_hour == 'days ago') || $word_hour == 'week ago' || $word_hour == 'weeks ago' || $word_hour == 'month ago' || $word_hour == 'months ago' || $word_hour == 'year ago' || $word_hour == 'years ago')) {
            $address_offline = str_replace(',', '.', $gps_address);
            $show = "<a href='details.php?account_id=$account_id&vid=$vehicle_id'><input type='button' class='btn btn-primary' value='View'/></a>";
            $alldata_offline .= "$imei_vehicle,$vehicle_id,$vpltnum,$address_offline,$duration, $show,";
        } else {
            $address_online = str_replace(',', '.', $gps_address);
            $alldata_online .= "$imei_vehicle,$vehicle_id,$vpltnum,$address_online,$duration,";
        }
    }

    return $alldata_offline;
}