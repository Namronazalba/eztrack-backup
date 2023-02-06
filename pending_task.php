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

    if (isset($_GET['offline-vehicle'])) {
        $page_pending_vehicle = $_GET['offline-vehicle'];
    } else {
        $page_pending_vehicle = 1;
    }

    if (isset($_GET['platenum'])) {
        $platenum = $_GET['platenum'];
    } else {
        $platenum = '';
    }

    $start_from_page_pending_vehicle = ($page_pending_vehicle - 1) * 5;

    // QUERY TROUBLESHOOT REPORT WITH LIMIT
    $query_pending_task = "SELECT * FROM tbl_trblesht_report
    LEFT JOIN tbl_cvehicles
    ON tbl_trblesht_report.vid = tbl_cvehicles.vid
    WHERE id IN (SELECT MAX(id) FROM tbl_trblesht_report WHERE task_status = 'Unfinished' GROUP BY vid)
    AND technician_id = $user_id AND accnt_id = $account_id
    LIMIT $start_from_page_pending_vehicle, $num_per_page";

    $query_run = mysqli_query($dbc, $query_pending_task);

    //QUERY ALL TROUBLESHOOT REPORT
    $query_all_pending_task = "SELECT * FROM tbl_trblesht_report
    LEFT JOIN tbl_cvehicles
    ON tbl_trblesht_report.vid = tbl_cvehicles.vid
    WHERE id IN (SELECT MAX(id) FROM tbl_trblesht_report WHERE task_status = 'Unfinished' GROUP BY vid)
    AND technician_id = $user_id AND accnt_id = $account_id";

    $query_run_all_pending_task = mysqli_query($dbc, $query_all_pending_task);
}

if (!empty($_GET['platenum']) && isset($_GET['account_id'])) {
    // QUERY 1 TROUBLESHOOT REPORT DEPENDING ON PLATE NUMBER 
    $query_one_report = "SELECT * FROM tbl_trblesht_report
    LEFT JOIN tbl_cvehicles
    ON tbl_trblesht_report.vid = tbl_cvehicles.vid
    WHERE id IN (SELECT MAX(id) FROM tbl_trblesht_report WHERE task_status = 'Unfinished' GROUP BY vid)
    AND technician_id = $user_id AND accnt_id = $account_id AND vplatenum LIKE '%$platenum%'";

    $query_run_one_report = mysqli_query($dbc, $query_one_report);
}
?>
<!doctype html>
<html lang="en">
<!-- head -->
<?php include 'head.php'; ?>
<style>
    img {
        width: 120px;
        height: 100px;
    }
    @media(max-width: 576px) {
        .container-pendingtask-desktop{
            display: none;
        }
    }
    
</style>

