<?php include '../connection.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM tbl_trblesht_report
              LEFT JOIN tbl_cvehicles
              ON tbl_trblesht_report.vid = tbl_cvehicles.vid
              WHERE tbl_trblesht_report.vid = $id
              ORDER BY id DESC";
    $result = mysqli_query($dbc, $query);
}
?>

<!--doctype, head, css link, js link-->
<?php include '../layouts/link.php'; ?>

<body>
    <!-- navbar -->
    <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4 d-flex justify-content-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php"><span style="color: #ff0000">TR</span>-ADMIN</a>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <!-- Main -->
            <main class="main">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2  border-bottom">
                    <?php $row = mysqli_fetch_array($result) ?>
                    <h2 class="s-header d-flex justify-content-center"> <span style="color: #084695"><?= $row['vplatenum']; ?></span></h2>
                    <div class="d-flex justify-content-center">
                        <a href="../home/troubleshooted.php" class="btn btn-secondary btn-sm b-mt b-width">Back</a>
                    </div>
                </div>
                <div class=" table-responsive mt-3 mb-2 bx-shadow">
                    <table class="table table-striped table-md table-hover">
                        <thead class="b-table">
                            <tr class="text-white text-center" style="vertical-align:middle">
                                <th>STATUS</th>
                                <th>DATE CHECKED</th>
                                <th>TIME TRAVEL</th>
                                <th>WORK DURATION</th>
                                <th>REASON FOR OFFLINE</th>
                                <th>CAUSE FOR OFFLINE</th>
                                <th>ACTION TAKEN</th>
                                <th>IMAGE</th>
                                <th>COORDINATED BY</th>
                                <th>TECHNICIAN</th>
                                <th>CHECKED BY</th>
                                <th>CONTACT NO.</th>
                            </tr>
                        </thead>
                        <tbody style="vertical-align:middle">
                            <?php
                            if (mysqli_num_rows($result) > 0) {
                                while ($data = mysqli_fetch_assoc($result)) {
                                    $work_start = strtotime($data['work_time_start']);
                                    $work_end = strtotime($data['work_time_end']);
                                    $arrival_result = $work_end - $work_start;
                                    $min = floor(($arrival_result % 3600) / 60);
                                    $hr = floor(($arrival_result % 86400) / 3600);
                            ?>
                                    <tr class="text-center">
                                        <td><?= $data['troubleshooting_status']; ?></td>
                                        <td><?= $data['date_performed']; ?></td>
                                        <td><?= $data['traveling_time']; ?></td>
                                        <td><?php
                                            if ($hr > 1 && $min > 1) {  // checked if hr and min are greater than 1
                                                echo "$hr hrs and $min mins";
                                            } elseif ($hr > 1 && $min == 1) { //checked if hr is greater than 1 and min is equal to 1
                                                echo "$hr hrs, $min min";
                                            } elseif ($hr == 1 && $min > 1) { //checked if hr is equal than 1 and min is greater to 1
                                                echo "$hr hr, $min mins";
                                            } elseif ($hr > 1 && $min == 0) {
                                                echo "$hr hrs";
                                            } elseif ($hr == 0 && $min > 1) {
                                                echo "$min mins";
                                            } elseif ($hr == 0 && $min == 1) {
                                                echo "$min min";
                                            } elseif ($hr == 1 && $min == 0) {
                                                echo "$hr hr";
                                            } elseif ($hr == 0 && $min == 0) {
                                                echo "0";
                                            } else {
                                                echo "$hr hr, $min min";
                                            }
                                            ?>
                                        </td>
                                        <td><?= $data['reason_offline']; ?></td>
                                        <td><?= $data['cause_offline']; ?></td>
                                        <td><?= $data['action_taken']; ?></td>
                                        <td>
                                            <img src="../admin/uploads/<?= $row['image_url'] ?>" class="img-thumbnail">
                                        </td>
                                        <td><?= $data['coordinated_by']; ?></td>
                                        <td><?= $data['technician']; ?></td>
                                        <td><?= $data['checked_by']; ?></td>
                                        <td><?= $data['contact_num']; ?></td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="12">No Record</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>

</html>