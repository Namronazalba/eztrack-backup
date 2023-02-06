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

if (isset($_GET['account_id'])) {
    $account_id = $_GET['account_id'];

    $num_per_page = 5;

    if (isset($_GET['done-task'])) {
        $page_task_done = $_GET['done-task'];
    } else {
        $page_task_done = 1;
    }

    if (isset($_GET['platenum'])) {
        $platenum = $_GET['platenum'];
    } else {
        $platenum = '';
    }

    $start_from_page_task_done = ($page_task_done - 1) * 5;

    // QUERY TROUBLESHOOT REPORT WITH LIMIT
    $query_done_task = "SELECT * FROM tbl_trblesht_report
    LEFT JOIN tbl_cvehicles
    ON tbl_trblesht_report.vid = tbl_cvehicles.vid
    WHERE id IN (SELECT MAX(id) FROM tbl_trblesht_report WHERE task_status = 'Finished' GROUP BY vid)
    AND technician_id = $user_id AND accnt_id = $account_id
    LIMIT $start_from_page_task_done, $num_per_page";

    $query_run = mysqli_query($dbc, $query_done_task);

    //QUERY ALL TROUBLESHOOT REPORT
    $query_all_task_done = "SELECT * FROM tbl_trblesht_report
    LEFT JOIN tbl_cvehicles
    ON tbl_trblesht_report.vid = tbl_cvehicles.vid
    WHERE id IN (SELECT MAX(id) FROM tbl_trblesht_report WHERE task_status = 'Finished' GROUP BY vid)
    AND technician_id = $user_id AND accnt_id = $account_id";

    $query_run_all_task_done = mysqli_query($dbc, $query_all_task_done);
}

if (!empty($_GET['platenum']) && isset($_GET['account_id'])) {
    // QUERY 1 TROUBLESHOOT REPORT DEPENDING ON PLATE NUMBER 
    $query_one_report = "SELECT * FROM tbl_trblesht_report
    LEFT JOIN tbl_cvehicles
    ON tbl_trblesht_report.vid = tbl_cvehicles.vid
    WHERE id IN (SELECT MAX(id) FROM tbl_trblesht_report WHERE task_status = 'Finished' GROUP BY vid)
    AND technician_id = $user_id AND accnt_id = $account_id AND vplatenum LIKE '%$platenum%'";

    $query_run_one_report = mysqli_query($dbc, $query_one_report);
}
if (isset($_GET['finish-search'])) {
    $page_finish_search = $_GET['finish-search'];
} else {
    $page_finish_search = 1;
}

$start_from_page_finish = ($page_finish_search  - 1) * 5;
$num_per_page_in_search = 5;

if (isset($_GET['account_id']) && isset($_GET['from_date']) && isset($_GET['to_date'])) {
    //QUERY WITH DATE AND LIMIT
    $from_date = $_GET['from_date'];
    $to_date = $_GET['to_date'];
    $query_with_date = "SELECT * FROM tbl_trblesht_report
    LEFT JOIN tbl_cvehicles
    ON tbl_trblesht_report.vid = tbl_cvehicles.vid
    WHERE id IN (SELECT MAX(id) FROM tbl_trblesht_report WHERE task_status = 'Finished' GROUP BY vid)
    AND technician_id = $user_id AND accnt_id = $account_id AND vplatenum LIKE '%$platenum%'
    AND date_performed BETWEEN '$from_date' AND '$to_date' 
    ORDER BY id DESC
    LIMIT $start_from_page_finish, $num_per_page_in_search
    ";

    $result_with_date = mysqli_query($dbc, $query_with_date);

    //QUERY ALL RESULT WITHOUT LIMIT

    $query_all_with_date = "SELECT * FROM tbl_trblesht_report
    LEFT JOIN tbl_cvehicles
    ON tbl_trblesht_report.vid = tbl_cvehicles.vid
    WHERE id IN (SELECT MAX(id) FROM tbl_trblesht_report WHERE task_status = 'Finished' GROUP BY vid)
    AND technician_id = $user_id AND accnt_id = $account_id
    AND date_performed BETWEEN '$from_date' AND '$to_date'
    ";

    $all_result_with_date = mysqli_query($dbc, $query_all_with_date);
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
        .finished-task-desktop {
            display: none;
        }
    }
