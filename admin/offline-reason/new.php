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
                    <h2 class="s-header">NEW OFFLINE REASON</h2>
                    <div class="d-flex justify-content-center">
                        <a href="index.php" class="btn btn-secondary btn-sm b-width b-mt">Back</a>
                    </div>
                </div>
                <div class="p-2 mt-3 mb-4">
                    <div class="card bx-shadow">
                        <div class="card-body p-4">
                            <form method="post" action="new.php">
                                <div class="form-group mb-3">
                                    <label class="form-label">Offline Reason</label>
                                    <input type="text" name="content" class="form-control" placeholder="Enter Content">
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