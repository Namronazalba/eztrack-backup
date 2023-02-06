<?php 
include 'conection.php';

$reason_for_offline_id = $_POST['reason_category_data'];
// Get only the id in input form
$reason_for_offline_id = substr($reason_for_offline_id, 0, 1);
$cause_of_offline = "SELECT * FROM  tbl_cause_of_offline WHERE reason_for_offline_id = $reason_for_offline_id";
$cause_of_offline_query = mysqli_query($dbc, $cause_of_offline);

$output = '<option selected disabled> Select Cause of Offline </option>';
while ($cause_of_offline_data = mysqli_fetch_assoc($cause_of_offline_query)){
    $output .='<option value="'.$cause_of_offline_data['cause_id'].$cause_of_offline_data['cause_of_offline_name'].'">'.$cause_of_offline_data['cause_of_offline_name'].'</option>';
}

echo $output;
?>