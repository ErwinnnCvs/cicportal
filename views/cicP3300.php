<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
$dbh7 = new mysqli("10.250.111.80", "evaluser", 'xc&m9rSCkuXY2', "evaluation");




if ($_POST['sbtAttend']) {
  $pid = $_POST['sbtAttend'];
  if ($dbh7->query("UPDATE demo SET fld_status = 1, fld_answers_override = '".$_SESSION['user_id'].'|'.$_SESSION['name'].'|'.date("Y-m-d H:i:s").'|'.addslashes($_POST['justification'])."' WHERE id = '".$_POST['sbtAttend']."'")) {
    $msg = 'Survey Link sending...';
        $msgclr = 'success';
  }
  
}

if ($_POST['sbtNoShow']) {
  
  if ($dbh7->query("UPDATE demo SET fld_status = 9 WHERE id = '".$_POST['sbtNoShow']."'")) {
    $msg = 'Record saved!';
        $msgclr = 'success';
  }
  
}

if ($_POST['sbtEdit']) {
  
  
  $stmt = $dbh7->prepare("UPDATE demo SET firstname = AES_ENCRYPT(?, CONCAT(id, 'PLANNER')), initial = AES_ENCRYPT(?, CONCAT(id, 'PLANNER')), surname = AES_ENCRYPT(?, CONCAT(id, 'PLANNER')), email = AES_ENCRYPT(?, CONCAT(id, 'PLANNER')), fld_edited_by = '".$_SESSION['user_id'].'|'.$_SESSION['name']."', fld_edited_ts = '".date("Y-m-d H:i:s")."', fld_questionnaire_link_sent = NULL WHERE id = ?");
  $stmt->bind_param('ssssi', strtoupper($_POST['i-fname']), strtoupper($_POST['i-initial']), strtoupper($_POST['i-sname']), $_POST['i-email'], $_POST['i-id']);
  $stmt->execute();
  if ($stmt->affected_rows) {
    $msg = 'Updated successfully.';
    $msgclr = 'success';
  }else{
    $msg = 'Sorry, something went wrong.';
    $msgclr = 'danger';
  }
        
}

