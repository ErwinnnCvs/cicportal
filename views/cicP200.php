<?php

$user_limited = [87];

if ($_POST['sbtUpload']) {
  $key = "RA3019";

  $provcode = trim($_POST['provider-code']);
  $se = $_POST['entity-name'];
  $firstname = $_POST['first-name'];
  $middlename = $_POST['middle-name'];
  $lastname = $_POST['last-name'];
  $extname = $_POST['extension-name'];
  $position = $_POST['position'];
  $email = $_POST['email'];
  $pfirstname = $_POST['p-first-name'];
  $pmiddlename = $_POST['p-middle-name'];
  $plastname = $_POST['p-last-name'];
  $pextname = $_POST['p-extension-name'];
  $pposition = $_POST['p-position'];
  $pemail = $_POST['p-email'];

  $q = $dbh4->query("SELECT * FROM tbentities ORDER BY fld_ctrlno DESC");
  $v = $q->fetch_array();

  if(date("m") <> substr($v['fld_ctrlno'],4,2)){
      $ctrlno = date("Ym")."0001";
  }else{
      $seq = substr($v['fld_ctrlno'],7);
      $ctrlno = substr($v['fld_ctrlno'],0,6).str_pad(((int)$seq + 1), 4, "0", STR_PAD_LEFT);
  }

  $code = $ctrlno.$key;
  $vcode = $auth->randomString();
  if ($dbh4->query("INSERT INTO tbentities (fld_provcode, fld_name, fld_fname_ar, fld_mname_ar, fld_lname_ar, fld_extname_ar, fld_position_ar, fld_email_ar, fld_fname_c1, fld_mname_c1, fld_lname_c1, fld_extname_c1, fld_position_c1, fld_email_c1, fld_ctrlno, fld_verification_code, fld_registration_uploaded_by, fld_registration_type, fld_registration_upload) VALUES (AES_ENCRYPT('".$provcode."', MD5('".$code."')), AES_ENCRYPT('".addslashes($se)."', MD5('".$code."')), AES_ENCRYPT('".addslashes($firstname)."', MD5('".$code."')), AES_ENCRYPT('".addslashes($middlename)."', MD5('".$code."')), AES_ENCRYPT('".addslashes($lastname)."', MD5('".$code."')), AES_ENCRYPT('".addslashes($extname)."', MD5('".$code."')), AES_ENCRYPT('".addslashes($position)."', MD5('".$code."')), AES_ENCRYPT('".addslashes($email)."', MD5('".$code."')), AES_ENCRYPT('".addslashes($pfirstname)."', MD5('".$code."')), AES_ENCRYPT('".addslashes($pmiddlename)."', MD5('".$code."')), AES_ENCRYPT('".addslashes($plastname)."', MD5('".$code."')), AES_ENCRYPT('".addslashes($pextname)."', MD5('".$code."')), AES_ENCRYPT('".addslashes($pposition)."', MD5('".$code."')), AES_ENCRYPT('".addslashes($pemail)."', MD5('".$code."')), '".$ctrlno."', '".$vcode."', '".$_SESSION['name']."', '1', '".date("Y-m-d H:i:s")."')")) {
      $msg = "Successfully Uploaded.";
      $msgclr = "success";
  } else {
      $msg = "Error encountered uploading entity.";
      $msgclr = "danger";
  }
} 

