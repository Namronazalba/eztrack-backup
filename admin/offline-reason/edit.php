<?php include 'process.php'; ?>
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
                    <h2 class="s-header">EDIT OFFLINE REASON</h2>
                    <div class="d-flex justify-content-center">
                        <a href="index.php" class="btn btn-secondary btn-sm b-width b-mt">Back</a>
                    </div>
                </div>
                <div class="p-2 mt-3 mb-4">
                    <div class="card bx-shadow">
                        <div class="card-body p-4">
                            <form method="POST" action="edit.php">
                                <?php
                                if (isset($_GET['id'])) {
                                    include '../connection.php';
                                    $id = $_GET['id'];
                                    $sql = "SELECT * FROM tbl_reason_for_offline WHERE reason_id=$id";
                                    $result = mysqli_query($dbc, $sql);
                                    $data = mysqli_fetch_assoc($result);
                                ?>
                                    <div class="form-group mb-3">
                                        <label class="form-label">Offline Reason</label>
                                        <input type="text" name="content" value="<?php echo $data['reason_for_offline_name']; ?>" class="form-control" placeholder="Enter Content">
                                    </div>
                                    <input type="hidden" value="<?php echo $id; ?>" name="id">
                                    <div class="d-flex justify-content-center">
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
    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>