if ($_POST['sbtBatchUpload']) {
  $ext = strtoupper(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
  if ($ext == 'CSV') {
    $lines = file($_FILES["fileToUpload"]["tmp_name"]);
    $rownumber = 0;


    if ($_POST['uploadType'] == '1') {
      $sql = "INSERT INTO demo (id, fld_insert_ts, fld_timestamp, email, fld_company, fld_office, surname, firstname, initial, fld_suffix, gender, fld_nationality, fld_designation, fld_contact, fld_how, fld_is_registered, trainingdate, fld_orientation_attend, fld_training_attended_before, fld_training_attended_before_date, fld_id_link) VALUES ";
      $errors = '';
      foreach($lines as $line) {
          $rownumber++;
          if ($rownumber >= 2 && trim($line)) {
            ##Validations
            $value = str_getcsv($line, ',');
            $timestamp = $value[0];
            $email = $value[1];
            $company_name = $value[2];
            $office_address = $value[3];
            $lname = $value[4];
            $fname = $value[5];
            $mname = $value[6];
            $suffix = $value[7];
            $sex = $value[8];
            $nationality = $value[9];
            $designation = $value[10];
            $contact_no = $value[11];
            $how = $value[12];
            $is_registered = $value[13];
            $schedule =  date("Y-m-d", strtotime($value[15]));
            $orientation_attend = $value[16];
            $training_attended_before = $value[17];
            $training_attended_before_date = $value[18];
            $id_link = $value[19];


            $error = '';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
              $error .= ' invalid email address,';
            }
            if (!trim($fname) || !trim($lname) || !trim($sex)) {
              $error .= ' required fields (Firstname, Surname, Gender),';
            }
            if ($schedule == '1970-01-01') {
              $error .= ' invalid training date,';
            }

            if($error){
              $errors .= 'Error in row '.$rownumber.': '.substr($error, 0, -1).'.<br/>';
            }



            if (!$refstart) {
              # GENERATE CERTIFICATE NUMBER
              $sql_id = $dbh7->query("SELECT id FROM `demo` WHERE id LIKE '".date("ymd")."%' ORDER BY id DESC");
              if ($r_id = $sql_id->fetch_array()) {
                $refstart = $r_id['id'] + 1;
              }else{
                $refstart = date("ymd").'0001';
              }
            }else{
              $refstart = $refstart + 1;
            }

            

            $sql .= "(
              '".$refstart."',
              '".date("Y-m-d H:i:s")."',
              '".$timestamp."', 
              AES_ENCRYPT('".$email."', CONCAT('".$refstart."', 'PLANNER')), 
              '".$company_name."', 
              '".$office_address."', 
              AES_ENCRYPT('".$lname."', CONCAT('".$refstart."', 'PLANNER')), 
              AES_ENCRYPT('".$fname."', CONCAT('".$refstart."', 'PLANNER')), 
              AES_ENCRYPT('".$mname."', CONCAT('".$refstart."', 'PLANNER')), 
              AES_ENCRYPT('".$suffix."', CONCAT('".$refstart."', 'PLANNER')), 
              '".$sex."', 
              '".$nationality."', 
              '".$designation."', 
              '".$contact_no."', 
              '".$how."', 
              '".$is_registered."', 
              '".$schedule."', 
              '".$orientation_attend."', 
              '".$training_attended_before."', 
              '".$training_attended_before_date."', 
              '".$id_link."'
            ), ";

          
            
          } 
      }





    }elseif ($_POST['uploadType'] == '2') {
      $sql = "INSERT INTO demo (id, fld_source, email, fld_phase, fld_company, fld_office, surname, firstname, initial, gender, fld_nationality, fld_designation, fld_contact, fld_how, fld_is_registered, trainingdate, fld_orientation_attend, fld_training_attended_before, fld_training_attended_before_date, fld_id_link) VALUES ";

      $errors = '';
      foreach($lines as $line) {
          $rownumber++;
          if ($rownumber >= 3) {
            ##Validations
            $value = str_getcsv($line, ',');
            $schedule = date("Y-m-d", strtotime($value[14]));
            $fname = $value[6];
            $mname = $value[7];
            $lname = $value[5];
            $email = trim($value[1]);
            $sex = $value[8];
            $nationality = $value[9];

            $error = '';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
              $error .= ' invalid email address,';
            }
            if (!trim($fname) || !trim($lname) || !trim($sex)) {
              $error .= ' required fields (Firstname, Surname, Gender),';
            }
            if ($schedule == '1970-01-01') {
              $error .= ' invalid training date,';
            }

            if($error){
              $errors .= 'Error in row '.$rownumber.': '.substr($error, 0, -1).'.<br/>';
            }



            if (!$refstart) {
              # GENERATE CERTIFICATE NUMBER
              $sql_id = $dbh7->query("SELECT id FROM `demo` WHERE id LIKE '".date("ymd")."%' ORDER BY id DESC");
              if ($r_id = $sql_id->fetch_array()) {
                $refstart = $r_id['id'] + 1;
              }else{
                $refstart = date("ymd").'0001';
              }
            }else{
              $refstart = $refstart + 1;
            }


            $sql .= "('".$refstart."', '".$value[0]."', AES_ENCRYPT('".$email."', CONCAT('".$refstart."', 'PLANNER')), '".$value[2]."', '".addslashes($value[3])."', '".addslashes($value[4])."', AES_ENCRYPT('".strtoupper($value[5])."', CONCAT('".$refstart."', 'PLANNER')), AES_ENCRYPT('".strtoupper($value[6])."', CONCAT('".$refstart."', 'PLANNER')), AES_ENCRYPT('".strtoupper($value[7])."', CONCAT('".$refstart."', 'PLANNER')), '".$value[8]."', '".$value[9]."', '".addslashes($value[10])."', '".$value[11]."', '".addslashes($value[12])."', '".$value[13]."', '".$schedule."', '".$value[15]."', '".$value[16]."', '".$value[17]."', '".addslashes($value[18])."'), ";

            
          } 
      }
    }
   






   


    if (!$errors) {

      $sql = substr($sql, 0, -2);
      if ($dbh7->query($sql)) {
        echo "<form id='frdirect' method='POST'><input type='hidden' name='msg' value='Uploaded successfully!'><input type='hidden' name='msgclr' value='success'></form><script>document.getElementById('frdirect').submit();</script>";
        exit;
      }
    }else{
      $msg = $errors.'<i>If values are correct, please check if the selected source is correct.</i>';
      $msgclr = 'danger';
    }




    
  }else{
    $msg = 'Please upload csv file only.';
    $msgclr = 'danger';
  }


}


if ($_POST['msg']) {
  $msg = $_POST['msg'];
  $msgclr = $_POST['msgclr'];
}


