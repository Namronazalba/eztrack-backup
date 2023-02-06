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
                    <h2 class="s-header">TOTAL OFFLINE VEHICLES</h2>
                    <div class="d-flex justify-content-center">
                        <a href="../index.php" class="btn btn-secondary btn-sm b-mt b-width">Back</a>
                    </div>
                </div>
                <div class="table-responsive mt-3 mb-5 bx-shadow">
                    <table class="table table-striped table-md table-hover">
                        <thead class="r-table">
                            <tr class="text-white text-center" style="vertical-align:middle">
                                <th>PLATE NUMBER</th>
                                <th>LOCATION</th>
                                <th>LAST REPORT</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody style="vertical-align:middle">
                            <tr class="text-center">
                                <td>J2-97</td>
                                <td>Pasay City</td>
                                <td>09-16-67</td>
                                <td>
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#Modal">Details</button>
                                </td>
                            </tr>
                            <tr class="text-center">
                                <td>J2-97</td>
                                <td>Pasay City</td>
                                <td>09-16-67</td>
                                <td>
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#Modal">Details</button>
                                </td>
                            </tr>
                            <tr class="text-center">
                                <td>J2-97</td>
                                <td>Pasay City</td>
                                <td>09-16-67</td>
                                <td>
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#Modal">Details</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- Modal -->
                    <div class="modal fade" id="Modal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header d-flex justify-content-center">
                                    <h1 class="modal-title fs-5" id="ModalLabel">PLATE NO: J2-97</h1>
                                </div>
                                <div class="modal-body">
                                    <p>LOCATION:</p>
                                    <p>LAST REPORT:</p>
                                    <p>IMEI:</p>
                                    <p>SIM CARD N0:</p>
                                </div>
                                <div class="modal-footer d-flex justify-content-center">
                                    <button type="button" class="btn btn-secondary btn-sm b-width" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>