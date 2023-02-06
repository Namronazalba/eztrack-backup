<?php
if (!isset($_SESSION['username'])) {
     header("Location: login.php");
     exit;
}

// SESSION USER ID AND USERNAME
$user_id = $_SESSION['user_id'];
$user_technician_name = $_SESSION['username'];

$num_per_page = 5;

if (isset($_GET['history'])) {
     $page_task_history = $_GET['history'];
} else {
     $page_task_history = 1;
}

if (isset($_GET['platenum'])) {
     $platenum = $_GET['platenum'];
} else {
     $platenum = '';
}

if (isset($_GET['vid']) && isset($_GET['account_id'])) {
     $vid = $_GET['vid'];
     $account_id = $_GET['account_id'];
}

$star_from_page_task_history = ($page_task_history - 1) * 5;

// QUERY TROUBLESHOOT REPORT WITH LIMIT
$query_done_task = "SELECT * FROM tbl_trblesht_report
    LEFT JOIN tbl_cvehicles
    ON tbl_trblesht_report.vid = tbl_cvehicles.vid
    WHERE tbl_trblesht_report.vid = $vid AND technician_id = $user_id
    ORDER BY id DESC
    LIMIT $star_from_page_task_history, $num_per_page";

$query_run = mysqli_query($dbc, $query_done_task);

//QUERY ALL TROUBLESHOOT REPORT
$query_all_task_done = "SELECT * FROM tbl_trblesht_report
    LEFT JOIN tbl_cvehicles
    ON tbl_trblesht_report.vid = tbl_cvehicles.vid
    WHERE tbl_trblesht_report.vid = $vid AND technician_id = $user_id 
    ORDER BY id DESC";

$query_run_all_task_done = mysqli_query($dbc, $query_all_task_done);


if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
     $from_date = $_GET['from_date'];
     $to_date = $_GET['to_date'];

     if (isset($_GET['history-search'])) {
          $page_task_history_search = $_GET['history-search'];
     } else {
          $page_task_history_search = 1;
     }
     $star_from_page_task_history_search = ($page_task_history_search - 1) * 5;
     $num_per_page_in_search = 5;
     // QUERY TROUBLESHOOT REPORT DEPENDING ON DATE RANGE WITH LIMIT
     $query_run_date_report = "SELECT * FROM tbl_trblesht_report
    LEFT JOIN tbl_cvehicles
    ON tbl_trblesht_report.vid = tbl_cvehicles.vid
    WHERE date_performed BETWEEN '$from_date' AND '$to_date'
    AND technician_id = $user_id AND tbl_trblesht_report.vid = $vid
    ORDER BY id DESC
    LIMIT $star_from_page_task_history_search, $num_per_page_in_search
    ";
     $query_run_date = mysqli_query($dbc, $query_run_date_report);

     $query_all_task_done_search = "SELECT * FROM tbl_trblesht_report
    LEFT JOIN tbl_cvehicles
    ON tbl_trblesht_report.vid = tbl_cvehicles.vid
    WHERE date_performed BETWEEN '$from_date' AND '$to_date'
    AND tbl_trblesht_report.vid = $vid AND technician_id = $user_id 
    ORDER BY id DESC";

     $query_run_all_task_done_search = mysqli_query($dbc, $query_all_task_done_search);
}
?>
<!doctype html>
<html lang="en">
<style>
     img {
          width: 80px;
          height: 80px;
     }

     @media(min-width: 576px) {
          .finished-task-record-mobile {
               display: none;
          }
     }
</style>