?>
<!-- Main content -->
<section class="content">

  <?php
  if (!$_GET['cid']) {
  ?>
  <div class="row" style="margin-top: 100px;">
      <div class="col-md-2"></div>
      <div class="col-md-3">
        <a href="main.php?nid=33&sid=0&rid=0&cid=1">
          <button type="button" name="sbtBatchUpload" class="btn btn-primary btn-block" value="1">Training Attendance</button>
        </a>
      </div>
      <div class="col-md-2"></div>
      <div class="col-md-3">
        <a href="main.php?nid=33&sid=1&rid=0">
          <button type="button" name="sbtBatchUpload" class="btn btn-secondary btn-block" value="1">Training Scheduler</button>
        </a>
      </div>
    </div>
  <?php
  }elseif ($_POST['btnFileUpload'] || $_POST['sbtBatchUpload']) {
    if (!$_POST['uploadType']) {
      $_POST['uploadType'] = 1;
    }
    $chk[$_POST['uploadType']] = ' checked';
  ?>
  <div class="row">
      <div class="col-md-4"></div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <?php
              if ($msg) {
            ?>
              <div class="callout callout-<?php echo $msgclr;?>">
                <p><?php echo $msg;?></p>
              </div>
            <?php
              }
            ?>
            <h3 class="page-header">Registered for Training</h3><br/>
            <form method="POST" enctype="multipart/form-data">
              <div class="form-group">
                <input type="file" name="fileToUpload" required><br/>
                <p class="help-block">.csv file only</p>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="uploadType" id="exampleRadios1" value="1"<?php echo $chk['1'];?>>
                  <label class="form-check-label" for="exampleRadios1">
                    Form responses <small>( Default )</small>
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="uploadType" id="exampleRadios2" value="2"<?php echo $chk['2'];?>>
                  <label class="form-check-label" for="exampleRadios2">
                    Registration via email
                  </label>
                </div><br/><br/>
                <button type="submit" name="sbtBatchUpload" class="btn btn-primary btn-block" value="1">Upload File</button>
                
                <!-- <button class="btn btn-default btn-block" name="sbtFileUpload" value="1">Upload</button> -->
                

              </div>
            </form>
          </div>
        </div>
        
      </div>
      <div class="col-md-4"></div>
    </div>
  <?php
  }else{
  ?>
  &nbsp;&nbsp;&nbsp;<a href="main.php?nid=33&sid=0&rid=0">Go Back</a>
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">List of Registered Participants</h3>
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
              if (trim($_POST['filter']) == '') {
                $_POST['filter'] = 0;
              }
              $sel_filter[$_POST['filter']] = ' selected';
            ?>
            <form method="post">
              <select name="filter" class="form-control" style="width:auto; display: inline;" onchange="submit()">
                <option value="0"<?php echo $sel_filter[0];?>>Registered</option>
                <option value="1"<?php echo $sel_filter[1];?>>Pending Survey</option>
                <option value="2"<?php echo $sel_filter[2];?>>Survey Done</option>
                <option value="9"<?php echo $sel_filter[9];?>>No Show</option>
              </select>
              <button name="btnFileUpload" type="submit" onchange="submit()" value="1" class="btn btn-success" style="float: right;">
                Batch Upload
              </button>
              <a href="main.php?nid=33&sid=2&rid=0" class="btn btn-link" style="float: right; margin-right: 10px;">Questionnaire</a>
            </form>
            

          

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

          <table id="tbevaluation" class="table table-bordered table-striped" style="table-layout: fixed; width: 100%;">
            <thead>
              <tr>
                <th width="20px" class="no-sort"></th>
                <th>Training Date</th>
                <th>First Name</th>
                <th width="">Middle Initial</th>
                <th>Last Name</th>
                <th>Suffix</th>
                <th>Email</th>
                <th width="150px"></th>
              </tr>
            </thead>
            <tbody>
              <?php
              $ctr = 1;
              if ($_POST['filter'] == 0) {
                // $sql_recent = $dbh7->query("SELECT trainingdate FROM demo WHERE trainingdate <= '".date("Y-m-d", strtotime('+ 1 day'))."' ORDER BY trainingdate DESC LIMIT 1;");
                $sql_recent = $dbh7->query("SELECT t.trainingdateone trainingdate FROM trainer t JOIN demo d ON t.trainingdateone = d.trainingdate WHERE trainingdate <= (SELECT trainingdateone FROM trainer WHERE trainingdateone >= (SELECT trainingdateone FROM trainer WHERE trainingdateone <= '".date("Y-m-d")."' ORDER BY trainingdateone DESC LIMIT 1) ORDER BY trainingdateone DESC LIMIT 1) GROUP BY trainingdate ORDER BY trainingdate DESC LIMIT 1;");
                $r_recent = $sql_recent->fetch_array();

                $sql = $dbh7->query("SELECT fld_status, id, trainingdate, AES_DECRYPT(firstname, CONCAT(id, 'PLANNER')) AS firstname, AES_DECRYPT(initial, CONCAT(id, 'PLANNER')) AS initial, AES_DECRYPT(surname, CONCAT(id, 'PLANNER')) AS surname, AES_DECRYPT(fld_suffix, CONCAT(id, 'PLANNER')) AS suffix, AES_DECRYPT(email, CONCAT(id, 'PLANNER')) AS email, fld_cert_no, fld_survey_link_sent FROM demo WHERE fld_status = '".$_POST['filter']."' AND trainingdate = '".$r_recent['trainingdate']."' ORDER BY id");
              }elseif($_POST['filter'] == 1){
                  $sql = $dbh7->query("SELECT fld_status, id, trainingdate, AES_DECRYPT(firstname, CONCAT(id, 'PLANNER')) AS firstname, AES_DECRYPT(initial, CONCAT(id, 'PLANNER')) AS initial, AES_DECRYPT(surname, CONCAT(id, 'PLANNER')) AS surname, AES_DECRYPT(fld_suffix, CONCAT(id, 'PLANNER')) AS suffix, AES_DECRYPT(email, CONCAT(id, 'PLANNER')) AS email, fld_cert_no, fld_survey_link_sent, fld_answer1, fld_answer2 FROM demo WHERE fld_status = '".$_POST['filter']."' OR (fld_status = '0' AND fld_answer1 IS NOT NULL AND fld_answer2 IS NOT NULL AND fld_survey_link_sent IS NOT NULL) ORDER BY id");
              }else{
                $sql = $dbh7->query("SELECT fld_status, id, trainingdate, AES_DECRYPT(firstname, CONCAT(id, 'PLANNER')) AS firstname, AES_DECRYPT(initial, CONCAT(id, 'PLANNER')) AS initial, AES_DECRYPT(surname, CONCAT(id, 'PLANNER')) AS surname, AES_DECRYPT(fld_suffix, CONCAT(id, 'PLANNER')) AS suffix, AES_DECRYPT(email, CONCAT(id, 'PLANNER')) AS email, fld_cert_no, fld_survey_link_sent FROM demo WHERE fld_status = '".$_POST['filter']."' ORDER BY id");
              }
                
                while ($r=$sql->fetch_array()) {
                  $name = $r['firstname'].($r['initial']? ' '.substr($r['initial'], 0, 1).'. ':'').$r['surname'];

              ?>
              <tr>
                <td align="center"><?php echo $ctr++;?></td>
                <td><?php echo $r['trainingdate']; ?></td>
                <td><?php echo $r['firstname']; ?></td>
                <td><?php echo $r['initial']? substr($r['initial'], 0, 1).'.': ''; ?></td>
                <td><?php echo $r['surname']; ?></td>
                <td><?php echo $r['suffix']; ?></td>
                <td><?php echo $r['email']; ?></td>
                <td width="100px" align="center">
                  <?php
                  if ($r['fld_status'] == '0' && !$r['fld_survey_link_sent']) {
                   ?>
                   <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                    <input type="hidden" name="name" value="<?php echo $name; ?>">
                    <button type="submit" name="btnEdit" class="btn btn-primary btn-sm btnEdit" value="1"><i class="fa fa-edit" aria-hidden="true"></i></button>&nbsp;&nbsp;
                    <button type="button" class="btn btn-primary btn-sm btnAttend" value="1"><i class="fa fa-check" aria-hidden="true"></i></button>&nbsp;&nbsp;
                    <button type="button" class="btn btn-danger btn-sm btnNoAttend" value="1"><i class="icon-remove" aria-hidden="true">No Show</i></button>
                  </form>
                   <?php
                  }elseif ($r['fld_status'] == '1' || ($r['fld_answer1'] && $r['fld_answer2'] && $r['fld_status'] < 2)) {
                    if ($r['fld_survey_link_sent']) {
                      echo '<i>Link sent on </i>'.date("M j, Y g:i a", strtotime($r['fld_survey_link_sent']));
                    }else{
                      echo '<i>Survey Link sending...</i>';
                    }
                  ?>
                  
                  <?php
                  }elseif ($r['fld_status'] == '2') {
                  echo 'CN-'.substr($r['fld_cert_no'], 0, 4).'-'.substr($r['fld_cert_no'], 4, 2).'-'.substr($r['fld_cert_no'], 6, 2).'-'.substr($r['fld_cert_no'], 8);
                  }
                  ?>
                </td>
              </tr>
              <?php
                }
                if ($sql->num_rows < 1) {
              ?>
              <tr>
                <td colspan="7" align="center">No record</td>
              </tr>
              <?php
                }
              ?>
            </tbody>
          </table>
          
          <div class="modal fade" id="modal-attended">
            <div class="modal-dialog">
              <div class="modal-content">
                <form method="post">
                <div class="modal-body">
                      <br>
                      <h5>Are you sure <span id="attended_name"></span> attended?</h5>
                      <br>
                      <div class="form-group">
                        <label for="exampleFormControlTextarea1">Justification:</label>
                        <textarea name="justification" class="form-control" id="exampleFormControlTextarea1" rows="3" required></textarea>
                      </div>
                </div>
                <div class="modal-footer">
                  

                  <button type="submit" name="sbtAttend" id="attended_btn" class="btn btn-primary">Yes</button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  
                </div>
                </form>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>


          <div class="modal fade" id="modal-noshow">
            <div class="modal-dialog">
              <div class="modal-content">
                
                <div class="modal-body">
                      <br>
                      <h5>Are you sure <span id="noattended_name"></span> did <b>not</b> attended?</h5>
                      <br>
                  
                </div>
                <div class="modal-footer">
                  <form method="post">
                  <button type="submit" name="sbtNoShow" id="noshow_btn" class="btn btn-danger">No Show</button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  </form>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>

          <?php
          if ($_POST['btnEdit'] == '1') {
            // print_r($_POST);
            $sql = $dbh7->query("SELECT fld_status, id, trainingdate, AES_DECRYPT(firstname, CONCAT(id, 'PLANNER')) AS firstname, AES_DECRYPT(initial, CONCAT(id, 'PLANNER')) AS initial, AES_DECRYPT(surname, CONCAT(id, 'PLANNER')) AS surname, AES_DECRYPT(fld_suffix, CONCAT(id, 'PLANNER')) AS suffix, AES_DECRYPT(email, CONCAT(id, 'PLANNER')) AS email, fld_cert_no, fld_survey_link_sent, fld_questionnaire_link_sent FROM demo WHERE id = '".$_POST['id']."'");
            $r = $sql->fetch_array();
            // print_r($r);
          ?>
          <div class="modal fade" id="modal-edit">
            <div class="modal-dialog">
              <div class="modal-content">
                <form method="post">
                  <input type="hidden" name="i-id" value="<?php echo $r['id'];?>">
                <div class="modal-body">
                      <br>
                      <h5>Edit details</h5>
                      <br>
                      <div class="form-group row">
                        <label for="staticEmail" class="col-sm-3 col-form-label">Firstname</label>
                        <div class="col-sm-9">
                          <input type="text" name="i-fname" value="<?php echo $r['firstname'];?>" class="form-control">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="staticEmail" class="col-sm-3 col-form-label">Middle initial</label>
                        <div class="col-sm-9">
                          <input type="text" name="i-initial" value="<?php echo $r['initial'];?>" class="form-control">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="staticEmail" class="col-sm-3 col-form-label">Surname</label>
                        <div class="col-sm-9">
                          <input type="text" name="i-sname" value="<?php echo $r['surname'];?>" class="form-control">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="staticEmail" class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                          <input type="text" name="i-email" value="<?php echo $r['email'];?>" class="form-control">
                          <?php 
                          if($r['fld_questionnaire_link_sent']){
                          ?>
                          <font color="red">Warning: Answer link already sent to <?php echo $r['email'];?></font>
                          <?php
                          }
                          ?>
                        </div>
                      </div>
                      
                  
                </div>
                <div class="modal-footer">
                  <button type="submit" name="sbtEdit" value="1" class="btn btn-primary">Save</button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
                </form>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <script type="text/javascript">
  
            document.addEventListener("DOMContentLoaded", function(){
                $('#modal-edit').modal('show');
            });
          </script>
          <?php
          }
          ?>
        </div>
      </div>
    </div>
  </div>
  <?php
    }
  ?>

  
</section>


