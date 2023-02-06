<?php
require('conection.php');
session_start();
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

    // QUERY TROUBLESHOOT REPORT DEPENDING ON DATE RANGE WITH LIMIT
    $query_run_date_report = "SELECT * FROM tbl_trblesht_report
    LEFT JOIN tbl_cvehicles
    ON tbl_trblesht_report.vid = tbl_cvehicles.vid
    WHERE date_performed BETWEEN '$from_date' AND '$to_date'
    AND technician_id = $user_id AND tbl_trblesht_report.vid = $vid";
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

<!-- head -->
<?php include 'head.php'; ?>
<style>
    img {
        width: 100px;
        height: 100px;
    }

    @media(max-width: 576px) {
        .finished-task-record-desktop {
            display: none;
        }
    }
</style>

<body class="p-3 m-0 border-0 bd-example bgcolor">
    <!-- navbar -->
    <?php include 'navbar.php'; ?>

    <div class="finished-task-record-desktop container">
        <p class="fs-5 text-black mt-4">Finished Task / <span class="text-secondary">Record</span></p>
        <div class="card mb-2 mt-3 shadow-sm px-5 pt-5 pb-3">
            <!-- Search bar-->
            <div class="container">
                <div class="row align-items-start mb-4">
                    <form action="" method="GET">
                        <div class="row">
                            <div class="col-6">
                                <input type="hidden" name="vid" value="<?= (isset($_GET['vid'])) ? $_GET['vid'] : '' ?>">
                                <input type="hidden" name="account_id" value="<?= (isset($_GET['account_id'])) ? $_GET['account_id'] : '' ?>">
                                <label class="mb-2"><b>From Date:</b></label>
                                <input type="date" name="from_date" class="form-control" value="<?= (isset($_GET['from_date'])) ? $_GET['from_date'] : '' ?>" required>
                            </div>
                            <div class="col-6">
                                <label class="mb-2"><b>From Date:</b></label>
                                <div class="input-group">
                                    <input type="date" name="to_date" class="form-control" value="<?= (isset($_GET['to_date'])) ? $_GET['to_date'] : '' ?>" required>
                                    <button type="submit" class="btn btn-primary" name="submit-button">Search</button>
                                    <a href="finished_task_record.php?vid=<?= $vid ?>&account_id=<?= $account_id; ?>" class="btn btn-warning" style="text-decoration: none;">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Search bar-->

            <!-- Table -->
            <?php

            // GET ALL REPORT WITH ACCOUNT ID AND PLATE NUMBER
            if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {  ?>
                <?php

                //ito yung table for search 
                if (mysqli_num_rows($query_run_date) > 0) { ?>
                    <table class="table table-striped table-hover text-center" style="box-shadow: 0px 3px 11px rgba(0, 0, 0, 0.25);">
                        <thead style="background: #7280A5;">
                            <tr class="text-white">
                                <th>Plate #</th>
                                <th>Date Checked</th>
                                <th>Reason of Offline</th>
                                <th>Cause of Offline</th>
                                <th>Action Taken</th>
                                <th>Image</th>
                                <th>Troubleshooting Status</th>
                            </tr>
                        </thead>
                        <tbody class="font-monospace" style="vertical-align:middle">
                            <?php
                            while ($row = $query_run_date->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= $row['vplatenum']; ?></td>
                                    <td><?= $row['date_performed']; ?></td>
                                    <td><?= $row['reason_offline']; ?></td>
                                    <td><?= $row['cause_offline']; ?></td>
                                    <td><?= $row['action_taken']; ?></td>
                                    <td><img src="uploads/<?= $row['image_url'] ?>"></td>
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
                                <th style='width:200px; color:white;'>Plate #</th>
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
                            <table class="table table-striped table-hover text-center" style="box-shadow: 0px 3px 11px rgba(0, 0, 0, 0.25);">
                                <thead style="background: #7280A5;">
                                    <tr class="text-white">
                                        <th>Plate #</th>
                                        <th>Date Checked</th>
                                        <th>Reason for Offline</th>
                                        <th>Cause of Offline</th>
                                        <th>Action Taken</th>
                                        <th>Image</th>
                                        <th>Troubleshooting Status</th>
                                    </tr>
                                </thead>
                                <tbody class="font-monospace" style="vertical-align:middle">
                                    <?php
                                    while ($row = $query_run->fetch_assoc()) { ?>
                                        <!-- ito yung index display  -->
                                        <tr class="fw-bold">
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
                                    <table class="table table-striped table-hover text-center" style="box-shadow: 0px 3px 11px rgba(0, 0, 0, 0.25);">
                                        <tr class='text-white' style="height:40px;background-color: #7280A5;font-weight: 600;">
                                            <th style='display:none'>VID</th>
                                            <th style='width:200px; color:white;'>Plate #</th>
                                            <th style='width:300px; color:white;'>Status</th>
                                            <th style='width:200px; color:white;'>Date Checked</th>
                                            <th style='width:200px; color:white;'>Image</th>
                                        </tr>
                                        <tr>
                                            <td colspan=4 style='text-align:center'>No Data!</td>
                                        </tr>
                                    </table>
                                <?php } ?>
                                </tbody>
                            </table>
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
                                        <th style='width:200px; color:white;'>Image</th>
                                    </tr>
                                    <tr>
                                        <td colspan=4 style='text-align:center'>No Data!</td>
                                    </tr>
                                </table>

                            <?php  } ?>
                        <?php } ?>

                            </div>
                    </div>
        </div>

        <?php include 'mobile_pages/finished_task_record.php' ?>
</body>

</html>