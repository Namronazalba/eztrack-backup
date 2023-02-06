<?php
include '../connection.php';
$query = "SELECT *  FROM `tbl_trblesht_report`
          LEFT JOIN tbl_cvehicles
          ON tbl_trblesht_report.vid = tbl_cvehicles.vid
          WHERE task_status = 'Finished'
          AND troubleshooting_status = 'Fixed'";
$result = mysqli_query($dbc, $query);
if (!$result) {
    die('Query Failed' . mysqli_connect_error());
}
?>
<!--doctype, head, css link, js link-->
<?php include '../layouts/link.php'; ?>

<body>
    <!-- navbar -->
  <?php include '../layouts/navbar.php'; ?>
    <!-- Sidebar Menu -->
    <div class="container-fluid">
        <div class="row">
            <!--Sidebar -->
            <?php include 'sidebar.php'; ?>
            <!-- Main -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2  border-bottom">
                    <h2 class="s-header">TOTAL TROUBLESHOOTED</h2>
                    <div class="d-flex justify-content-center">
                        <a href="../index.php" class="btn btn-secondary btn-sm b-mt b-width">Back</a>
                    </div>
                </div>
                <div class="table-responsive mt-3 mb-2 bx-shadow">
                    <table class="table table-striped table-md table-hover">
                        <thead class="b-table">
                            <tr class="text-white text-center" style="vertical-align:middle">
                                <th>PLATE NUMBER</th>
                                <th>STATUS</th>
                                <th>DATE CHECKED</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody style="vertical-align:middle">
                            <?php
                            if (mysqli_num_rows($result) > 0) {
                                while ($data = mysqli_fetch_assoc($result)) {
                            ?>
                                    <tr class="text-center">
                                        <td><?php echo $data['vplatenum']; ?></td>
                                        <td><?php echo $data['troubleshooting_status']; ?></td>
                                        <td><?php echo $data['date_performed']; ?></td>
                                        <td>
                                        <a href="history_record.php?id=<?php echo $data['vid'];?>" class="btn btn-info btn-sm b-width text-white">History</a>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="4">No Record</td>
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