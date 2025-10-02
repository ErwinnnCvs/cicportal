<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
  if ($_POST['sbtBatchUATAccess']) {
    $controlno = $_POST['controlno'];

    $get_entity_information = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provcode, fld_mnemonics FROM tbentities WHERE fld_ctrlno = '".$controlno."'");
    $gei=$get_entity_information->fetch_array();
    

    $get_uat_operators = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_fname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fname, AES_DECRYPT(fld_mname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as mname, AES_DECRYPT(fld_lname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as lname, AES_DECRYPT(fld_email, md5(CONCAT(fld_ctrlno, 'RA3019'))) as email FROM tboperators WHERE fld_ctrlno = '".$controlno."' and fld_uat = 1 and fld_delete = 0");
    
    $fp = fopen('pdf/uat/credentials/UAT-Users_'.$gei['name'].'.csv', 'w');
    $placed_header = false;
    while ($guo=$get_uat_operators->fetch_array()) {





      $fname = substr($guo['fname'], 0, 1);
      if(empty($guo['mname'])) {
        $mname = "N";
      } else {
        $mname = substr($guo['mname'], 0, 1);
      }
      $lname = substr($guo['lname'], 0, 1);
      $email = $guo['email'];
      $username = $gei['fld_mnemonics']."9".$fname.$mname.$lname;
      $password = $auth->generateRandomString(8);
      $arr = array($gei['fld_mnemonics'], $gei['provcode'], $username, $password, $guo['fname'], $guo['mname'], $guo['lname'], $email, "FTP UAT", $gei['name']);
      $head = array('Meme', 'ProviderCode', 'SamAccount', 'Password', 'Fname', 'Initial', 'LName', 'Email', 'Channel / Environment', 'Company Name');

      if(!$placed_header) {
          fputcsv($fp, $head);
          $placed_header = true;
      }

      fputcsv($fp, array_values($arr));
      
    }
    fclose($fp);

    if (file_exists("pdf/uat/credentials/UAT-Users_".$gei['name'].".csv")) {
      if ($dbh4->query("UPDATE tbentities SET fld_batch_uat_creds_status = 1, fld_batch_uat_creds_ts = '".date('Y-m-d H:i:s')."', fld_batch_uat_creds_by = '".$_SESSION['name']."' WHERE fld_ctrlno = '".$controlno."' ")) {
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
              <li class="dropdown-item"><a href="main.php?nid=23&sid=0&rid=0">Pending</a></li>
              <li class="dropdown-item"><a href="main.php?nid=23&sid=1&rid=1">Generated</a></li>
              <!-- <li class="dropdown-item"><a href="main.php?nid=3&sid=0&rid=0&fstatus=3">Completed</a></li> -->
            </ul>
          </div>
        </div>
        <div class="card-body table-responsive p-0" style="height: 700px;">
          <table class="table table-hover table-striped">
            <thead>
              <tr>
                <th><center>#</center></th>
                <th>Provider Code</th>
                <th>Mnemonic</th>
                <th>Company Name</th>
                <th>Date Time</th>
                <th><center>Action</center></th>
              </tr>
            </thead>
            <tbody>
                <?php
                  $get_entity_for_generation = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provcode, fld_mnemonics, fld_batch_ops_reg FROM tbentities WHERE fld_batch_uat_creds_status = 0 and fld_registration_type = 1 and fld_batch_ops_reg IS NOT NULL");
                  while ($gefg=$get_entity_for_generation->fetch_array()) {
                    $c++;
                ?>
                <form method="post">
                <tr>
                  <input type="hidden" name="controlno" value="<?php echo $gefg['fld_ctrlno']; ?>">
                  <td><center><?php echo $c; ?></center></td>
                  <td><?php echo $gefg['provcode']; ?></td>
                  <td><?php echo $gefg['fld_mnemonics']; ?></td>
                  <td><?php echo $gefg['name']; ?></td>
                  <td><?php echo date("F d, Y H:ia", strtotime($gefg['fld_batch_ops_reg'])); ?></td>
                  <td>
                    <center>
                      <button class="btn btn-primary" name="sbtBatchUATAccess" value="1">Generate</button>
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