</style>

<body class="p-3 m-0 border-0 bd-example bgcolor">
    <!-- navbar -->
    <?php include 'navbar.php'; ?>
    <div class="finished-task-desktop container">
        <p class="fs-5 text-black mt-4">Finished Task / <span class="text-secondary">Complete Services</span></p>
        <div class="card mb-2 mt-3 shadow-sm px-5 pt-5 pb-3">
            <!-- Search bar-->
            <div class="container">
                <div class="row align-items-start mb-4">
                    <div class="col">
                        <form action="" method="GET">
                            <input type="date" name="from_date" class="form-control mb-3" value="<?= (isset($_GET['from_date'])) ? $_GET['from_date'] : '' ?>">
                            <select class="form-select" aria-label="Default select example" name="account_id" required>
                                <option selected disabled value="">Select Account</option>
                                <option value="22" <?= (isset($_GET['account_id']) && $_GET['account_id'] == 22) ? 'selected' : ''; ?>>Dole Philippines</option>
                                <option value="43" <?= (isset($_GET['account_id']) && $_GET['account_id'] == 43) ? 'selected' : ''; ?>>XDE Logistics</option>
                            </select>
                    </div>
                    <div class="col">
                        <input type="date" name="to_date" class="form-control mb-3" value="<?= (isset($_GET['to_date'])) ? $_GET['to_date'] : '' ?>">
                        <div class="input-group">
                            <input type="text" name="platenum" class="form-control" placeholder="Search For Plate Number..." value="<?= (isset($_GET['platenum'])) ? $_GET['platenum'] : '' ?>">
                            <!-- <input type="hidden" name="account_id" value="<?= (isset($_GET['account_id'])) ? $_GET['account_id'] : '' ?>"> -->
                            <button type="submit" class="btn btn-primary mx-1" name="submit-button">Search</button>
                            <a href="finished_task.php?done-task=<?= 1; ?>&account_id=<?= $account_id; ?>" class="btn btn-warning" style="text-decoration: none;">Reset</a>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            <!-- Search bar-->

            <!-- Table -->
            <?php
            // GET ALL REPORT WITH ACCOUNT ID AND PLATE NUMBER AND EMPTY DATES
            if (
                isset($_GET['account_id']) && !empty($_GET['platenum']) &&
                empty($_GET['from_date']) && empty($_GET['to_date'])
            ) {  ?>
                <?php
                if (mysqli_num_rows($query_run_one_report) > 0) { ?>
                    <table class="table table-striped table-hover text-center" style="box-shadow: 0px 3px 11px rgba(0, 0, 0, 0.25);">
                        <thead style="background: #7280A5;">
                            <tr class="text-white">
                                <th>Plate #</th>
                                <th>Status</th>
                                <th>Date Checked</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="font-monospace" style="vertical-align:middle">
                            <?php
                            while ($row = $query_run_one_report->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= $row['vplatenum']; ?></td>
                                    <td><?= $row['task_status']; ?></td>
                                    <td><?= $row['date_performed']; ?></td>
                                    <td><img src="uploads/<?= $row['image_url'] ?>"></td>
                                    <td><a href="finished_task_record.php?vid=<?= $row['vid']; ?>&account_id=<?= $account_id ?>" class="btn btn-danger">Record</a></td>
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
                                    <th style='width:70px; color:white;'>Action</th>
                                </tr>
                                <tr>
                                    <td colspan=4 style='text-align:center'>No Data!</td>
                                </tr>
                            </table>
                        <?php } ?>
                        </tbody>
                    </table>
                    <?php // GET ALL REPORT ACCORDING ACCOUNT ID WITHOUT PLATE NUM AND DATES
                    ?>
                <?php } elseif (
                empty($_GET['platenum']) && isset($_GET['account_id']) &&
                empty($_GET['from_date']) && empty($_GET['to_date'])
            ) { ?>
                    <?php

                    if (mysqli_num_rows($query_run) > 0) { ?>
                        <table class="table table-striped table-hover text-center" style="box-shadow: 0px 3px 11px rgba(0, 0, 0, 0.25);">
                            <thead style="background: #7280A5;">
                                <tr class="text-white">
                                    <th>Plate #</th>
                                    <th>Status</th>
                                    <th>Date Checked</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="font-monospace" style="vertical-align:middle">
                                <?php
                                while ($row = $query_run->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?= $row['vplatenum']; ?></td>
                                        <td><?= $row['task_status']; ?></td>
                                        <td><?= $row['date_performed']; ?></td>
                                        <td><img src="uploads/<?= $row['image_url'] ?>"></td>
                                        <td><a href="finished_task_record.php?vid=<?= $row['vid']; ?>&account_id=<?= $account_id ?>" class="btn btn-danger">Record</a></td>
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
                                        <th style='width:70px; color:white;'>Action</th>
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

                            $start = ($page_task_done - 2);
                            switch ($start) {
                                case 0:
                                    $start = 1;
                                case -1:
                                    $start = 1;
                            }
                            $end = ($page_task_done + 2);
                            if ($end > $pages) {
                                $end = $pages;
                            }

                            echo "<ul style='padding-left: 0;'>";
                            if ($page_task_done >= 2) {
                                echo "<a href='finished_task.php?done-task=1&account_id=" . $account_id . "'>First</a>";
                                echo "<a href='finished_task.php?done-task=" . ($page_task_done - 1) . "&account_id=" . $account_id . "'>&laquo;</a>";
                            }

                            for ($i = $start; $i <= $end; $i++) {
                                if ($i == $page_task_done) {
                                    $act_dect = "active";
                                } else {
                                    $act_dect = "";
                                }


                                if ($end == 1) {
                                    echo "<a href='finished_task.php?done-task=" . $i . "&account_id=" . $account_id . "' style='display: none;'>" . $i . "</a>";
                                } else {
                                    echo "<a href='finished_task.php?done-task=" . $i . "&account_id=" . $account_id . "' class=" . $act_dect . ">" . $i . "</a>";
                                }
                            }
                            if ($page_task_done < $pages) {
                                echo "<a href='finished_task.php?done-task=" . ($page_task_done + 1) . "&account_id=" . $account_id . "' class=" . $act_dect . ">&raquo;</a>";
                                echo "<a href='finished_task.php?done-task=" . $pages . "&account_id=" . $account_id . "' class=" . $act_dect . ">Last</a>";
                            }

                            if ($pages > 1) {
                                echo "<br><br>";
                                echo "<li style='list-style: none;'>" . $page_task_done . "&nbsp;of&nbsp;" . $pages . "&nbsp;Pages</li>";
                            }
                            echo "</ul>";
                            echo "</div>";
                            echo "</div>";
                            ?>

                        </div>
                    <?php // QUERY ALL VEHICLE WITH DATES AND PLATE NUM
                } elseif (isset($_GET['account_id']) && isset($_GET['from_date']) && isset($_GET['to_date'])) { ?>
                        <?php
                        if (mysqli_num_rows($result_with_date) > 0) { ?>
                            <table class="table table-striped table-hover text-center" style="box-shadow: 0px 3px 11px rgba(0, 0, 0, 0.25);">
                                <thead style="background: #7280A5;">
                                    <tr class="text-white">
                                        <th>Plate #</th>
                                        <th>Status</th>
                                        <th>Date Checked</th>
                                        <th>Image</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="font-monospace" style="vertical-align:middle">
                                    <?php
                                    while ($row = $result_with_date->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $row['vplatenum']; ?></td>
                                            <td><?= $row['task_status']; ?></td>
                                            <td><?= $row['date_performed']; ?></td>
                                            <td><img src="uploads/<?= $row['image_url'] ?>"></td>
                                            <td><a href="finished_task_record.php?vid=<?= $row['vid']; ?>&account_id=<?= $account_id ?>" class="btn btn-danger">Record</a></td>
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
                                            <th style='width:70px; color:white;'>Action</th>
                                        </tr>
                                        <tr>
                                            <td colspan=4 style='text-align:center'>No Data!</td>
                                        </tr>
                                    </table>
                                <?php } ?>
                                </tbody>
                            </table>
                            <?php // DON'T DISPLAY PAGINATION IF THERE IS PLATENUMBER
                             if (empty($_GET['platenum'])) { ?>
                                <!-- pagination -->
                                <div class="pagination mt-3">
                                    <?php

                                    if (mysqli_num_rows($all_result_with_date) > 0) {
                                        $records = mysqli_num_rows($all_result_with_date);
                                        $limit = 5;
                                        $pages = ceil($records / $limit);
                                    } else {
                                        $pages = '';
                                    }

                                    $start = ($page_finish_search - 2);
                                    switch ($start) {
                                        case 0:
                                            $start = 1;
                                        case -1:
                                            $start = 1;
                                    }
                                    $end = ($page_finish_search + 2);
                                    if ($end > $pages) {
                                        $end = $pages;
                                    }

                                    echo "<ul style='padding-left: 0;'>";
                                    if ($page_finish_search >= 2) {
                                        echo "<a href='finished_task.php?finish-search=1&account_id=" . $account_id . "&from_date=" . $from_date . "&to_date=" . $to_date . "'>First</a>";
                                        echo "<a href='finished_task.php?finish-search=" . ($page_finish_search - 1) . "&account_id=" . $account_id . "&from_date=" . $from_date . "&to_date=" . $to_date . "'>&laquo;</a>";
                                    }

                                    for ($i = $start; $i <= $end; $i++) {
                                        if ($i == $page_finish_search) {
                                            $act_dect = "active";
                                        } else {
                                            $act_dect = "";
                                        }


                                        if ($end == 1) {
                                            echo "<a href='finished_task.php?finish-search=" . $i . "&account_id=" . $account_id . "&from_date=" . $from_date . "&to_date=" . $to_date . "' style='display: none;'>" . $i . "</a>";
                                        } else {
                                            echo "<a href='finished_task.php?finish-search=" . $i . "&account_id=" . $account_id . "&from_date=" . $from_date . "&to_date=" . $to_date . "' class=" . $act_dect . ">" . $i . "</a>";
                                        }
                                    }
                                    if ($page_finish_search < $pages) {
                                        echo "<a href='finished_task.php?finish-search=" . ($page_finish_search + 1) . "&account_id=" . $account_id . "&from_date=" . $from_date . "&to_date=" . $to_date . "' class=" . $act_dect . ">&raquo;</a>";
                                        echo "<a href='finished_task.php?finish-search=" . $pages . "&account_id=" . $account_id . "&from_date=" . $from_date . "&to_date=" . $to_date . "' class=" . $act_dect . ">Last</a>";
                                    }

                                    if ($pages > 1) {
                                        echo "<br><br>";
                                        echo "<li style='list-style: none;'>" . $page_finish_search . "&nbsp;of&nbsp;" . $pages . "&nbsp;Pages</li>";
                                    }
                                    echo "</ul>";
                                    echo "</div>";
                                    echo "</div>";
                                    ?>

                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <table class="table table-striped table-hover text-center mb-5" style="box-shadow: 0px 3px 11px rgba(0, 0, 0, 0.25);">
                                <tr class='text-white' style="height:40px;background-color: #7280A5;font-weight: 600">
                                    <th style='display:none'>VID</th>
                                    <th style='width:200px; color:white;'>Plate #</th>
                                    <th style='width:300px; color:white;'>Status</th>
                                    <th style='width:200px; color:white;'>Date Checked</th>
                                    <th style='width:200px; color:white;'>Image</th>
                                    <th style='width:70px; color:white;'>Action</th>
                                </tr>
                                <tr>
                                    <td colspan=4 style='text-align:center; font-weight:700'>No Data</td>
                                </tr>
                            </table>
                        <?php } ?>
        </div>
    </div>
    <?php include 'mobile_pages/finished_task.php' ?>
</body>

</html>