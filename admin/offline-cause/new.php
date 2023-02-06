<?php
include 'process.php';
include '../connection.php';
$query = "SELECT * FROM tbl_user_technician";
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
                    <h2 class="s-header">NEW OFFLINE CAUSE</h2>
                    <div class="d-flex justify-content-center">
                        <a href="index.php" class="btn btn-secondary btn-sm b-mt b-width">Back</a>
                    </div>
                </div>
                <div class="p-2 mt-2 mb-4">
                    <div class="card bx-shadow">
                        <div class="card-body p-4">
                            <form method="post" action="new.php">
                                <div class="form-group mb-3">
                                    <label class="form-label">Offline Reason</label>
                                    <select class="form-select" name="select_offline_reason">
                                        <?php
                                        $reason_for_offline = "SELECT * FROM tbl_reason_for_offline";
                                        $reason_for_offline_query = mysqli_query($dbc, $reason_for_offline);
                                        ?>
                                        <option selected disabled value="">Select offline reason</option>
                                        <?php while ($data = mysqli_fetch_assoc($reason_for_offline_query)) : ?>
                                            <option value="<?php echo $data['reason_id'] ?>"> <?php echo $data['reason_for_offline_name']; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Offline Cause</label>
                                    <input type="text" class="form-control" name="content" placeholder="Enter Content" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                </div>
                                <div class="d-flex justify-content-center">
                                    <input type="submit" value="Create" name="create" class="btn btn-success w-50">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>