<body class="p-3 m-0 border-0 bd-example bgcolor">
     <div class="finished-task-record-mobile">
     <p class="fs-5 text-black mt-4">Finished Task / <span class="text-secondary">Records</span></p>
          <div class="card mb-1 px-4">
               <!-- Search bar-->
               <div class="container my-3">
                    <div class="row">
                         <div class="col-12 mb-2">
                              <form action="#" method="GET">
                                   <input type="hidden" name="vid" value="<?= (isset($_GET['vid'])) ? $_GET['vid'] : '' ?>">
                                   <input type="hidden" name="account_id" value="<?= (isset($_GET['account_id'])) ? $_GET['account_id'] : '' ?>">
                                   <label class="fs-5 fw-bold">Start Date</label>
                                   <input type="date" name="from_date" class="form-control text-center fs-4 fw-bold" value="<?= (isset($_GET['from_date'])) ? $_GET['from_date'] : '' ?>" required>
                         </div>
                         <div class="col-12">
                              <label class="fs-5 fw-bold">To Date</label>
                              <div class="input-group">
                                   <input type="date" name="to_date" class="form-control text-center fs-4 fw-bold" value="<?= (isset($_GET['to_date'])) ? $_GET['to_date'] : '' ?>" required>
                              </div>
                              <div class="row mt-3">
                                   <div class="d-grid col-6">
                                        <button type="submit" class="btn btn-primary me-md-2" name="submit-button">Search</button>
                                   </div>
                                   <div class="d-grid col-6">
                                        <a href="finished_task_record.php?vid=<?= $vid ?>&account_id=<?= $account_id; ?>" class="btn btn-warning text-white" style="text-decoration: none;">Reset</a>
                                   </div>
                              </div>
                         </div>
                         </form>
                    </div>
               </div>
               <!-- Search bar-->
          </div>
          <!-- Table -->
          <?php
          // GET ALL REPORT WITH ACCOUNT ID AND PLATE NUMBER
          if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {  ?>
               <?php
               if (mysqli_num_rows($query_run_date) > 0) { ?>
                    <table class="table table-striped table-hover text-center" style="box-shadow: 0px 3px 11px rgba(0, 0, 0, 0.25);">
                         <thead style="background: #7280A5;">
                              <tr class="text-white">
                                   <th>Date Checked</th>
                                   <th>Reason of Offline</th>
                                   <th>Cause of Offline</th>
                                   <th>Action Taken</th>
                                   <th>Troubleshooting Status</th>
                              </tr>
                         </thead>
                         <tbody class="font-monospace" style="vertical-align:middle">
                              <?php
                              while ($row = $query_run_date->fetch_assoc()) { ?>
                                   <tr>
                                        <td><?= $row['date_performed']; ?></td>
                                        <td><?= $row['reason_offline']; ?></td>
                                        <td><?= $row['cause_offline']; ?></td>
                                        <td><?= $row['action_taken']; ?></td>
                                        <td><?= $row['troubleshooting_status']; ?></td>
                                   </tr>
                              <?php } ?>
                         </tbody>
                    </table>
                    <div class="pagination mt-3">
                         <?php

                         if (mysqli_num_rows($query_run_all_task_done_search) > 0) {
                              $records = mysqli_num_rows($query_run_all_task_done_search);
                              $limit = 5;
                              $pages = ceil($records / $limit);
                         } else {
                              $pages = '';
                         }

                         $start = ($page_task_history_search - 2);
                         switch ($start) {
                              case 0:
                                   $start = 1;
                              case -1:
                                   $start = 1;
                         }
                         $end = ($page_task_history_search + 2);
                         if ($end > $pages) {
                              $end = $pages;
                         }

                         echo "<ul style='padding-left: 0;'>";
                         if ($page_task_history_search >= 2) {
                              echo "<a href='finished_task_record.php?history-search=1&vid=" . $vid . "&account_id=" . $account_id . "&from_date=" . $from_date . "&to_date=" . $to_date . "'>First</a>";
                              echo "<a href='finished_task_record.php?history-search=" . ($page_task_history - 1) . "&vid=" . $vid . "&account_id=" . $account_id . "&from_date=" . $from_date . "&to_date=" . $to_date . "'>&laquo;</a>";
                         }

                         for ($i = $start; $i <= $end; $i++) {
                              if ($i == $page_task_history_search) {
                                   $act_dect = "active";
                              } else {
                                   $act_dect = "";
                              }


                              if ($end == 1) {
                                   echo "<a href='finished_task_record.php?history-search=" . $i . "&vid=" . $vid . "&account_id=" . $account_id . "&from_date=" . $from_date . "&to_date=" . $to_date . "' style='display: none;'>" . $i . "</a>";
                              } else {
                                   echo "<a href='finished_task_record.php?history-search=" . $i . "&vid=" . $vid . "&account_id=" . $account_id . "&from_date=" . $from_date . "&to_date=" . $to_date . "' class=" . $act_dect . ">" . $i . "</a>";
                              }
                         }
                         if ($page_task_history_search < $pages) {
                              echo "<a href='finished_task_record.php?history-search=" . ($page_task_history_search + 1) . "&vid=" . $vid . "&account_id=" . $account_id . "&from_date=" . $from_date . "&to_date=" . $to_date . "' class=" . $act_dect . ">&raquo;</a>";
                              echo "<a href='finished_task_record.php?history-search=" . $pages . "&vid=" . $vid . "&account_id=" . $account_id . "&from_date=" . $from_date . "&to_date=" . $to_date . "' class=" . $act_dect . ">Last</a>";
                         }

                         if ($pages > 1) {
                              echo "<br><br>";
                              echo "<li style='list-style: none;'>" . $page_task_history_search . "&nbsp;of&nbsp;" . $pages . "&nbsp;Pages</li>";
                         } ?>
                    <?php } else { ?>
                         <table class="table table-striped table-hover text-center" style="box-shadow: 0px 3px 11px rgba(0, 0, 0, 0.25);">
                              <tr class='text-white' style="height:40px;background-color: #7280A5;font-weight: 600;">
                                   <th style='display:none'>VID</th>
                                   <th style='width:200px; color:white;'>Date Checked</th>
                                   <th style='width:200px; color:white;'>Image</th>
                                   <th style='width:300px; color:white;'>Troubleshooting Status</th>
                              </tr>
                              <tr>
                                   <td colspan=4 style='text-align:center'>No Data!</td>
                              </tr>
                         </table>
                    <?php } ?>
                    </tbody>
                    </table>
               <?php } else { ?>
                    <?php
                    // GET ALL REPORT
                    if (!isset($_GET['from_date']) && !isset($_GET['to_date'])) { ?>
                         <?php
                         if (mysqli_num_rows($query_run) > 0) { ?>
                         <div class="table-responsive">
                              <table class="table table-responsive table-striped table-hover text-center" style="box-shadow: 0px 3px 11px rgba(0, 0, 0, 0.25)">
                                   <thead style="background: #7280A5;">
                                        <tr class="text-white" style="font-size: 15px;">
                                             <th style="padding-bottom: 24px; line-height:1">PLATE NUMBER</th>
                                             <th style="padding-bottom: 24px; line-height:1">DATE CHECKED</th>
                                             <th style="padding-bottom: 19px; line-height:1; padding-top:18px">REASON FOR OFFLINE</th>
                                             <th style="padding-bottom: 19px; line-height:1">CAUSE OF OFFLINE</th>
                                             <th style="padding-bottom: 24px; line-height:1">ACTION TAKEN</th>
                                             <th style="padding-bottom: 30px; line-height:1">IMAGE</th>
                                             <th style="padding-bottom: 19px; line-height:1">TROUBLESHOOTING REPORT</th>
                                        </tr>
                                   </thead>
                                   <tbody class="font-monospace" style="vertical-align:middle">
                                        <?php
                                        while ($row = $query_run->fetch_assoc()) { ?>
                                             <tr class="fw-bold" style="font-size: 13px;width: 100px;">
                                                  <td><?= $row['vplatenum']; ?></td>
                                                  <td><?= $row['date_performed']; ?></td>
                                                  <td><?= $row['reason_offline']; ?></td>
                                                  <td><?= $row['cause_offline']; ?></td>
                                                  <td><?= $row['action_taken']; ?></td>
                                                  <td><img src="uploads/<?= $row['image_url'] ?>"></td>
                                                  <td><?= $row['troubleshooting_status']; ?></td>
                                             </tr>
                                        <?php } ?>
                                   <?php } else { ?>
                                        <table class="table table-responsive table-striped table-hover text-center" style="box-shadow: 0px 3px 11px rgba(0, 0, 0, 0.25);">
                                             <tr class='text-white' style="height:20px;background-color: #7280A5;font-weight: 600;">
                                                  <th style='display:none'>VID</th>
                                                  <th style='width:200px; color:white;'>Plate #</th>
                                                  <th style='width:300px; color:white;'>Status</th>
                                                  <th style='width:200px; color:white;'>Date Checked</th>
                                             </tr>
                                             <tr>
                                                  <td colspan=4 style='text-align:center'>No Data!</td>
                                             </tr>
                                        </table>
                                   <?php } ?>
                                   </tbody>
                              </table>
                              </div>
                              <!-- Table -->

                              <!-- pagination -->
                              <div class="pagination mt-3">
                                   <?php

                                   if (mysqli_num_rows($query_run_all_task_done) > 0) {
                                        $records = mysqli_num_rows($query_run_all_task_done);
                                        $limit = 5;
                                        $pages = ceil($records / $limit);
                                   } else {
                                        $pages = '';
                                   }

                                   $start = ($page_task_history - 2);
                                   switch ($start) {
                                        case 0:
                                             $start = 1;
                                        case -1:
                                             $start = 1;
                                   }
                                   $end = ($page_task_history + 2);
                                   if ($end > $pages) {
                                        $end = $pages;
                                   }

                                   echo "<ul style='padding-left: 0;'>";
                                   if ($page_task_history >= 2) {
                                        echo "<a href='finished_task_record.php?history=1&vid=" . $vid . "&account_id=" . $account_id . "'>First</a>";
                                        echo "<a href='finished_task_record.php?history=" . ($page_task_history - 1) . "&vid=" . $vid . "&account_id=" . $account_id . "'>&laquo;</a>";
                                   }

                                   for ($i = $start; $i <= $end; $i++) {
                                        if ($i == $page_task_history) {
                                             $act_dect = "active";
                                        } else {
                                             $act_dect = "";
                                        }


                                        if ($end == 1) {
                                             echo "<a href='finished_task_record.php?history=" . $i . "&vid=" . $vid . "&account_id=" . $account_id . "' style='display: none;'>" . $i . "</a>";
                                        } else {
                                             echo "<a href='finished_task_record.php?history=" . $i . "&vid=" . $vid . "&account_id=" . $account_id . "' class=" . $act_dect . ">" . $i . "</a>";
                                        }
                                   }
                                   if ($page_task_history < $pages) {
                                        echo "<a href='finished_task_record.php?history=" . ($page_task_history + 1) . "&vid=" . $vid . "&account_id=" . $account_id . "' class=" . $act_dect . ">&raquo;</a>";
                                        echo "<a href='finished_task_record.php?history=" . $pages . "&vid=" . $vid . "&account_id=" . $account_id . "' class=" . $act_dect . ">Last</a>";
                                   }

                                   if ($pages > 1) {
                                        echo "<br><br>";
                                        echo "<li style='list-style: none;'>" . $page_task_history . "&nbsp;of&nbsp;" . $pages . "&nbsp;Pages</li>";
                                   }
                                   echo "</ul>";
                                   echo "</div>";
                                   echo "</div>";
                              } else { ?>
                                   <table class="table table-striped table-hover text-center mb-5" style="box-shadow: 0px 3px 11px rgba(0, 0, 0, 0.25);">
                                        <tr class='text-white' style="height:40px;background-color: #7280A5;font-weight: 600">
                                             <th style='display:none'>VID</th>
                                             <th style='width:200px; color:white;'>Plate #</th>
                                             <th style='width:300px; color:white;'>Status</th>
                                             <th style='width:200px; color:white;'>Date Checked</th>
                                        </tr>
                                        <tr>
                                             <td class="text-danger" colspan=4 style='text-align:center'>No Data</td>
                                        </tr>
                                   </table>

                              <?php  } ?>
                         <?php } ?>

                              </div>
                    </div>
</body>

</html>