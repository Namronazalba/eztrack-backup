<!doctype html>
<html lang="en">
<!-- head -->
<?php
$user_id = $_SESSION['user_id'];
$vid = $_GET['vid'];
$data_id = $_GET['id'];
$account_id = $_GET['account_id'];
$query_update_service = "SELECT * FROM tbl_trblesht_report
    LEFT JOIN tbl_cvehicles
    ON tbl_trblesht_report.vid = tbl_cvehicles.vid
    WHERE id = $data_id";
$result = mysqli_query($dbc, $query_update_service);

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
     <!-- content -->
     <style>
          @media(min-width: 576px) {
               .update-service-mobile {
                    display: none;
               }
          }
     </style>
     <div class="update-service-mobile container">
          <p class="mt-4 fs-5">Pending Task / <span class="text-secondary">Update Service</span></p>
          <?php
          $row = mysqli_fetch_array($result);
          ?>
          <div class="card" style="box-shadow: 0px 3px 11px rgba(0, 0, 0, 0.25);">
               <div class="card-header text-white text-center" style="background: #7280A5; border-radius: 5px 5px 20px 20px;">
                    <p style="font-size:14px; margin-bottom: 17px" class="mt-2 fw-bold text-white">PLATE NUMBER</p>
                    <h2 class="card-text mb-2" style="margin-top: -20px; font-style: italic; font-size: 38px"><?= $row['vplatenum'] ?></h2>
               </div>
               <div class="card-body">
                    <form action="update_service_form.php?id=<?= $data_id; ?>&vid=<?= $vid; ?>&account_id=<?= $account_id; ?>" method="POST" enctype="multipart/form-data">
                         <div class="mb-2">
                              <div class="border border-2 border-primary-subtle py-3 mb-1">
                                   <div id="image_view" style="padding-left: 15px;"></div>
                              </div>
                              <?php if (isset($_GET['error'])) : ?>
                                   <p class="text-danger"><?php echo $_GET['error']; ?></p>
                              <?php endif ?>
                              <label style="font-size: 20px; margin-bottom: -30px; font-weight: 400" for="file_upload" class="form-label">Upload Image</label>
                              <input type="file" name="my_image" id="upload_file" onchange="getImageView(event)" class="form-control p-3">
                         </div>
                         <div class="mb-2">
                              <label style="margin-bottom: -10px; font-weight: 400; font-size: 20px" for="witness" class="form-label">Address Checked</label>
                              <input type="text" class="form-control p-2 fw-bold fs-5" id="address" name="address" required>
                         </div>
                         <div class="row text-center">
                              <div class="col-6 mb-2">
                                   <label style="margin-bottom: -10px; font-weight: 400; font-size: 17px" for="date_performed" class="form-label">Date Performed</label>
                                   <input type="date" class="form-control text-center fw-bold p-2 fs-5" id="date_performed" name="date_performed" required>
                              </div>
                              <div class="col-6 mb-2">
                                   <label style="margin-bottom: -10px; font-weight: 400; font-size: 17px" for="date_work_end" class="form-label">Date Work End</label>
                                   <input type="date" class="form-control text-center fw-bold p-2 fs-5" id="date_work_end" name="date_work_end" required>
                              </div>
                              <div class="col-6 mb-2">
                                   <label style="margin-bottom: -10px; font-weight: 400; font-size: 17px" for="departure_time" class="form-label">Departure Time</label>
                                   <input type="time" class="form-control text-center fw-bold p-2 fs-5" id="departure_time" name="departure_time" required>
                              </div>
                              <div class="col-6 mb-2">
                                   <label style="margin-bottom: -10px; font-weight: 400; font-size: 17px" for="timeof_arrival" class="form-label">Time of Arrival</label>
                                   <input type="time" class="form-control text-center fw-bold p-2 fs-5" id="timeof_arrival" name="timeof_arrival" required>
                              </div>
                              <div class="col-6 mb-2">
                                   <label style="margin-bottom: -10px; font-weight: 400; font-size: 17px" for="work_time_start" class="form-label">Work Time Start</label>
                                   <input type="time" class="form-control text-center fw-bold p-2 fs-5" id="work_time_start" name="work_time_start" required>
                              </div>
                              <div class="col-6 mb-2">
                                   <label style="margin-bottom: -10px; font-weight: 400; font-size: 17px" for="work_time_end" class="form-label">Work Time End</label>
                                   <input type="time" class="form-control text-center fw-bold p-2 fs-5" id="work_time_end" name="work_time_end" required>
                              </div>
                         </div>  
                         <div class="mb-2">
                              <label style="margin-bottom: -10px; font-weight: 400; font-size: 20px" for="coordinated_by" class="form-label">Coordinated by</label>
                              <input type="text" class="form-control fw-bold  fs-5 p-2" id="coordinated_by" name="coordinated_by" required>
                         </div>
                         <div class="mb-2">
                              <label style="margin-bottom: -10px; font-weight: 400; font-size: 20px" for="checked_by" class="form-label">Checked by</label>
                              <input type="text" class="form-control fw-bold  fs-5 p-2" id="checked_by" name="checked_by" required>
                         </div>
                         <div class="mb-2">
                              <label style="margin-bottom: -10px; font-weight: 400; font-size: 20px" for="contact_num" class="form-label">Contact Number</label>
                              <input type="number" class="form-control fw-bold  fs-5 p-2" id="mobile" name="contact_num" required>
                              <span class="error text-danger fs-6" id="mobile_err"></span>  
                         </div>
                         <div class="mb-2">
                              <label style="margin-bottom: -10px; font-weight: 400; font-size: 20px" for="reason_for_offline" class="form-label">Reason For Offline</label>
                              <input type="text" class="form-control fw-bold  fs-5 p-2" id="reason_for_offline" name="reason_for_offline" value="<?= (isset($row['reason_offline'])) ? $row['reason_offline'] : ''; ?>" readonly required>
                         </div>
                         <div class="mb-2">
                              <label style="margin-bottom: -10px; font-weight: 400; font-size: 20px" for="cause_of_offline" class="form-label">Cause of Offline</label>
                              <input type="text" class="form-control fw-bold  fs-5 p-2" id="cause_of_offline" name="cause_of_offline" value="<?= (isset($row['cause_offline'])) ? $row['cause_offline'] : ''; ?>" readonly required>
                         </div>
                         <div class="mb-3">
                              <label style="margin-bottom: -10px; font-weight: 200; font-size: 20px" for="arrival_time"><b>Action Taken</b></label>
                              <select class="form-select p-2 fw-bold fs-5" aria-label="Default select example" name="action_taken"  required>
                                   <option selected disabled value=""><?= $row['action_taken']; ?></option>
                                   <?php
                                   while ($data = $result_action_taken->fetch_assoc()) { ?>
                                        <option value="<?= $data['action_id'] . $data['action_taken_name']; ?>"><?= $data['action_taken_name']; ?></option>
                                   <?php } ?>
                              </select>
                         </div>
                         <div class="d-grid col-6 mx-auto">
                              <input type="submit" name="submit_button" class="btn btn-outline-primary btn-lg mt-2" value="Update"  onclick="return confirm('Are you sure you want to submit this form?');">
                         </div>
                    </form>     
               </div>
          </div>
     </div>
     </div>
     <script type="text/javascript">
          // Function to display the image
          function getImageView(event) {
               var image = URL.createObjectURL(event.target.files[0]);
               var imgDiv = document.getElementById('image_view');
               var newImg = document.createElement('img');
               imgDiv.innerHTML = '';
               newImg.src = image;
               newImg.width = 270;
               newImg.height = 150;
               imgDiv.appendChild(newImg);
          }
          // Display the default image preview
          $('#image_view').html('<img src="https://drive.google.com/uc?id=1HzXm5FjqdWw6wQgl-At8pBwGZDDPdHJ6" width="150" height="150" style= "margin-left:60px;"/>');

          // Validation for cellphone number
          $('#mobile').on('input', function () {
               checkmobile();
          });

          function checkmobile() {
          if (!$.isNumeric($("#mobile").val())) {
               $("#mobile_err").html("Only number is allowed");
               return false;
          } else if ($("#mobile").val().length != 11) {
               $("#mobile_err").html("11 digit required!");
               return false;
          }else {
               $("#mobile_err").html("");
               return true;
               }
          }
     </script>
</body>

</html>