<?php 
include 'conection.php';

$cause_of_offline_data = $_POST['cause_category_data'];
// Get only the id in input form
$cause_of_offline_id = preg_replace("/[^0-9]/", '', $cause_of_offline_data);
// $action_taken = "SELECT * FROM  tbl_cause_of_offline WHERE reason_for_offline_id = $reason_for_offline_id";

// QUERY ACTION TAKEN
$query_action_taken = "SELECT * FROM tbl_action_taken
INNER JOIN tbl_pivot_cause_action 
ON tbl_action_taken.action_id = tbl_pivot_cause_action.action_taken_id
INNER JOIN tbl_cause_of_offline
ON tbl_cause_of_offline.cause_id = tbl_pivot_cause_action.cause_of_offline_id
WHERE cause_of_offline_id = $cause_of_offline_id";
$result_action_taken = mysqli_query($dbc, $query_action_taken);


$output = '<option selected disabled> Select Action Taken </option>';
while ($action_taken_data = mysqli_fetch_assoc($result_action_taken)){
    $output .='<option value="'.$action_taken_data['action_id'].$action_taken_data['action_taken_name'].'">'.$action_taken_data['action_taken_name'].'</option>';
}

echo $output;
