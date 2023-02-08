<?php
include('../session.php');
include('../session_admin.php');
include 'connection.php';
// count all pending services where status is unfinished
$pending_services_query = "SELECT task_status
                           FROM tbl_trblesht_report
                           WHERE task_status = 'Unfinished'";
$result_pending_services = mysqli_query($dbc, $pending_services_query);
$count_pending_services = mysqli_num_rows($result_pending_services);

// count all troubleshooted where status is finished and fixed
$troubleshooted_query = "SELECT task_status
                         FROM tbl_trblesht_report
                         WHERE task_status = 'Finished'
                         AND troubleshooting_status = 'Fixed'";
$result_troubleshooted = mysqli_query($dbc, $troubleshooted_query);
$count_troubleshooted = mysqli_num_rows($result_troubleshooted);
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../admin/layouts/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../admin/layouts/css/style.css">
  <link rel="stylesheet" href="../admin/layouts/css/content.css">
  <link rel="stylesheet" href="../admin/layouts/font-aws/css/all.csS" />
  <script src="../admin/layouts/bootstrap/js/bootstrap.min.js"></script>
  <title>TR-Admin</title>
</head>

<body>
  <!-- navbar -->
  <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4 d-flex justify-content-start">
    <div class="container-fluid">
      <a class="navbar-brand" href="../admin/index.php"><span style="color: #ff0000">TSR</span>-ADMIN</a>
      <button class="navbar-toggler border-dark" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fa-solid fa-bars fa-xl "></i>
      </button>
    </div>
  </nav>
  <!-- navbar -->

  <!-- Sidebar Menu -->
  <div class="container-fluid">
    <div class="row">
      <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
        <div class="position-sticky">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link active s-font" aria-current="page" href="../admin/index.php">
                <i class="fa-solid fa-house"></i>
                Home
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link s-font" href="../admin/user-list/index.php">
                <i class="fa-solid fa-user-plus"></i>
                Users
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link s-font" href="../admin/offline-reason/index.php">
                <i class="fa-solid fa-circle-exclamation"></i>
                Offline Reason
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link s-font" href="../admin/offline-cause/index.php">
                <i class="fa-solid fa-circle-xmark"></i>
                Offline Cause
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link s-font" href="../admin/action-taken/index.php">
                <i class="fa-solid fa-hands-praying"></i>
                Action Taken
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link s-font" href="logout.php" onclick="return confirm('Are you sure you want to logout?');">
                <i class="fa-solid fa-right-from-bracket"></i>
                Sign out
              </a>
            </li>
          </ul>
        </div>
      </nav>
      <!-- Sidebar Menu -->

      <!-- Main -->
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4  main">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2  border-bottom">
          <h2 class="s-header">DASHBOARD</h2>
          <div class="btn-toolbar mb-2 mb-md-0">
          </div>
        </div>
        <div class="mt-3">
          <div class="row">
            <!-- 1st card -->
            <div class="col-sm-12 col-md-6 col-lg-3 mb-4">
              <div class="card text-center bx-shadow">
                <div class="card-header text-light g-head">
                  <div class="row align-items-center">
                    <div class="col">
                      <i class="fa-solid fa-car fa-4x"></i>
                    </div>
                    <div class="col">
                      <?php if (mysqli_num_rows($result_pending_services) > 0) {
                      ?>
                        <span class="count display-2 f-weight" data-val="<?= $count_pending_services ?>"></span>
                      <?php } else {
                      ?>
                        <span class="display-2 f-weight">0</span>
                      <?php } ?>
                      <p>ONLINE VEHICLES</p>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <a href="../admin/home/online_vehicles.php" class="btn btn-light w-100">View Details <i class="fa-solid fa-circle-arrow-right"></i></a>
                </div>
              </div>
            </div>
            <!-- 1st card -->

            <!-- 2nd card -->
            <div class="col-sm-12 col-md-6 col-lg-3 mb-4">
              <div class="card text-center bx-shadow">
                <div class="card-header text-light r-head">
                  <div class="row align-items-center">
                    <div class="col">
                      <i class="fa-solid fa-car fa-4x"></i>
                    </div>
                    <div class="col">
                      <?php if (mysqli_num_rows($result_troubleshooted) > 0) {
                      ?>
                        <span class="count display-2 f-weight" data-val="<?= $count_troubleshooted ?>"></span>
                      <?php } else {
                      ?>
                        <span class="display-2 f-weight">0</span>
                      <?php } ?>
                      <p>OFFLINE VEHILCES</p>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <a href="../admin/home/offline_vehicles.php" class="btn btn-light w-100">View Details <i class="fa-solid fa-circle-arrow-right"></i></a>
                </div>
              </div>
            </div>
            <!-- 2nd card -->

            <!-- 3rd card -->
            <div class="col-sm-12 col-md-6 col-lg-3 mb-4">
              <div class="card text-center bx-shadow">
                <div class="card-header text-light v-head">
                  <div class="row align-items-center">
                    <div class="col">
                      <i class="fa-solid fa-list-check fa-4x"></i>
                    </div>
                    <div class="col">
                      <?php if (mysqli_num_rows($result_pending_services) > 0) {
                      ?>
                        <span class="count display-2 f-weight" data-val="<?= $count_pending_services ?>"></span>
                      <?php } else {
                      ?>
                        <span class="display-2 f-weight">0</span>
                      <?php } ?>
                      <p>PENDING SERVICES</p>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <a href="../admin/home/pending_services.php" class="btn btn-light w-100">View Details <i class="fa-solid fa-circle-arrow-right"></i></a>
                </div>
              </div>
            </div>
            <!-- 3rd card -->

            <!-- 4th card -->
            <div class="col-sm-12 col-md-6 col-lg-3 mb-4">
              <div class="card text-center bx-shadow">
                <div class="card-header text-light b-head">
                  <div class="row align-items-center">
                    <div class="col">
                      <i class="fa-solid fa-screwdriver-wrench fa-4x"></i>
                    </div>
                    <div class="col">
                     <?php if (mysqli_num_rows($result_troubleshooted) > 0) {
                      ?>
                        <span class="count display-2 f-weight" data-val="<?= $count_troubleshooted ?>"></span>
                      <?php } else {
                      ?>
                        <span class="display-2 f-weight">0</span>
                      <?php } ?>
                      <p>TROUBLSHOOTED</p>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <a href="../admin/home/troubleshooted.php" class="btn btn-light w-100">View Details <i class="fa-solid fa-circle-arrow-right"></i></a>
                </div>
              </div>
            </div>
            <!-- 4th card -->
          </div>
        </div>
      </main>
    </div>
  </div>
  <script>
    let valueDisplays = document.querySelectorAll(".count");
    let interval = 200;

    valueDisplays.forEach(valueDisplay => {
      let startValue = 0;
      let endValue = parseInt(valueDisplay.getAttribute("data-val"));
      let duration = Math.floor(interval / endValue);

      let counter = setInterval(function() {
        startValue += 1;
        valueDisplay.textContent = startValue;
        if (startValue == endValue) {
          clearInterval(counter);
        }
      }, duration);
    });
  </script>
</body>

</html>