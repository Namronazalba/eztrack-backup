

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
          <h2 class="s-header">USER LIST</h2>
          <div class="b-mt">
            <a href="new.php" class="btn btn-success btn-sm b-width">New</a>
          </div>
        </div>
        <?php include 'alert_msg.php'; ?>
        <div class="table-responsive mt-3 bx-shadow">
          <table class="table table-striped table-md table-hover">
            <thead style="background: #9F2727;">
              <tr class="text-white text-center" style="vertical-align:middle">
                <th>NO</t>
                <th>LAST NAME</th>
                <th>FIRST NAME</th>
                <th>GENDER</th>
                <th>CONTACT NO</th>
                <th>ADDRESS</th>
                <th>USERNAME</th>
                <th>ROLE</th>
                <th>ACTIONS</th>
              </tr>
            </thead>
            <?php
            include '../connection.php';
            $query = "SELECT * FROM tbl_user_technician";
            $result = mysqli_query($dbc, $query);
            if (!$result) {
            die('Query Failed' . mysqli_connect_error());
            }
            ?>
            <tbody style="vertical-align:middle">
              <?php
              if (mysqli_num_rows($result) > 0) {
                $id = 1;
                while ($data = mysqli_fetch_assoc($result)) {
              ?>
                  <tr class="text-center">
                    <td><?= $id; ?></td>
                    <td><?= $data['last_name']; ?></td>
                    <td><?= $data['first_name']; ?></td>
                    <td><?= $data['gender']; ?></td>
                    <td><?= $data['contact_no']; ?></td>
                    <td><?= $data['address']; ?></td>
                    <td><?= $data['username']; ?></td>
                    <td><?= $data['role']; ?></td>
                    <td>
                      <a href="edit.php?id=<?= $data['id']; ?>" class="btn btn-danger btn-sm b-width">Edit</a>
                      <a href="delete.php?id=<?= $data['id']; ?>" class="btn btn-danger btn-sm b-width">Delete</a>
                    </td>
                  </tr>
                <?php
                  $id++;
                }
              } else {
                ?>
                <tr>
                  <td colspan="9">No Record</td>
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