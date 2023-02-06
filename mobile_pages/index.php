<!doctype html>
<html lang="en">
<body class="p-3 m-0 border-0 bd-example bgcolor">
  <!-- Content -->
  <div id="dashboard-mobile" class="container">
    <p class="fs-5 text-black mt-4">Home / <span class="text-secondary">Dashboard</span></p>
    <div class="row">
      <div class="col-12 col-lg-4">
        <div class="card mb-2 border-0">
          <div class="card-body">
            <div class="p-2">
              <?php
              if ($offline > 0) { ?>
                <p class="num fs-1" data-val="<?= $offline ?>" style="margin-left: 18px"></p>
              <?php } else { ?>
                <p class="fs-1" style="margin-top:0px; margin-left: 20px">0</p>
              <?php } ?>
              <h6 class="fw-bold" style="margin-top:-25px; margin-left: 20px">Total Service Task</h6>
            </div>
            <img style="height: 90%; width: 30%; position:absolute; margin-left: 199px; margin-top: -90px" src="uploads/service-task.png" alt="4014703-driver-mobile-phone-repair-screw-service-wrench-112878" alt="">
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-4 mt-2">
        <div class="card mb-2 border-0 ">
          <div class="card-body">
            <div class="p-2">
              <?php
              if (mysqli_num_rows($query_run_all_pending_task) > 0) { ?>
                <p class="num fs-1" data-val="<?= mysqli_num_rows($query_run_all_pending_task) ?>" style="margin-left: 18px"></p>
              <?php } else { ?>
                <p class="fs-1" style="margin-top:0px; margin-left: 20px">0</p>
              <?php } ?>
              <h6 class="fw-bold" style="margin-top:-25px; margin-left: 20px">Total Pending Task</h6>
            </div>
            <img style="height: 90%; width: 30%; position:absolute; margin-left: 199px; margin-top: -90px" src="uploads/pending-task.png" alt="4014703-driver-mobile-phone-repair-screw-service-wrench-112878" alt="">
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-4 mt-2">
        <div class="card mb-2 border-0 ">
          <div class="card-body">
            <div class="p-2">
              <?php
              if (mysqli_num_rows($query_run_all_finished_task) > 0) { ?>
                <p class="num fs-1" data-val="<?= mysqli_num_rows($query_run_all_finished_task) ?>" style="margin-left: 18px"></p>
              <?php } else { ?>
                <p class="fs-1" style="margin-top:0px; margin-left: 20px">0</p>
              <?php } ?>
              <h6 class="fw-bold" style="margin-top:-25px; margin-left: 20px">Total Finished Task</h6>
            </div>
            <img style="height: 90%; width: 30%; position:absolute; margin-left: 205px; margin-top: -90px" src="uploads/finished-task.png" alt="4014703-driver-mobile-phone-repair-screw-service-wrench-112878" alt="">
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="script/count.js"></script>
</body>

</html>