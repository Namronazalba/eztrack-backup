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
                    <h2 class="s-header">EDIT OFFLINE CAUSE</h2>
                    <div class="d-flex justify-content-center">
                        <a href="index.php" class="btn btn-secondary btn-sm b-mt b-width">Back</a>
                    </div>
                </div>
                <div class="p-2 mt-2 mb-4">
                    <div class="card bx-shadow">
                        <div class="card-body p-4">
                            <form method="POST" action="edit.php">
                                <?php
                                if (isset($_GET['id'])) {
                                    include '../connection.php';
                                    $id = $_GET['id'];
                                    $sql = "SELECT * FROM tbl_cause_of_offline WHERE cause_id=$id";
                                    $result = mysqli_query($dbc, $sql);
                                    $data = mysqli_fetch_assoc($result);
                                ?>
                                    <div class="form-group mb-3">
                                        <label class="form-label">Offline Reason</label>
                                        <select class="form-select" name="">
                                            <?php
                                            $reason_for_offline = "SELECT * FROM tbl_reason_for_offline";
                                            $reason_for_offline_query = mysqli_query($dbc, $reason_for_offline);
                                            ?>
                                            <option selected disabled value="">Select offline reason</option>
                                            <?php while ($data_from_resonOffline = mysqli_fetch_assoc($reason_for_offline_query)) : ?>
                                                <option value="<?php echo $data_from_resonOffline['reason_id'] . $data_from_resonOffline['reason_for_offline_name']; ?>"><?php echo $data_from_resonOffline['reason_for_offline_name']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="form-label">Offline Reason</label>
                                        <input type="text" name="content" value="<?php echo $data['cause_of_offline_name']; ?>" class="form-control" placeholder="Enter Content">
                                    </div>
                                    <input type="hidden" value="<?php echo $id; ?>" name="id">
                                    <div class="d-flex justify-content-center mt-4">
                                        <input type="submit" value="Update" name="update" class="btn btn-success w-50">
                                    </div>
                                <?php
                                } else {
                                    echo "<h3>Content Does Not Exist</h3>";
                                }
                                ?>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>