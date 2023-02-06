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
          <h2 class="s-header">EDIT USER</h2>
          <div class="b-mt">
            <a href="index.php" class="btn btn-secondary btn-sm b-width">Back</a>
          </div>
        </div>
        <div class="p-2 mt-2 mb-4">
          <div class="card bx-shadow">
            <div class="card-body p-4">
              <?php
              if (isset($_GET["id"])) {
                include '../connection.php';
                $id = $_GET["id"];
                $sql = "SELECT * FROM tbl_user_technician WHERE id=$id";
                $result = mysqli_query($dbc, $sql);
                $data = mysqli_fetch_array($result);
              ?>
                <form method="post" action="new.php">
                  <div class="row">
                    <div class="col">
                      <div class="form-group mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="last_name" value="<?= $data['last_name']; ?>" placeholder="Enter last name" autocomplete="off">
                      </div>
                    </div>
                    <div class="col">
                      <div class="form-group mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control" name="first_name" value="<?= $data['first_name']; ?>" placeholder="Enter first name" autocomplete="off">
                      </div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Address</Address></label>
                    <textarea class="form-control" name="address" placeholder="Enter address" rows="1"><?= $data['address']; ?></textarea>
                  </div>

                  <div class="row">
                    <div class="col">
                      <div class="form-group mb-3">
                        <label class="form-label">Contact No.</label>
                        <input type="text" class="form-control" name="contact_no" value="<?= $data['contact_no']; ?>" placeholder="Enter contact no." autocomplete="off">
                      </div>
                    </div>
                    <div class="col">
                      <div class="form-group">
                        <label class="form-label">Gender</label>
                        <select class="form-select" name="gender">
                          <option disabled selected value="">Select Gender</option>
                          <option value="Male"
                          <?php
                            if ($data['gender'] == 'Male') {
                              echo "selected";
                            }
                            ?>>Male</option>
                          <option value="Female"
                          <?php
                            if ($data['gender'] == 'Female') {
                              echo "selected";
                            }
                            ?>>Female</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col">
                      <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" value="<?= $data['username']; ?>" name="username" placeholder="Enter username" autocomplete="off">
                      </div>
                    </div>
                    <div class="col">
                      <div class="form-group">
                        <label class="form-label">Role</label>
                        <select class="form-select" name="role">
                          <option disabled selected value="">Select Role</option>
                          <option value="Admin"
                          <?php
                            if ($data['role'] == 'Admin') {
                              echo "selected";
                            }
                          ?>>Admin</option>
                          <option value="Technician"
                          <?php
                            if ($data['role'] == 'Technician') {
                              echo "selected";
                            }
                          ?>>Technician</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col">
                      <div class="form-group mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password_1" placeholder="Enter password" id="myInput1" autocomplete="off">
                      </div>
                    </div>
                    <div class="col">
                      <div class="form-group mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="password_2" placeholder="Confirm password" id="myInput2" autocomplete="off">
                      </div>
                    </div>
                  </div>

                  <div class="form-check">
                    <input type="checkbox" onclick="myFunction()">
                    <label class="form-check-label">Show Password</label>
                  </div>
                  <input type="hidden" value="<?= $id; ?>" name="id">
                  <div class="d-flex justify-content-center">
                    <button class="btn btn-success w-50 mt-2" type="submit" name="update"><span>Update</span></button>
                  </div>
                <?php
              } else {
                echo "<h3 class='d-flex justify-content-center' >User Does Not Exist</h3>";
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
<script>
  function myFunction() {
    var x = document.getElementById("myInput1");
    var y = document.getElementById("myInput2");
    if (x.type === "password" && y.type === "password") {
      x.type = "text";
      y.type = "text";
    } else {
      x.type = "password";
      y.type = "password";
    }
  }
</script>

</html>