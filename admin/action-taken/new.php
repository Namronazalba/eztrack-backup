<?php
include '../connection.php';
$query = "SELECT * FROM tbl_user_technician";
$result = mysqli_query($dbc, $query);
if (!$result) {
    die('Query Failed' . mysqli_connect_error());
}
?>

<!--doctype, head, css link, js link & title -->
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
                    <h2 class="s-header">NEW ACTION TAKEN</h2>
                    <div class="b-mt">
                        <a href="index.php" class="btn btn-secondary btn-sm btn-sm b-width">Back</a>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="card bx-shadow">
                        <div class="card-body p-4">
                            <form method="post" action="new.php">
                                <div class="form-group mb-3">
                                    <label class="form-label"><b>Action Taken</b></label>
                                    <input type="text" class="form-control" placeholder="Enter Content" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-success w-50">Create</button>
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