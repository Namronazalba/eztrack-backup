<!doctype html>
<html lang="en">
<body>
    <!-- content -->
    <div class="container-details-mobile container">
    <p class="fs-5 text-dark mt-4">Service Task / <span class="text-secondary">Details</span></p>
        <div class="card" style="box-shadow: 0px 3px 11px rgba(0, 0, 0, 0.25)">
            <div class="card-header text-white text-center" style="background: #7280A5; border-radius: 5px 5px 20px 20px;">
                <p style="font-size:14px; margin-bottom: 17px" class="mt-2 fw-bold text-white">PLATE NUMBER</p>
                <h2 class="card-text mb-2" style="margin-top: -20px; font-style: italic; font-size: 38px"><?= $data['vplatenum']?></h2>
            </div>
            <div class="card-body mt-3 text-center">
                <h3 class="fw-bold" style="margin-bottom: -1px;">LOCATION <i class="fa-solid fa-location-dot"></i></h3>
                <span class="lh-1" style="text-transform: uppercase; font-size: 19px"><?= $data_last_log['address']?></span>
                <h3 class="fw-bold mt-3" style="margin-bottom: -1px;" >DATE OFFLINE <i class="fa-regular fa-calendar-check"></i></h3>
                <span style="text-transform: uppercase; font-size: 19px"><?= fordate($data_last_log['gps_date'])?></span>
                <h3 class="fw-bold mt-3" style="margin-bottom: -1px;" >IMEI <i class="fa-solid fa-arrow-up-1-9"></i></h3>
                <span style="font-size: 19px"><?= $data['imei']?></span>
                <h3 class="fw-bold mt-3" style="margin-bottom: -1px;" >SIM CARD # <i class="fa-solid fa-sim-card"></i></h3>
                <span style="text-transform: uppercase; font-size: 19px"><?= $data['simnum']?></span>
                <br>
                <a href="traveling.php?vid=<?= $data['vid']?>" class="btn btn-success mb-3 mt-3 text-center text-uppercase fw-bold p-2">Acknowledge <i class="fa-solid fa-handshake fs-5" ></i></a>
            </div>
        </div>
    </div>   
</body>
</html>
