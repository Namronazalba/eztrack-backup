<?php
include '../connection.php';
$query = "SELECT * FROM tbl_reason_for_offline";
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
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 border-bottom">
          <h2 class="s-header">OFFLINE REASON</h2>
          <div class="d-flex justify-content-center">
            <a href="new.php" class="btn btn-success btn-sm b-width b-mt">New</a>
          </div>
        </div>
        <div class="table-responsive mt-3 mb-5 bx-shadow">
          <table class="table table-striped table-md table-hover">
            <thead style="background: #9F2727;">
              <tr class="text-white text-center" style="vertical-align:middle">
                <th>ID</th>
                <th>CONTENT</th>
                <th>DATE CREATED</th>
                <th>ACTIONS</th>
              </tr>
            </thead>
            <tbody style="vertical-align:middle">
              <?php
              if (mysqli_num_rows($result) > 0) {
                while ($data = mysqli_fetch_assoc($result)) {
              ?>
                  <tr class="text-center">
                    <td><?php echo $data['reason_id']; ?></td>
                    <td><?php echo $data['reason_for_offline_name']; ?></td>
                    <td><?php echo date('M d, Y', strtotime($data['date_created'])); ?></td>
                    <td>
                      <a href="edit.php?id=<?php echo $data['reason_id']; ?>" class="btn btn-danger btn-sm b-width">Edit</a>
                      <a href="delete.php?id=<?php echo $data['reason_id']; ?>" class="btn btn-danger btn-sm b-width">Delete</a>
                    </td>
                  </tr>
                <?php
                }
              } else {
                ?>
                <tr>
                  <td colspan="3">No Record</td>
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