if ($_POST['sbtBatchUpload']) {
  $path = $_FILES['batch_upload']['name'];
  $ext = pathinfo($path, PATHINFO_EXTENSION);
  
  if ($ext == "csv" or $ext == "CSV") {
    $target_dir = "files/csv/";
    $target_file = $target_dir . basename($_FILES["batch_upload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    if (move_uploaded_file($_FILES["batch_upload"]["tmp_name"], $target_file)) {
      $file = fopen($target_file, "r");
      //$sql_data = "SELECT * FROM prod_list_1 ";

      $count = 0;                                         // add this line
      while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
      {
          //print_r($emapData);
          //exit();
          $count++;                                      // add this line

          if($count>1){                                // add this line
            
            $provcode_csv = trim($emapData[0]);
            $se_csv = $emapData[1];
            $firstname_csv = $emapData[2];
            $middlename_csv = $emapData[3];
            $lastname_csv = $emapData[4];
            $extname_csv = $emapData[5];
            $position_csv = $emapData[6];
            $email_csv = $emapData[7];
            $pfirstname_csv = $emapData[8];
            $pmiddlename_csv = $emapData[9];
            $plastname_csv = $emapData[10];
            $pextname_csv = $emapData[11];
            $pposition_csv = $emapData[12];
            $pemail_csv = $emapData[13];

            $q = $dbh4->query("SELECT * FROM tbentities ORDER BY fld_ctrlno DESC");
            $v = $q->fetch_array();

            $key = "RA3019";

            if(date("m") <> substr($v['fld_ctrlno'],4,2)){
                $ctrlno = date("Ym")."0001";
            }else{
                $seq = substr($v['fld_ctrlno'],7);
                $ctrlno = substr($v['fld_ctrlno'],0,6).str_pad(((int)$seq + 1), 4, "0", STR_PAD_LEFT);
            }

            $code = $ctrlno.$key;
            $vcode = $auth->randomString();
            
            if ($dbh4->query("INSERT INTO tbentities (fld_provcode, fld_name, fld_fname_ar, fld_mname_ar, fld_lname_ar, fld_extname_ar, fld_position_ar, fld_email_ar, fld_fname_c1, fld_mname_c1, fld_lname_c1, fld_extname_c1, fld_position_c1, fld_email_c1, fld_ctrlno, fld_verification_code, fld_registration_uploaded_by, fld_registration_type, fld_registration_upload) VALUES (AES_ENCRYPT('".$provcode_csv."', MD5('".$code."')), AES_ENCRYPT('".addslashes($se_csv)."', MD5('".$code."')), AES_ENCRYPT('".addslashes($firstname_csv)."', MD5('".$code."')), AES_ENCRYPT('".addslashes($middlename_csv)."', MD5('".$code."')), AES_ENCRYPT('".addslashes($lastname_csv)."', MD5('".$code."')), AES_ENCRYPT('".addslashes($extname_csv)."', MD5('".$code."')), AES_ENCRYPT('".addslashes($position_csv)."', MD5('".$code."')), AES_ENCRYPT('".addslashes($email_csv)."', MD5('".$code."')), AES_ENCRYPT('".addslashes($pfirstname_csv)."', MD5('".$code."')), AES_ENCRYPT('".addslashes($pmiddlename_csv)."', MD5('".$code."')), AES_ENCRYPT('".addslashes($plastname_csv)."', MD5('".$code."')), AES_ENCRYPT('".addslashes($pextname_csv)."', MD5('".$code."')), AES_ENCRYPT('".addslashes($pposition_csv)."', MD5('".$code."')), AES_ENCRYPT('".addslashes($pemail_csv)."', MD5('".$code."')), '".$ctrlno."', '".$vcode."', '".$_SESSION['name']."', '1', '".date("Y-m-d H:i:s")."')")) {
              $msg = "The file ". htmlspecialchars( basename( $_FILES["batch_upload"]["name"])). " has been uploaded.";
              $msgclr = "success";
            } else {
              $msg = "Sorry, your file was not uploaded to database.";
            $msgclr = "danger";
            }
          }           
                            // add this line
      }
    } else {
      $msg = "Sorry, your file was not uploaded to directory.";
      $msgclr = "danger";
    }
  } else {
    $msg = "File is not csv, error uploading your file.";
    $msgclr = "danger";
  }
}

if (!$_POST['filter-provider-code'] and !$_POST['filter-company-name'] and !$_POST['filter-type'] and !$_POST['filter-date']) {
  $where = " WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) NOT LIKE '%SAE%' and fld_operational = 0 and fld_registration_type = 1 ORDER BY fld_registration_upload DESC";
}

if ($_POST['sbtFilter']) {
  if (!empty($_POST['filter-provider-code'])) {
    $whsql .= " and AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".addslashes($_POST['filter-provider-code'])."'";
  }

  if (!empty($_POST['filter-company-name'])) {
    $whsql .= " and AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) LIKE '%".addslashes($_POST['filter-company-name'])."%'";
  }

  if (!empty($_POST['filter-type'])) {
    if ($_POST['filter-type'] == "All") {
      $whsql .= "";
    } else {
      $whsql .= " and fld_type = '".addslashes($_POST['filter-type'])."'";
    }
  }

  if (!empty($_POST['filter-date'])) {
    $date_filter = explode(" - ", $_POST['filter-date']);

    $start_date = date("Y-m-d", strtotime($date_filter[0]));
    $end_date = date("Y-m-d", strtotime($date_filter[1]));

    $whsql .= " and fld_registration_upload BETWEEN '".$start_date."' AND '".$end_date."'";
  }



  $where = " WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) NOT LIKE '%SAE%' and fld_operational = 0 and fld_registration_type = 1 ".$whsql." ORDER BY fld_registration_upload DESC LIMIT 50";
}

$seltyp[$_POST['filter-type']] = " selected";
?>

<!-- Main content -->
<section class="content">

  <?php
    if ($msg) {
  ?>
  <div class="alert alert-<?php echo $msgclr; ?> alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h5><i class="icon fas fa-ban"></i> Information!</h5>
    <?php echo $msg; ?>
  </div>
  <?php
    }
  ?>
  <!-- Default box -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">List of Financial Institutions</h3>
          <?php
          if (!in_array($_SESSION['user_id'], $user_limited)) {
          ?>
          <div class="card-tools">
            <div class="btn-group">
              <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                Upload Entity
              </button>
              <div class="dropdown-menu dropdown-menu-right" role="menu">
                <a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal-lg">Single</a>
                <a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal-batch">Batch</a>
              </div>
            </div>
          </div>
          <?php
          }
          ?>
        </div>
        <div class="card-body table-responsive">
          <table class="table table-hover table-striped" id="re_se">
            <thead>
              <tr>
                <th>#</th>
                <th>Provider Code</th>
                <th>Entity Name</th>
                <th>Entity Type</th>
                <th>DateTime</th>
              </tr>
            </thead>
            <tbody>
            <?php
              $get_all_entities = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provider_code, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as entity_name, fld_type as entity_type, AES_DECRYPT(fld_fname_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fname_ar, AES_DECRYPT(fld_mname_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) as mname_ar, AES_DECRYPT(fld_lname_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) as lname_ar, AES_DECRYPT(fld_extname_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) as extname_ar, fld_registration_upload, fld_type FROM tbentities".$where);
              while ($gae=$get_all_entities->fetch_array()) {
                $c++;
            ?>
            <tr>
              <td><?php echo $c; ?></td>
              <td class="mailbox-star"><?php if(empty($gae['provider_code'])) { echo "N/A"; } else { echo $gae['provider_code']; } ?></td>
              <td class="mailbox-name"><a href="main.php?nid=2&sid=1&rid=1&ctrlno=<?php echo $gae['fld_ctrlno']; ?>"><?php echo $gae['entity_name']; ?></a></td>
              <td class="mailbox-subject"><?php echo $ent2[$gae['fld_type']]; ?></td>
              <td class="mailbox-date"><?php echo date("F d, Y", strtotime($gae['fld_registration_upload'])); ?></td>
            </tr>
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
    <!-- <div class="col-2">
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
    </div> -->
  </div>
  <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Entity Upload</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="post">
                <div class="card-body">
                  <div class="form-group row">
                    <label for="provider-code" class="col-sm-2 col-form-label">Provider Code</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="provider-code" id="provider-code" placeholder="Provider Code (Optional)">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="entity-name" class="col-sm-2 col-form-label">Entity Name</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="entity-name" id="entity-name" placeholder="Entity Name" required>
                    </div>
                  </div>
                  <hr>
                  <h3>Authorized Representative</h3>
                  <div class="form-group row">
                    <label for="first-name" class="col-sm-2 col-form-label">First Name</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="first-name" id="first-name" placeholder="First Name">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="middle-name" class="col-sm-2 col-form-label">Middle Name</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="middle-name" id="middle-name" placeholder="Middle Name">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="last-name" class="col-sm-2 col-form-label">Last Name</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="last-name" id="last-name" placeholder="Last Name">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="extension-name" class="col-sm-2 col-form-label">Ext. Name</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="extension-name" id="extension-name" placeholder="Extension Name">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="position" class="col-sm-2 col-form-label">Position</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="position" id="position" placeholder="Position">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                      <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                    </div>
                  </div>
                  <hr>
                  <h3>Primary Contact Person</h3>
                  <div class="form-group row">
                    <label for="p-first-name" class="col-sm-2 col-form-label">First Name</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="p-first-name" id="p-first-name" placeholder="First Name">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="p-middle-name" class="col-sm-2 col-form-label">Middle Name</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="p-middle-name" id="p-middle-name" placeholder="Middle Name">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="p-last-name" class="col-sm-2 col-form-label">Last Name</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="p-last-name" id="p-last-name" placeholder="Last Name">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="p-extension-name" class="col-sm-2 col-form-label">Ext. Name</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="p-extension-name" id="p-extension-name" placeholder="Extension Name">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="p-position" class="col-sm-2 col-form-label">Position</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="p-position" id="p-position" placeholder="Position">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="p-email" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                      <input type="email" class="form-control" name="p-email" id="p-email" placeholder="Email">
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-info float-right" name="sbtUpload" value="1">Save</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
                <!-- /.card-footer -->
              </form>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

      <!-- Batch UPload -->
      <div class="modal fade" id="modal-batch">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Entity Upload</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="exampleInputFile">File input</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" name="batch_upload" class="custom-file-input" id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                      </div>
                    </div>
                  </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-info float-right" name="sbtBatchUpload" value="1">Upload</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
                <!-- /.card-footer -->
              </form>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

</section>
<!-- /.content -->