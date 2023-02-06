<nav class="navbar bg-light fixed-top shadow-sm" style="padding: 25px">
    <div class="container-fluid">
      <!-- burger button  -->
      <i class="burger fa-solid fa-bars-staggered mt-1" style="font-size: 35px; margin-left:-12px;" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions"></i>
      <!-- title -->
      <div class="title">
        <a style="margin-left:39px; position:absolute; margin-top: 5px" class="nav-bar-desktop navbar-brand text-danger fs-4" href="index.php">EZ<span style="font-size: 14px; color: black;">TRACK</span>TR</a>
        <a class="nav-bar-mobile navbar-brand"  href="index.php" style="margin-left: 14px; margin-top: 10px; font-size: 25px; color:red">EZ<span class="text-black" style="font-size: 16px; margin-top: 100px">TRACK</span>TR<img style="height: 210%; width: 40%" src="https://www.linkpicture.com/q/EzTech-logo.png"></a>
      </div>
      <!-- burger content-->
      <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
          <div class="offcanvas-header">
            <h5 class="offcanvas-title mt-3 text-danger" id="offcanvasWithBothOptionsLabel">EZ<span style="font-size:13px; color: black">TRACK</span>TR</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body">
            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                  <li class="nav-item">
                    <a class="nav-link fs-4 active" aria-current="page" href="index.php">Home</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link fs-4" href="service_task.php">Service Task</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link fs-4" href="pending_task.php">Pending Task</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link fs-4" href="finished_task.php">Finished Task</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link fs-4" href="logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
                  </li>
            </ul>
          </div>
      </div>
    </div>
</nav>