<body class="p-3 m-0 border-0 bd-example bgcolor">
    <!-- navbar -->
    <?php include 'navbar.php'; ?>
    <div class="container-pendingtask-desktop container">
    <p class="fs-5 text-black mt-4">Pending Task / <span class="text-secondary">Uncomplete Services</span></p>
        <div class="card mb-2 mt-3 shadow-sm px-5 pt-5 pb-3">
            <!-- Search bar-->
            <div class="container">
                <div class="row align-items-start mb-4">
                    <div class="col">
                        <form action="" method="GET">
                            <select class="form-select" aria-label="Default select example" name="account_id" required>
                                <option selected disabled value="">Select Account</option>
                                <option value="22" <?= (isset($_GET['account_id']) && $_GET['account_id'] == 22) ? 'selected' : ''; ?>>Dole Philippines</option>
                                <option value="43" <?= (isset($_GET['account_id']) && $_GET['account_id'] == 43) ? 'selected' : ''; ?>>XDE Logistics</option>
                            </select>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <input type="text" name="platenum" class="form-control" placeholder="Search For Plate Number..." value="<?= (isset($_GET['platenum'])) ? $_GET['platenum'] : '' ?>">
                            <!-- <input type="hidden" name="account_id" value="<?= (isset($_GET['account_id'])) ? $_GET['account_id'] : '' ?>"> -->
                            <button type="submit" class="btn btn-primary" name="submit-button">Search</button>
                            <a href="pending_task.php?offline-vehicle=<?= 1; ?>&account_id=<?= $account_id; ?>" class="btn btn-warning" style="text-decoration: none;">Reset</a>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            <!-- Search bar-->

            <!-- Table -->
            <?php
            // GET ALL REPORT WITH ACCOUNT ID AND PLATE NUMBER
            if (isset($_GET['account_id']) && !empty($_GET['platenum'])) {  ?>
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
                                    <td><a href="update_service.php?id=<?= $row['id']; ?>&vid=<?= $row['vid']; ?>&account_id=<?= $account_id ?>" class="btn btn-warning">Edit</a></td>
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
                <?php } else { ?>
                    <?php
                    // GET ALL REPORT ACCORDING ACCOUNT ID
                    if (empty($_GET['platenum']) && isset($_GET['account_id'])) { ?>

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
                                            <td><a href="update_service.php?id=<?= $row['id']; ?>&vid=<?= $row['vid']; ?>&account_id=<?= $account_id ?>" class="btn btn-warning">Edit</a></td>
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

                                if (mysqli_num_rows($query_run_all_pending_task) > 0) {
                                    $records = mysqli_num_rows($query_run_all_pending_task);
                                    $limit = 5;
                                    $pages = ceil($records / $limit);
                                } else {
                                    $pages = '';
                                }

                                $start = ($page_pending_vehicle - 2);
                                switch ($start) {
                                    case 0:
                                        $start = 1;
                                    case -1:
                                        $start = 1;
                                }
                                $end = ($page_pending_vehicle + 2);
                                if ($end > $pages) {
                                    $end = $pages;
                                }

                                echo "<ul style='padding-left: 0;'>";
                                if ($page_pending_vehicle >= 2) {
                                    echo "<a href='pending_task.php?offline-vehicle=1&account_id=" . $account_id . "'>First</a>";
                                    echo "<a href='pending_task.php?offline-vehicle=" . ($page_pending_vehicle - 1) . "&account_id=" . $account_id . "'>&laquo;</a>";
                                }

                                for ($i = $start; $i <= $end; $i++) {
                                    if ($i == $page_pending_vehicle) {
                                        $act_dect = "active";
                                    } else {
                                        $act_dect = "";
                                    }


                                    if ($end == 1) {
                                        echo "<a href='pending_task.php?offline-vehicle=" . $i . "&account_id=" . $account_id . "' style='display: none;'>" . $i . "</a>";
                                    } else {
                                        echo "<a href='pending_task.php?offline-vehicle=" . $i . "&account_id=" . $account_id . "' class=" . $act_dect . ">" . $i . "</a>";
                                    }
                                }
                                if ($page_pending_vehicle < $pages) {
                                    echo "<a href='pending_task.php?offline-vehicle=" . ($page_pending_vehicle + 1) . "&account_id=" . $account_id . "' class=" . $act_dect . ">&raquo;</a>";
                                    echo "<a href='pending_task.php?offline-vehicle=" . $pages . "&account_id=" . $account_id . "' class=" . $act_dect . ">Last</a>";
                                }

                                if ($pages > 1) {
                                    echo "<br><br>";
                                    echo "<li style='list-style: none;'>" . $page_pending_vehicle . "&nbsp;of&nbsp;" . $pages . "&nbsp;Pages</li>";
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
                                        <th style='width:70px; color:white;'>Action</th>
                                    </tr>
                                    <tr>
                                        <td colspan=4 style='text-align:center; font-weight:700'>No Data</td>
                                    </tr>
                                </table>

                            <?php  } ?>
                        <?php } ?>

                            </div>
        </div>
    </div>
    <?php include 'mobile_pages/pending_task.php'?>
</body>

</html>