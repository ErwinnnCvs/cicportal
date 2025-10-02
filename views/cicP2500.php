<?php
$key = "RA3019";
if ($_POST['sbtSingleUpload']) {

$provcode = trim($_POST['selectProvcode']);
$se = addslashes($_POST['subentity']);
$firstname = addslashes($_POST['firstname']);
$middlename = addslashes($_POST['middlename']);
$lastname = addslashes($_POST['lastname']);
$extname = addslashes($_POST['extname']);
$position = addslashes($_POST['position']);
$email = addslashes($_POST['email']);


$check_existing_code = $dbh4->query("SELECT fld_ctrlno FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$provcode."'");
$cec=$check_existing_code->fetch_array();
  
  if ($cec['fld_ctrlno']) {
    $msg = "Submitting Entity already uploaded";
  } else {

    $q = $dbh4->query("SELECT * FROM tbentities ORDER BY fld_ctrlno DESC");
    $v = $q->fetch_array();

    if(date("m") <> substr($v['fld_ctrlno'],4,2)){
        $ctrlno = date("Ym")."0001";
    }else{
        $seq = substr($v['fld_ctrlno'],7);
        $ctrlno = substr($v['fld_ctrlno'],0,6).str_pad(((int)$seq + 1), 4, "0", STR_PAD_LEFT);
    }

    $code = $ctrlno.$key;
    //Print out my column data.
    $vcode = $auth->randomString();
    $counter = 1;
    if ($dbh4->query("INSERT INTO tbentities (fld_provcode, fld_name, fld_fname_ar, fld_mname_ar, fld_lname_ar, fld_extname_ar, fld_position_ar, fld_email_ar, fld_ctrlno, fld_verification_code, fld_ceis_reg_auth_uploaded_by) VALUES (AES_ENCRYPT('".$provcode."', MD5('".$code."')), AES_ENCRYPT('".addslashes($se)."', MD5('".$code."')), AES_ENCRYPT('".$firstname."', MD5('".$code."')), AES_ENCRYPT('".$middlename."', MD5('".$code."')), AES_ENCRYPT('".$lastname."', MD5('".$code."')), AES_ENCRYPT('".$extname."', MD5('".$code."')), AES_ENCRYPT('".$position."', MD5('".$code."')), AES_ENCRYPT('".$email."', MD5('".$code."')), '".$ctrlno."', '".$vcode."', '".$_SESSION['name']."')")) {
      unset($_POST['selectProvcode']);
      $msg = "SUCCESS";
      $msgclr = "success";
    } else {
      $msg = "ERROR";
      $msgclr = "danger";
    } 
  }
  
}

if ($_POST['sbtBatchUpload']) {
  $file = pathinfo($_FILES["exampleInputFile"]["name"],PATHINFO_EXTENSION);
  if($file == "csv") {
      $myfile = fopen($_FILES["exampleInputFile"]["tmp_name"], "r");

      $flag = true;

      $key = "RA3019";
      $counter = 1; 

      while (($row = fgetcsv($myfile, 10000, ",")) !== FALSE) {
          $q = $dbh4->query("SELECT * FROM tbentities ORDER BY fld_ctrlno DESC");
          $v = $q->fetch_array();

          if(date("m") <> substr($v['fld_ctrlno'],4,2)){
              $ctrlno = date("Ym")."0001";
          }else{
              $seq = substr($v['fld_ctrlno'],7);
              $ctrlno = substr($v['fld_ctrlno'],0,6).str_pad(((int)$seq + 1), 4, "0", STR_PAD_LEFT);
          }

          $code = $ctrlno.$key;
          //Print out my column data.
          $vcode = $auth->randomString();
          if($flag) { $flag = false; continue; }

    $check_existing_code = $dbh4->query("SELECT fld_ctrlno FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$row[0]."'");
    $cec=$check_existing_code->fetch_array();

        if ($cec['fld_ctrlno']) {
            $msg = "Submitting Entity already uploaded";
        } else {
        $dbh4->query("INSERT INTO tbentities (fld_provcode, fld_name, fld_fname_ar, fld_mname_ar, fld_lname_ar, fld_extname_ar, fld_position_ar, fld_email_ar, fld_ctrlno, fld_verification_code, fld_ceis_reg_auth_uploaded_by)
            VALUES (AES_ENCRYPT('".$row[0]."', MD5('".$code."')), AES_ENCRYPT('".addslashes($row[1])."', MD5('".$code."')), AES_ENCRYPT('".$row[2]."', MD5('".$code."')), AES_ENCRYPT('".$row[3]."', MD5('".$code."')), AES_ENCRYPT('".$row[4]."', MD5('".$code."')), AES_ENCRYPT('".$row[5]."', MD5('".$code."')), AES_ENCRYPT('".$row[6]."', MD5('".$code."')), AES_ENCRYPT('".$row[7]."', MD5('".$code."')), '".$ctrlno."', '".$vcode."', '".$_SESSION['name']."')");
        }
    }
  }
}


?>
<!-- Main content -->
<section class="content">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">List of Uploaded SE</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
            <?php
              if ($msg) {
            ?>
              <div class="callout callout-<?php echo $msgclr;?>">
                <p><?php echo $msg;?></p>
              </div>
            <?php
              }
            ?>
            
            <button id="btnTrigSingle" type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-upload" style="float: right;">
              Single Upload
            </button>

            <div class="modal fade" id="modal-upload">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Upload SE</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                  <form method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group ">
                                <label for="exampleInputEmail1">Provider Code</label>
                                <select name="selectProvcode" class="form-control select2" id="exampleFormControlSelect1" onchange="submit()">
                                  <option value="">Select provider code</option>
                                  <?php
                                  if ($_POST['selectProvcode']) {
                                    $input_disable = '';
                                  }else{
                                    $input_disable = ' disabled';
                                  }

                                  $sel_provcode[$_POST['selectProvcode']] = ' selected';
                                  $sql_list = $dbh4->query("SELECT AES_DECRYPT(fld_provcode, MD5('gy4Gg47sHKNcWdm3')) AS provcode, fld_name As name FROM `tbentities_temp` WHERE AES_DECRYPT(fld_provcode, MD5('gy4Gg47sHKNcWdm3')) NOT IN (SELECT AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) FROM tbentities) ORDER BY provcode");
                                  while ($r_list = $sql_list->fetch_array()) {
                                    $sel_list_name[$r_list['provcode']] = $r_list['name'];
                                  ?>
                                  <option value="<?php echo $r_list['provcode'];?>"<?php echo $sel_provcode[$r_list['provcode']];?>><?php echo $r_list['provcode']." - ".$r_list['name'];?></option>
                                  <?php
                                  }
                                  $_POST['subentity'] = $sel_list_name[$_POST['selectProvcode']];
                                  ?>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-6" style="display: none;">
                            <div class="form-group">
                                <label for="exampleInputPassword1">Entity Name</label>
                                <input type="text" name="subentity" value="<?php echo $_POST['subentity'];?>" class="form-control" id="exampleInputPassword1" placeholder="" required>
                            </div>
                        </div>
                    </div>
                    <h3 class="page-header">AUTHORIZED REPRESENTATIVE</h3>
                      <div class="row">
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label for="exampleInputPassword1">First Name</label>
                                  <input type="text" name="firstname" class="form-control" id="exampleInputPassword1" autocomplete="off" placeholder="e.g Juan"<?php echo $input_disable;?> required>
                              </div>
                              <div class="form-group">
                                  <label for="exampleInputPassword1">Middle Name</label>
                                  <input type="text" name="middlename" class="form-control" id="exampleInputPassword1" autocomplete="off" placeholder="e.g Middle"<?php echo $input_disable;?>>
                              </div>
                              <div class="form-group">
                                  <label for="exampleInputPassword1">Last Name</label>
                                  <input type="text" name="lastname" class="form-control" id="exampleInputPassword1" autocomplete="off" placeholder="e.g Dela Cruz"<?php echo $input_disable;?> required>
                              </div> 
                          </div>
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label for="exampleInputPassword1">Ext Name</label>
                                  <input type="text" name="extname" class="form-control" id="exampleInputPassword1" autocomplete="off" placeholder="e.g Jr."<?php echo $input_disable;?>>
                              </div>
                              <div class="form-group">
                                  <label for="exampleInputPassword1">Position</label>
                                  <input type="text" name="position" class="form-control" id="exampleInputPassword1" autocomplete="off" placeholder="e.g Manager, etc."<?php echo $input_disable;?> required>
                              </div>
                              <div class="form-group">
                                  <label for="exampleInputPassword1">Email</label>
                                  <input type="email" name="email" class="form-control" id="exampleInputPassword1" autocomplete="off" placeholder="e.g juandelacruz@gmail.com"<?php echo $input_disable;?> required>
                              </div> 
                          </div>
                      </div>
                      <button type="submit" name="sbtSingleUpload" value="1" class="btn btn-success">Save</button>
                    </form>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>

          <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-batchUpload">
            Batch Upload
          </button> -->

          <div class="modal fade" id="modal-batchUpload">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                  <h4 class="modal-title">Batch Upload SE</h4>
                </div>
                <div class="modal-body">
                  <form method="post" enctype="multipart/form-data">
                      <label for="exampleInputFile">File input</label>
                      <input type="file" name="exampleInputFile" id="exampleInputFile">
                      <br>
                      <button type="submit" name="sbtBatchUpload" value="1" class="btn btn-primary btn-block">Upload</button>
                  </form>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <br/><br/>


          <table class="table table-bordered" style="table-layout: fixed; width: 100%;">
            <thead>
              <tr>
                <th>Control No.</th>
                <th>Provider Code</th>
                <th>Entity Name</th>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Last Name</th>
                <th>Extension Name</th>
                <th>Position</th>
                <th>Email</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $get_all_uploaded_ses = $dbh4->query("SELECT fld_ctrlno, aes_decrypt(fld_provcode, md5(concat(fld_ctrlno, 'RA3019'))) as provcode, aes_decrypt(fld_name, md5(concat(fld_ctrlno, 'RA3019'))) as name, aes_decrypt(fld_fname_ar, md5(concat(fld_ctrlno, 'RA3019'))) as fname_ar, aes_decrypt(fld_mname_ar, md5(concat(fld_ctrlno, 'RA3019'))) as mname_ar, aes_decrypt(fld_lname_ar, md5(concat(fld_ctrlno, 'RA3019'))) as lname_ar, aes_decrypt(fld_extname_ar, md5(concat(fld_ctrlno, 'RA3019'))) as extname_ar, aes_decrypt(fld_position_ar, md5(concat(fld_ctrlno, 'RA3019'))) as position_ar, aes_decrypt(fld_email_ar, md5(concat(fld_ctrlno, 'RA3019'))) as email_ar FROM tbentities ORDER BY fld_ctrlno DESC");
                while ($gaus=$get_all_uploaded_ses->fetch_array()) {
              ?>
              <tr>
                <td><?php echo $gaus['fld_ctrlno']; ?></td>
                <td><?php echo $gaus['provcode']; ?></td>
                <td><?php echo $gaus['name']; ?></td>
                <td><?php echo $gaus['fname_ar']; ?></td>
                <td><?php echo $gaus['mname_ar']; ?></td>
                <td><?php echo $gaus['lname_ar']; ?></td>
                <td><?php echo $gaus['extname_ar']; ?></td>
                <td><?php echo $gaus['position_ar']; ?></td>
                <td><?php echo $gaus['email_ar']; ?></td>
              </tr>
              <?php
                }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
if (isset($_POST['selectProvcode'])) {
?>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function(event) {
  document.getElementById("btnTrigSingle").click();
})
</script>
<?php
}
?>
