<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
  if ($_POST['sbtGenerateDQUAAccess']) {
    $controlno = $_POST['controlno'];

    $get_entity_information = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, fld_dqua_sae_selected, fld_dqua_validation_appr_ts, fld_mnemonics, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provcode FROM tbentities WHERE fld_ctrlno = '".$controlno."'");
    $gei=$get_entity_information->fetch_array();

    if ($gei['fld_dqua_sae_selected'] == "SAE09670") {
      $dqnum = 1;
    } elseif ($gei['fld_dqua_sae_selected'] == "SAE09440") {
      $dqnum = 2;
    } elseif ($gei['fld_dqua_sae_selected'] == "SAE99999") {
      $dqnum = 5;
    }


    $username = $gei['fld_mnemonics']."3DQ".$dqnum;
    
    $password = $auth->generateRandomString(8);

    $list = array (
      array("MEME", "SamAccount", "Password", "Channel / Environment", "Company Name"),
      array($gei['fld_mnemonics'], $username, $password, "A2A", $gei['name']),
    );

    $file = fopen("pdf/dqua/credentials/DQUA_".$gei['provcode'].".csv","w");

    foreach ($list as $line) {
      fputcsv($file, $line);
    }

    fclose($file);

    if (file_exists("pdf/dqua/credentials/DQUA_".$gei['provcode'].".csv")) {
      if ($dbh4->query("UPDATE tbentities SET fld_dqua_cred_status = 1, fld_dqua_cred_ts = '".date('Y-m-d H:i:s')."', fld_dqua_cred_by = '".$_SESSION['name']."' WHERE fld_ctrlno = '".$controlno."' ")) {
        $msg = "Successfully generated";
        $msgclr = "success";
      } else {
        $msg = "An error occured in saving to database";
        $msgclr = "danger";
      }
    } else {
      $msg = "Unable to generate the file. Permission Denied";
      $msgclr = "danger";
    }
  }
?>
<!-- Main content -->
<section class="content">
  <!-- notification -->
  <?php
    if ($msg) {
  ?>
  <div class="alert alert-<?php echo $msgclr; ?> alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h5><i class="icon fas fa-check"></i> Information!</h5>
    <?php echo $msg; ?>
  </div>

  <?php
    }
  ?>

  <div class="row">
    <div class="col-10">
      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <div class="input-group-prepend">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              Pending Items
            </button>
            <ul class="dropdown-menu">
              <li class="dropdown-item"><a href="main.php?nid=11&sid=0&rid=0">Pending</a></li>
              <li class="dropdown-item"><a href="main.php?nid=11&sid=1&rid=1">Generated</a></li>
              <!-- <li class="dropdown-item"><a href="main.php?nid=3&sid=0&rid=0&fstatus=3">Completed</a></li> -->
            </ul>
          </div>
        </div>
        <div class="card-body table-responsive p-0" style="height: 700px;">
          <table class="table table-hover table-striped">
            <thead>
              <tr>
                <th><center>#</center></th>
                <th>Mnemonic</th>
                <th>Company Name</th>
                <th>Special Accessing Entity</th>
                <th>Date Time</th>
                <th><center>Action</center></th>
              </tr>
            </thead>
            <tbody>
                <?php
                  $get_entity_for_generation = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, fld_dqua_sae_selected, fld_dqua_validation_appr_ts, fld_mnemonics FROM tbentities WHERE fld_dqua_validation_approval_status = 1 and fld_dqua_cred_status = 0");
                  while ($gefg=$get_entity_for_generation->fetch_array()) {
                    $c++;
                ?>
                <form method="post">
                <tr>
                  <input type="hidden" name="controlno" value="<?php echo $gefg['fld_ctrlno']; ?>">
                  <td><center><?php echo $c; ?></center></td>
                  <td><?php echo $gefg['fld_mnemonics']; ?></td>
                  <td><?php echo $gefg['name']; ?></td>
                  <td><?php echo $sae_label[$gefg['fld_dqua_sae_selected']]; ?></td>
                  <td><?php echo date("F d, Y H:ia", strtotime($gefg['fld_dqua_validation_appr_ts'])); ?></td>
                  <td>
                    <center>
                      <button class="btn btn-primary" name="sbtGenerateDQUAAccess" value="1">Generate</button>
                    </center>
                  </td>
                </tr>
                </form>
                <?php
                  }
                ?>
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <div class="col-2">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">FILTERS</h3>
        </div>
        <div class="card-body">
          <form method="post">
            <label>Provider Code</label>
            <input type="text" name="filter-provider-code" class="form-control" placeholder="Any" value="<?php echo $_POST['filter-provider-code']; ?>">
            <br>
            <label>Name</label>
            <input type="text" name="filter-company-name" class="form-control" placeholder="Any" value="<?php echo $_POST['filter-company-name']; ?>">
            <br>
            <div class="form-group">
              <label>Type</label>
              <select class="form-control" name="filter-type">
                <option>All</option>
                <?php
                  foreach ($SE as $key => $value) {
                    echo "<option value='".$key."'".$seltyp[$key].">".$value."</option>";
                  }
                ?>
              </select>  
            </div>
            <div class="form-group">
              <label>Upload Date</label>
                
                <div class="input-group">
                  <button type="button" class="btn btn-default float-right" id="daterange-btn">
                    <i class="far fa-calendar-alt"></i> Date range picker
                    <i class="fas fa-caret-down"></i>
                  </button>
                </div>
                <div id="reportrange">
                  <input type="text" name="filter-date" id="filter-date" class="form-control" value="<?php echo $_POST['filter-date']; ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block" value="1" name="sbtFilter">Apply</button>
            <a href="main.php?nid=2&sid=0&rid=0" class="btn btn-default btn-block">Clear</a>
          </form>
        </div>
      </div>
      <!-- <div class="card">
        <div class="card-header">
          <h3 class="card-title">Upload Entity</h3>
        </div>
        <div class="card-body">
          <button type="submit" class="btn btn-secondary btn-block" value="1" name="sbtFilter">Upload</button>
        </div>
      </div> -->
    </div>
  </div>
</section>
<!-- /.content -->