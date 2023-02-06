<?php
include '../connection.php';
$query = "SELECT *  FROM `tbl_trblesht_report`
          LEFT JOIN tbl_cvehicles
          ON tbl_trblesht_report.vid = tbl_cvehicles.vid
          WHERE task_status = 'Unfinished'";
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
    <div class="container-fluid">
        <div class="row">
            <!--Sidebar -->
            <?php include 'sidebar.php'; ?>
            <!-- Main -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2  border-bottom">
                    <h2 class="s-header">TOTAL PENDING SERVICES</h2>
                    <div class="d-flex justify-content-center">
                        <a href="../index.php" class="btn btn-secondary btn-sm b-mt b-width">Back</a>
                    </div>
                </div>
                <div class="table-responsive mt-3 mb-5 bx-shadow">
                    <table class="table table-striped table-md table-hover">
                        <thead class="v-table">
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
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#Modal1<?php echo $data['id'] ?>">Details</button>
                                        </td>
                                    </tr>
                                    <!--Form Modal -->
                                    <div class="modal fade" id="Modal1<?php echo $data['id'] ?>" tabindex="-1" aria-labelledby="Modal1Label" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header d-flex justify-content-center">
                                                    <h1 class="modal-font" id="Modal1Label"><?php echo $data['vplatenum']; ?></h1>
                                                </div>
                                                <div class="modal-body">
                                                    <fieldset disabled>
                                                        <div class="mb-3">
                                                            <label class="form-label">STATUS</label>
                                                            <input type="text" class="form-control form-control-sm border-light bg-light shadow-sm" value="<?php echo $data['troubleshooting_status']; ?>">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">DATE CHECKED</label>
                                                            <input type="text" class="form-control form-control-sm border-light bg-light shadow-sm" value="<?php echo $data['date_performed']; ?>">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">ADDRESS</label>
                                                            <textarea class="form-control form-control-sm border-light bg-light shadow-sm" rows="1"><?php echo $data['complete_address']; ?></textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">REASON FOR OFFLINE</label>
                                                            <input type="text" class="form-control form-control-sm border-light bg-light shadow-sm" value="<?php echo $data['reason_offline']; ?>">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">CAUSE OF OFFLINE</label>
                                                            <input type="text" class="form-control form-control-sm border-light bg-light shadow-sm" value="<?php echo $data['cause_offline']; ?>">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">ACTION TAKEN</label>
                                                            <input type="text" class="form-control form-control-sm border-light bg-light shadow-sm" value="<?php echo $data['action_taken']; ?>">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">TECHNICIAN</label>
                                                            <input type="text" class="form-control form-control-sm border-light bg-light shadow-sm" value="<?php echo $data['technician']; ?>">
                                                        </div>
                                                    </fieldset>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary btn-sm b-wdith" data-bs-dismiss="modal">Close</button>
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-primary btn-sm b-wdith" data-bs-toggle="modal" data-bs-target="#Modal2">Image</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Image Modal -->
                                    <div class="modal fade" id="Modal2" tabindex="-1" aria-labelledby="Modal2Label" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="Modal2Label">Image</h1>
                                                </div>
                                                <div class="modal-body">
                                                    <img src="../uploads/sample.png" class="img-fluid" alt="">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm b-wdith" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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