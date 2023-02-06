<?php
// Connect to the database
require_once("conection.php");

if (isset($_GET['vid'])){
    $vid = $_GET['vid'];
    $query_detail = "SELECT * FROM tbl_cvehicles WHERE vid = $vid";
    $result = mysqli_query($dbc, $query_detail);
    $fetch_data = mysqli_fetch_array($result);
}
?>
<!doctype html>
<html lang="en">
<!-- head -->
<?php include 'head.php'; ?>
<body class="p-3 m-0 border-0 bd-example bgcolor">
    <!-- content -->
    <div class="trouble-shooting-mobile container">
        <p class="mt-4 fs-5">Service Task / <span class="text-secondary">Troubleshooting</span></p>
        <div class="card" style="box-shadow: 0px 3px 11px rgba(0, 0, 0, 0.25);">
            <div class="card-header text-white text-center" style="background: #7280A5; border-radius: 5px 5px 20px 20px;">
                <p style="font-size:14px; margin-bottom: 17px" class="mt-2 fw-bold text-white">PLATE NUMBER</p>
                <h2 class="card-text mb-2" style="margin-top: -20px; font-style: italic; font-size: 38px"><?= $fetch_data['vplatenum']?></h2>
            </div>  
            <div class="card-body">
                <?php
                require_once("conection.php");
                if (isset($_GET['vid'])) {
                    $vid = $_GET['vid'];
                }
                // SESSION USER ID
                $user_id = $_SESSION['user_id'];

                // SESSION CAUSE_OF_OFFLINE_ID
                $cause_of_offline_id = $_SESSION['cause_offline_id'];

                // QUERY ACTION TAKEN
                $query_action_taken = "SELECT * FROM tbl_action_taken
                INNER JOIN tbl_pivot_cause_action 
                ON tbl_action_taken.action_id = tbl_pivot_cause_action.action_taken_id
                INNER JOIN tbl_cause_of_offline
                ON tbl_cause_of_offline.cause_id = tbl_pivot_cause_action.cause_of_offline_id
                WHERE cause_of_offline_id = $cause_of_offline_id 
                ";
                $result_action_taken = mysqli_query($dbc, $query_action_taken);
                ?>
                <!-- form -->
                <form method="post" action="troubleshooting_form.php?account_id=<?= $account_id; ?>&vid=<?= $vid; ?>" enctype="multipart/form-data" >
                    <div class="mb-2">
                        <div class="border border-2 border-primary-subtle py-3 mb-1">
                            <div id="image_preview" style="padding-left: 15px;"></div>
                        </div>
                        <?php if (isset($_GET['error'])) : ?>
                            <p class="text-danger"><?php echo $_GET['error']; ?></p>
                        <?php endif ?>
                        <label style="font-size: 20px; margin-bottom: -30px; font-weight: 400" for="file_upload" class="form-label">Upload Image</label>
                        <input type="file"  name="my_image" id="upload_file" onchange="getImagePreview(event)" class="form-control mb-2" required>
                    </div> 
                    <div class="form-group mb-1">
                        <label style="font-size: 20px; margin-bottom: -30px; font-weight: 400" for="arrival_time" class="form-label">Date Work End</label>
                        <input style="font-size: 20px;" type="date" id="arrival_time" name="date_end" class="form-control" required>
                    </div>
                    <div class="form-group mb-1">
                        <label style="font-size: 20px; margin-bottom: -30px; font-weight: 400" for="time_end" class="form-label">Work Time End</label>
                        <input style="font-size: 20px;" type="time" id="time_end" name="time_end" class="form-control " required>
                    </div>
                    <div class="mt-2 mb-1">
                        <label style="font-size: 20px; margin-bottom: -30px; font-weight: 400" for="arrival_time" class="form-label">Action Taken</label>
                        <select style="font-size: 20px;"t class="form-select" aria-label="Default select example" name="action_taken" id="action_taken"
                         value="<?=(isset($_POST['action_taken']))? $_POST['action_taken'] :'' ?>" required>
                            <option selected disabled value="">Select Action</option>
                            <?php
                            while ($row = $result_action_taken->fetch_assoc()) { ?>
                                <option value="<?= $row['action_id'].$row['action_taken_name']; ?>"><?= $row['action_taken_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-1">
                        <label style="font-size: 20px; margin-bottom: -30px; font-weight: 400" for="witness" class="form-label">Checked/Witness By</label>
                        <input style="font-size: 20px;" type="text" class="form-control" id="witness" name="witness" required>
                    </div>
                    <div class="mb-1">
                        <label style="font-size: 20px; margin-bottom: -30px; font-weight: 400" for="contact_no" class="form-label">Contact Number</label>
                        <input style="font-size: 20px;" type="number" class="form-control" id="mobile" name="contact" required>
                        <span class="error text-danger fs-6" id="mobile_err"></span>             
                    </div>
                    <div class="mb-1">
                        <label style="font-size: 20px; margin-bottom: -30px; font-weight: 400" for="coordinated_by" class="form-label">Coordinated By</label>
                        <input style="font-size: 20px;" type="text" class="form-control" id="coordinated_by" name="coordinated_by" required>               
                    </div>
                    <div class="d-grid col-6 mx-auto">
                        <input type="submit" name="submit" value="Save" class="btn btn-outline-primary btn-lg mt-2"  onclick="return confirm('Are you sure you want to save this form?');">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">

     // Function to display the image
    function getImagePreview(event) {
        var image = URL.createObjectURL(event.target.files[0]);
        var imgDiv = document.getElementById('image_preview');
        var newImg = document.createElement('img');
        imgDiv.innerHTML= '';
        newImg.src=image;
        newImg.width = 270; 
        newImg.height = 150; 
        imgDiv.appendChild(newImg);
    }
    // Display the default image preview
    $('#image_preview').html('<img src="uploads/upload.png" width="150" height="150" style= "margin-left:60px;"/>');

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
