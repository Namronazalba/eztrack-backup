<!doctype html>
<html lang="en">
<!-- head -->
<?php include 'head.php'; ?>
<body class="p-3 m-0 border-0 bd-example bgcolor">
    <!-- content -->
    <div class="travelling-mobile-container container">
    <p class="mt-4 fs-5"">Service Task / <span class="text-secondary">Travelling</span></p>
        <div class="card" style="box-shadow: 0px 3px 11px rgba(0, 0, 0, 0.25);">
            <div class="card-header text-white text-center" style="background: #7280A5; border-radius: 5px 5px 20px 20px;">
                <p style="font-size:14px; margin-bottom: 17px" class="mt-2 fw-bold text-white">PLATE NUMBER</p>
                <h2 class="card-text mb-2" style="margin-top: -20px; font-style: italic; font-size: 38px"><?= $datas['vplatenum']?></h2>
            </div>
            <div class="card-body">
                <!-- form -->
                <form method="post">
                    <div class="form-group mb-3 mt-3">
                        <label style="margin-bottom: -20px; font-weight: 400;" for="date_start" class="form-label fs-3">Start Date</label>
                        <input type="date" id="date_start" name="date_start" class="form-control p-1 fs-2 fw-bold text-center <?php echo (!empty($date_start_err)) ? 'is-invalid' : ''; ?>">
                        <span style="margin-top:-2px" class="invalid-feedback fs-5"><?php echo $date_start_err;?></span>
                    </div>
                    <div class="form-group mb-3">
                        <label style="margin-bottom: -20px; font-weight: 400;" for="departure_time" class="form-label fs-3">Departure Time</label>
                        <input type="time" id="departure_time" name="departure_time" class="form-control p-1 fs-2 fw-bold text-center  <?php echo (!empty($departure_time_err)) ? 'is-invalid' : ''; ?>">
                        <span style="margin-top:-2px" class="invalid-feedback fs-5"><?php echo $departure_time_err;?></span>
                        <input type="hidden" name="vid" value="<?= (isset($_GET['vid'])) ? $_GET['vid'] : '' ?>">
                        <input type="hidden" name="user_id" value="<?= (isset($_SESSION['user_id'])) ? $_SESSION['user_id'] : '' ?>">
                    </div>
                    <div class="d-grid col-6 mx-auto">
                        <button class="btn btn-outline-primary btn-lg mt-2" type="Save" name="submit" onclick="return confirm('Are you sure you want to submit this form?');">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>