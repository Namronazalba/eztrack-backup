<?php
include('session.php');
?>
<!doctype html>
<html lang="en">
<style>
    #preview {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        min-height: 35vh;
    }
</style>
<!-- head -->
<?php include 'head.php'; ?>

<body class="p-3 m-0 border-0 bd-example bgcolor">
    <!-- navbar -->
    <?php include 'navbar.php'; ?>
    <!-- content -->
    <div id="trouble-shooting-desktop" class="container">
    <p class="fs-5 text-black mt-4">Service Task / <span class="text-secondary">Troubleshooting</span></p>
        <?php
        require_once("conection.php");
        if (isset($_GET['vid'])) {
            $vid = $_GET['vid'];
            $query_detail = "SELECT * FROM tbl_cvehicles WHERE vid = $vid";
            $result = mysqli_query($dbc, $query_detail);
            $data = mysqli_fetch_array($result);
        }

        if (isset($_GET['account_id'])){
            $account_id = $_GET['account_id'];
        }

        ?>
        <div class="card" style="box-shadow: 0px 3px 11px rgba(0, 0, 0, 0.25);">
            <div class="card-header text-white text-center" style="background: #7280A5; border-radius: 5px 5px 20px 20px;">
                <p class="mt-2">Plate No:</p>
                <h1 class="card-text p-2 " style="margin-top: -20px;"><?= $data['vplatenum']?></h1>
            </div>
            <div class="card-body">
                <?php
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
                WHERE cause_of_offline_id = $cause_of_offline_id";
                $result_action_taken = mysqli_query($dbc, $query_action_taken);
                ?>
                <!-- form -->
                <form method="post" action="troubleshooting_form.php?account_id=<?= $account_id; ?>&vid=<?= $vid; ?>" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label for="arrival_time" class="form-label">Date Work End</label>
                        <input type="date" id="arrival_time" name="date_end" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="time_end" class="form-label">Work Time End</label>
                        <input type="time" id="time_end" name="time_end" class="form-control " required>
                    </div>
                    <div class="mt-3 mb-3">
                        <label for="arrival_time" class="form-label">Action Taken</label>
                        <select class="form-select" aria-label="Default select example" name="action_taken" id="action_taken" value="<?= (isset($_POST['action_taken'])) ? $_POST['action_taken'] : '' ?>" required>
                            <option selected disabled value="">Select Action</option>
                            <?php
                            while ($row = $result_action_taken->fetch_assoc()) { ?>
                                <option value="<?= $row['action_id'] . $row['action_taken_name']; ?>"><?= $row['action_taken_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div id="preview" style="display: flex; align-items: center;"></div>
                        <?php if (isset($_GET['error'])) : ?>
                            <p><?php echo $_GET['error']; ?></p>
                        <?php endif ?>
                        <label for="file_upload" class="form-label">Upload Image</label>
                        <input type="file" name="my_image" id="image" class="form-control " required>
                    </div>
                    <div class="mb-3">
                        <label for="witness" class="form-label">Checked/Witness By</label>
                        <input type="text" class="form-control" id="witness" name="witness" required>
                    </div>
                    <div class="mb-3">
                        <label for="contact_no" class="form-label">Contact No.</label>
                        <input type="number" class="form-control" id="contact_no" name="contact" maxlength="11" required>
                    </div>
                    <div class="mb-3">
                        <label for="coordinated_by" class="form-label">Coordinated By</label>
                        <input type="text" class="form-control" id="coordinated_by" name="coordinated_by" required>
                    </div>
                    <input type="submit" name="submit" value="Save" class="btn btn-success mt-3">
                </form>
            </div>
        </div>
    </div>
    <?php include_once 'mobile_pages/troubleshooting.php' ?>
</body>

</html>
<script>
    document.querySelectorAll('input[type="number"]').forEach(input =>{
        input.oninput = () =>{
            if(input.value.length > input.maxLength) input.value = input.value.slice(0,input.maxLength);
        }
    })
</script>
<script src="script/script.js"></script>