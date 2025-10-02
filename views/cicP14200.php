<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

if($_POST['tableName'] == "contract"){
  $tbn = "contract";
  $tbs = "subject";
}else{
  $tbn = "contract2";
  $tbs = "subject2";
}

// print_r($_POST);
// die();

$error=  [];

  if ($_POST['sbtSubjectCode']) {
 
    if(!isset($_POST['classifyDisp']) ){
      array_push($error, 'Dispute Classification' );
    }

    if(!isset($_POST['classifyReso'])){
      array_push($error, 'Resolution Type' );
    }


    if($_POST['classifyReso'] == 1 || $_POST['classifyReso'] == 2 ||$_POST['classifyReso'] == 6 ){
      if(isset($_POST['subject_code']) && trim($_POST['subject_code']) == "" ){
        array_push($error, 'Subject Code' );
      }
    }

    if(isset($_POST['subject_remarks']) && trim($_POST['subject_remarks']) == "" ){
      array_push($error, 'Remarks' );
    }

    if (count($error) >= 1) {
      $msg = 'empty fields found';
      $msgclr = 'danger';
    }

    if(!$msg && $msgclr != "danger"){
  
    $subjcode = trim($_POST['subject_code']);


        $remarks = "<hr><small>".date('Y-m-d H:i:s')."</small><br><b>CIC - Dispute Resolution Representative</b><br>".$_POST['subject_remarks']."<br>";
        $get_previous_remarks = $dbh2->query("SELECT fld_subjcode_remarks FROM ".$tbn." WHERE fld_id = '".$_POST['fld_id']."'");
        $gpr=$get_previous_remarks->fetch_array();

        $new_remarks = addslashes($remarks.$gpr['fld_subjcode_remarks']);


        if ($dbh2->query("UPDATE ".$tbn." SET fld_disp_status = 2, fld_disp_ts = '".date("Y-m-d H:i:s")."', fld_dispute_classification = '".$_POST['classifyDisp']."', fld_classification_ts = '".date("Y-m-d H:i:s")."' ,fld_resolution_type = '".$_POST['classifyReso']."', fld_resolution_type_ts = '".date("Y-m-d H:i:s")."', fld_resolution_type_by = '".$_SESSION['name']."', fld_subjcode = AES_ENCRYPT('".$subjcode."', CONCAT(fld_id,'G3n13')), fld_verification_returned = 0, fld_subjcode_ts = '".date("Y-m-d H:i:s")."', fld_subjcode_remarks = '".$new_remarks."', fld_dispute_verification_ts = '".date("Y-m-d H:i:s")."',  fld_dispute_verification_by = '".$_SESSION['name']."' , fld_subjcode_by = '".$_SESSION['name']."' WHERE fld_id = '".$_POST['fld_id']."'")) {
          if ($dbh2->query("UPDATE ".$tbs." SET fld_subjcode = AES_ENCRYPT('".$subjcode."', CONCAT(fld_Birthday,'G3n13')), fld_subjcode_ts = '".date("Y-m-d H:i:s")."' WHERE fld_TRN = '".$_POST['subj_TRN']."' and AES_DECRYPT(fld_subjcode, CONCAT(fld_Birthday,'G3n13')) IS NULL")) {
            
            if($_POST['tableName'] == "contract2"){
              $get_first_filed_dispute = $dbh2-> query("SELECT * from contract where fld_TRN = '".$_POST['subj_TRN']."' AND fld_prov = '".$_POST['provCode']."' ");
              $gffd = $get_first_filed_dispute->fetch_array();
              if($dbh2->query("UPDATE contract SET fld_update_old_disp = 2 where fld_TRN = '".$gffd['fld_TRN']."' AND fld_prov = '".$gffd['fld_prov']."'")){
              
                $msg = "Dispute Verification succesfully completed.";
                $msgclr = "success";
              }else{
                $msg = "There's an error updating the dispute.";
                $msgclr = "danger";
              }
      
            }else{
              $msg = "Dispute Verification succesfully completed.";
              $msgclr = "success";
            }



          } else {
            $msg = "Error Dispute Verification ";
            $msgclr = "danger";
          }
        } else {
            $msg = "Error updating.";
            $msgclr = "danger";
        }

      }
 
  }


  if ($_POST['sbtIncomplete']) {

    if(isset($_POST['subject_remarks']) && $_POST['subject_remarks'] == "" ){
      array_push($error, 'Remarks' );
    }


    if (count($error) >= 1) {
      $msg = 'empty fields found';
      $msgclr = 'danger';
    }

    if(!$msg && $msgclr != "danger"){

      $remarks = "<hr><small>".date('Y-m-d H:i:s')."</small><br><b><b>CIC - Dispute Resolution Representative</b></b><br>".$_POST['subject_remarks']."<br>";
      $get_previous_remarks = $dbh2->query("SELECT fld_subjcode_remarks FROM ".$tbn." WHERE fld_id = '".$_POST['fld_id']."'");
      $gpr=$get_previous_remarks->fetch_array();

      $new_remarks = addslashes($remarks.$gpr['fld_subjcode_remarks']);


      //Return dispute to pending status for correction of DRCP
      if ($dbh2->query("UPDATE ".$tbn." SET fld_dispute_verification_status = 0 , fld_disp_status = 0, fld_verification_returned = 1, fld_subjcode_remarks = '".$new_remarks."', fld_subjcode_by = '".$_SESSION['name']."', fld_returned_status = 1, fld_resubmit_ts = NULL, fld_reminder_returned_mailer_ts = NULL, fld_reminder_notification_ts = NULL, fld_returned_ts = '".date('Y-m-d H:i:s')."' ,  fld_returned_by = '".$_SESSION['user_id']."'  WHERE fld_id = '".$_POST['fld_id']."'")) {
        
        //update old dispute identifier
        if($_POST['tableName'] == "contract2"){
          $get_first_filed_dispute = $dbh2-> query("SELECT * from contract where fld_TRN = '".$_POST['subj_TRN']."' AND fld_prov = '".$_POST['provCode']."' ");
          $gffd = $get_first_filed_dispute->fetch_array();
          if($dbh2->query("UPDATE contract SET fld_update_old_disp = 0 where fld_TRN = '".$gffd['fld_TRN']."' AND fld_prov = '".$gffd['fld_prov']."'")){
            $msg = "Incomplete Validation Remarks successfully updated. The dispute will return to pending status for correction of DRCP";
            $msgclr = "success";
          }else{
            $msg = "There's an error updating the dispute.";
            $msgclr = "danger";
          }

        }else{
          $msg = "Incomplete Validation Remarks successfully updated. The dispute will return to pending status for correction of DRCP";
          $msgclr = "success";
        }
        // $get_previous_remarks = $dbh2->query("SELECT fld_subjcode_remarks FROM ".$tbn." WHERE fld_id = '".$_POST['fld_id']."'");
        // $gpr=$get_previous_remarks->fetch_array();

      } else {
          $msg = "Error in updating Incomplete Validation Remarks.";
          $msgclr = "danger";
      }

    }

  }


  $subject_arr = array("01"=>"Main Address","02"=>"Civil Status","03"=>"Secondary Address","04"=>"TIN Number","05"=>"Cars Owned","06"=>"SSS Number","07"=>"Number of Dependents","08"=>"GSIS Number","09"=>"Gross Income","10"=>"Additional Phone", "11"=>"Company Trade Name", "12"=>"Main Phone","13"=>"Company Main Address","14"=>"Name");
?>  
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <div class="input-group-prepend">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          For Verification
        </button>
        <ul class="dropdown-menu">
          <li class="dropdown-item"><a href="main.php?nid=142&sid=2&rid=2">Pending</a></li>
          <li class="dropdown-item"><a href="main.php?nid=142&sid=0&rid=0">For Verification</a></li>
          <li class="dropdown-item"><a href="main.php?nid=142&sid=1&rid=1">Completed</a></li>
        </ul>
      </div>
    </div>
    <div class="card-body">
      <?php
        if ($msg) {
      ?>
      <div class="alert alert-<?php echo $msgclr; ?> alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <!-- <h5><i class="icon fas fa-ban"></i> Alert!</h5> -->
        <?php 
             if (count($error) >= 1) {
              // Display error messages
              echo "<p  style='font-weight: bold;'>Kindly fill all the required fields on the Action Details listed below: </p>";
              foreach ($error as $msg) {
                  
                  echo "<p>- $msg</p>";
              }
              
          } else {
              echo $msg;

          }
        ?>
      </div>
      <?php
        }
      ?>
      <table class="table table-bordered" id="disputeVerificationInprog">
        <thead>
          <tr>
            <th width="15"><center>#</center></th>
            <th width="100">ID</th>
            <th width="100">Filed Date</th>
            <th width="100">SE Submission Date</th>
            <th width="300">Name</th>
            <th width="100">Birth Date</th>
            <th width="400">Financial Institution</th>
            <th width="150"><center>Action</center></th>
          </tr>
        </thead>
        <tbody>
          <?php
          $c = 0;
            $get_filed_disputes = $dbh2->query("SELECT fld_id, fld_TRN, fld_prov, fld_name, fld_status, fld_contractType, fld_dispute_remarks, fld_complaint, fld_description, fld_dispute_classification,  fld_disp_classify_drcp, AES_DECRYPT(fld_subjcode, CONCAT(fld_id,'G3n13')) AS subjcode,fld_provsubj_number,fld_provcontr_number, fld_dispute_details,fld_filename, fld_subjcode_remarks, AES_DECRYPT(fld_subjcode, CONCAT(fld_id,'G3n13')) as fld_subjcode, fld_dispute_verification_ts ,  fld_se_dispdetails_ts,  fld_subjcode_by, fld_record_count FROM contract WHERE fld_id > 9434 and fld_dispute_verification_status = 1 and fld_disp_status = 0 
            
            ORDER BY fld_dispute_verification_ts DESC");

            
            while ($gfd = $get_filed_disputes->fetch_array()) {
              $trn = $gfd['fld_TRN'];
              $status = $gfd['fld_status'];
              $id = $gfd['fld_id'];
              $provCode = $gfd['fld_prov'];
              $tableName = "contract";
              $drcpUpdateTs = $gfd['fld_se_dispdetails_ts'];

              $c++;

              $get_filed_disputes2 = $dbh2->query("SELECT fld_id, fld_TRN, fld_prov, fld_name, fld_dispute_classification, fld_se_dispdetails_ts, 	 fld_dispute_verification_ts, fld_status, fld_contractType, fld_complaint, fld_description, AES_DECRYPT(fld_subjcode, CONCAT(fld_id,'G3n13')) AS subjcode FROM contract2 WHERE fld_dispute_verification_status = 1 and fld_disp_status = 0 AND fld_prov = '".$gfd['fld_prov']."' AND fld_trn = '".$trn."'");                                      
              if($gfd2 = $get_filed_disputes2->fetch_array()){

                $trn = $gfd2['fld_TRN'];
                $status = $gfd2['fld_status'];
                $id = $gfd2['fld_id'];
                $provCode = $gfd2['fld_prov'];
                $tableName = "contract2";
                $drcpUpdateTs = $gfd2['fld_se_dispdetails_ts'];

                $subject = $dbh2->query("SELECT fld_TRN, AES_DECRYPT(fld_Fname, CONCAT(fld_Birthday,'G3n13')) AS firstname, fld_Birthday, AES_DECRYPT(fld_Mname, CONCAT(fld_Birthday,'G3n13')) AS middlename, AES_DECRYPT(fld_Lname, CONCAT(fld_Birthday,'G3n13')) AS lastname, AES_DECRYPT(fld_Contact, CONCAT(fld_Birthday,'G3n13')) AS contact, fld_DateFilled, changes, AES_DECRYPT(fld_SSS, CONCAT(fld_Birthday,'G3n13')) AS SSS, AES_DECRYPT(fld_GSIS, CONCAT(fld_Birthday,'G3n13')) AS GSIS, AES_DECRYPT(fld_TIN, CONCAT(fld_Birthday,'G3n13')) AS TIN, AES_DECRYPT(fld_UMID, CONCAT(fld_Birthday,'G3n13')) AS UMID, AES_DECRYPT(fld_DL, CONCAT(fld_Birthday,'G3n13')) AS DL, AES_DECRYPT(fld_subjcode, CONCAT(fld_Birthday,'G3n13')) AS subjcode FROM subject2 WHERE fld_TRN = '".$gfd['fld_TRN']."' ORDER BY subjcode ASC");

              }else{
                $subject = $dbh2->query("SELECT fld_TRN, AES_DECRYPT(fld_Fname, CONCAT(fld_Birthday,'G3n13')) AS firstname, fld_Birthday, AES_DECRYPT(fld_Mname, CONCAT(fld_Birthday,'G3n13')) AS middlename, AES_DECRYPT(fld_Lname, CONCAT(fld_Birthday,'G3n13')) AS lastname, AES_DECRYPT(fld_Contact, CONCAT(fld_Birthday,'G3n13')) AS contact, fld_DateFilled, changes, AES_DECRYPT(fld_SSS, CONCAT(fld_Birthday,'G3n13')) AS SSS, AES_DECRYPT(fld_GSIS, CONCAT(fld_Birthday,'G3n13')) AS GSIS, AES_DECRYPT(fld_TIN, CONCAT(fld_Birthday,'G3n13')) AS TIN, AES_DECRYPT(fld_UMID, CONCAT(fld_Birthday,'G3n13')) AS UMID, AES_DECRYPT(fld_DL, CONCAT(fld_Birthday,'G3n13')) AS DL, AES_DECRYPT(fld_subjcode, CONCAT(fld_Birthday,'G3n13')) AS subjcode FROM subject WHERE fld_TRN = '".$gfd['fld_TRN']."' ORDER BY subjcode ASC");
              }

    
              $s=$subject->fetch_array();

  
              $disputeDateFiled = $s['fld_DateFilled'];


              if($gfd['fld_disp_classify_drcp'] == 1){
                $gfd['fld_disp_classify_drcp'] = "Simple Dispute";
              }elseif($gfd['fld_disp_classify_drcp'] == 2){
                $gfd['fld_disp_classify_drcp'] = "Complex Dispute";
              }elseif($gfd['fld_disp_classify_drcp'] == 3){
                $gfd['fld_disp_classify_drcp'] = "Highly Technical Dispute";
              }else{
                $gfd['fld_disp_classify_drcp'] = "N/A";
              }

              if($gfd['fld_dispute_classification'] == 1){
                $gfd['fld_dispute_classification'] = "Simple Dispute";
              }elseif($gfd['fld_dispute_classification'] == 2){
                $gfd['fld_dispute_classification'] = "Complex Dispute";
              }elseif($gfd['fld_dispute_classification'] == 3){
                $gfd['fld_dispute_classification'] = "Highly Technical Dispute";
              }else{
                $gfd['fld_dispute_classification'] = "N/A";
              }


            

              $appointment = $dbh6->query("SELECT AES_decrypt(fld_email,CONCAT(fld_refID, 'RA9510')) as email FROM tblappointment where fld_refID = '".$gfd['fld_TRN']."' ");
              $a = $appointment->fetch_array();

              if ($gfd['fld_status'] == 4) {
                $name = $gfd['fld_name'];
              } else {
                $get_company_name = $dbh4->query("SELECT AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$gfd['fld_prov']."'");
                $gcn=$get_company_name->fetch_array();
                $name = $gcn['name'];
              }

    

          ?>
          <tr>           
            <td><center><?php echo $c; ?></center></td>
            <td><?php echo $trn ?></td>
            <td><?php echo date("F d, Y h:ia", strtotime($disputeDateFiled)); ?></td>
              <td><?php echo date("F d, Y h:ia", strtotime($drcpUpdateTs)); ?></td>
       
           <td><a href="#" data-toggle="modal" data-target="#modal_dispute_details<?php echo $gfd['fld_id']; ?>"><?php echo $s['firstname']. " " .$s['middlename']. " " .$s['lastname']; ?></a>
              <div class="modal fade" id="modal_dispute_details<?php echo $gfd['fld_id']; ?>" style="display: none;">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title"><?php echo $gfd['fld_TRN']. " - ".$name ; ?></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                      <?php
                      if ($s['changes']) {
                      ?>
                      <h5><b>Subject</b></h5>
                      <label>Selected information:</label><br>
                      <p>
                        <?php 
                        
                          $subval = explode("|", $s['changes']);
                            foreach ($subval as $key) {
                              echo $subject_arr[$key]."<br>";
                            }
                        ?>
                      </p>
                      <hr>
                      <?php
                      }
                      ?>
                      

                      <?php
                      if ($gfd['fld_contractType']) {
                      ?>
                      <h5><b>Contract</b></h5>
                      <label>Loan Name:</label><br>
                      <p><?php echo $gfd['fld_contractType']; ?></p>
                      <label>Dispute:</label><br>
                      <p><?php echo $gfd['fld_complaint']; ?></p>
                      <label>Dispute Details:</label><br>
                      <p><?php echo $gfd['fld_description']; ?></p>
                      
                      <br>
                      <hr>
                      <?php
                      }
                      ?>
                      

                      <h5><b>Personal Information</b></h5>
                      <?php
                        if ($_SESSION['usertype'] == 0 || $_SESSION['usertype'] == 8 || $_SESSION['user_id'] == 115) {
                      ?>
                      <label>ID NUMBER(S)</label>
                      <ul>
                        <?php
                            if (trim($s['SSS'])) {
                        ?>
                        <li><b>SSS:</b> <?php echo $s['SSS']; ?></li>
                        <?php
                            }
                        ?>

                        <?php
                            if (trim($s['GSIS'])) {
                        ?>
                        <li><b>GSIS:</b> <?php echo $s['GSIS']; ?></li>
                        <?php
                            }
                        ?>

                        <?php
                            if (trim($s['TIN'])) {
                        ?>
                        <li><b>TIN:</b> <?php echo $s['TIN']; ?></li>
                        <?php
                            }
                        ?>

                        <?php
                            if (trim($s['UMID'])) {
                        ?>
                        <li><b>UMID:</b> <?php echo $s['UMID']; ?></li>
                        <?php
                            }
                        ?>

                        <?php
                            if (trim($s['DL'])) {
                        ?>
                        <li><b>DL:</b> <?php echo $s['DL']; ?></li>
                        <?php
                            }
                        ?>
                      </ul>

                      <?php
                        }
                      ?>
                      <hr>
                      <label>Address</label>
                      <br>
                      <?php
                      $get_address_from_appt = $dbh6->query("SELECT AES_DECRYPT(fld_street1, CONCAT(fld_refID, 'RA9510')) as fld_street1, AES_DECRYPT(fld_subd1, CONCAT(fld_refID, 'RA9510')) as fld_subd1, AES_DECRYPT(fld_brngy1, CONCAT(fld_refID, 'RA9510')) as fld_brngy1, fld_postal1, fld_city1, AES_DECRYPT(fld_street2, CONCAT(fld_refID, 'RA9510')) as fld_street2, AES_DECRYPT(fld_subd2, CONCAT(fld_refID, 'RA9510')) as fld_subd2, AES_DECRYPT(fld_brngy2, CONCAT(fld_refID, 'RA9510')) as fld_brngy2, fld_postal2, fld_city2  FROM tblappointment WHERE fld_refID = '".$gfd['fld_TRN']."'");
                      $gafappt=$get_address_from_appt->fetch_array();
                      ?>
                      <p><?php echo $gafappt['fld_street1']. " " .$gafappt['fld_subd1']. " " .$gafappt['fld_brngy1']. " " .$gafappt['fld_city1']. " " .$gafappt['fld_postal1']; ?></p>
                      <p><?php echo $gafappt['fld_street2']. " " .$gafappt['fld_subd2']. " " .$gafappt['fld_brngy2']. " " .$gafappt['fld_city2']. " " .$gafappt['fld_postal2']; ?></p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>

            </td>
            <td><?php echo $s['fld_Birthday']; ?></td>
            <td><?php echo $name; ?></td>
   
            <td>
              <center>
                <button type="button" class="btn btn-warning btn-block" data-toggle="modal" data-target="#modal_enter_incomplete<?php echo $gfd['fld_id']; ?>">Incomplete Validation</button>
                <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#modal_view_convo<?php echo $gfd['fld_id']; ?>">View Conversation</button>
                <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#modal_enter_subjcode<?php echo $gfd['fld_id']; ?>">Complete Validation</button>

              </center>
              <!-- start complete -->
              <div class="modal fade" id="modal_enter_subjcode<?php echo $gfd['fld_id']; ?>" style="display: none;">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title text-success">Complete Validation Remarks for <?php echo $gfd['fld_TRN']. " - ".$name ; ?></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    </div>
                    <form method="post">
                    <div class="modal-body">
                    <input type="hidden" name="tableName" value="<?php echo $tableName; ?>">
                      <input type="hidden" name="fld_id" value="<?php echo $id; ?>">
                      <input type="hidden" name="subj_TRN" value="<?php echo $trn; ?>">
                      <input type="hidden" name="provCode" value="<?php echo $provCode; ?>">
                      <p>Provider Subject Number: <b><?php echo $gfd['fld_provsubj_number']; ?></b></p>
                      <p>Provider Contract Number: <b><?php echo $gfd['fld_provcontr_number']; ?></b></p>
                      <p>Description: <b><?php echo $gfd['fld_description']; ?></b></p>
                      <!-- <p>Dispute Details: <b><?php echo $gfd['fld_dispute_details']; ?></b></p> -->
                      <p>Dispute Remarks: <b><?php echo $gfd['fld_dispute_remarks']; ?></b></p>
                      <p>Filename(s): <b><?php echo $gfd['fld_filename']; ?></b></p>
                      <p>Record Count(s): <b><?php echo $gfd['fld_record_count']; ?></b></p>

                      <div class="form-group">
                      <label for="subject_code">Dispute Classification DRCP</label>
                      <p><?php echo $gfd['fld_disp_classify_drcp']?></p>
                      <!-- <input type="text" class="form-control" id="disputeClassificationSE" name="disputeClassificationSE" value=""> -->

                      
                      </div>
                      
                      <div class="form-group">
                      <label for="subject_code">Dispute Classification</label>
                          <select class="custom-select classifyDisp" name="classifyDisp" id="classifyDisp"  value="<?php echo $gfd['fld_dispute_classification']?>" >
                              <option value="x" selected="" disabled="">Select Dispute Classification DRT</option>
                              <option value="1" <?php if($gfd['fld_dispute_classification'] == 1){ echo "selected";}?>>Simple Dispute</option>
                              <option value="2" <?php if($gfd['fld_dispute_classification'] == 2){ echo "selected";}?>>Complex Dispute</option>
                              <option value="3" <?php if($gfd['fld_dispute_classification'] == 3){ echo "selected";}?>>Highly Technical Dispute</option>              
                          </select>
                      
                      </div>

                      
                      <div class="form-group">
                        <input type="hidden" name="fld_id" value="<?php echo $id; ?>">
                        <label for="subject_code">Resolution Type</label>
                          <select class="custom-select classifyDisp" name="classifyReso" id="classifyReso" >
                              <option value="x" selected="" disabled="">Select Resolution Type</option>
                              <option value="1" <?php if($gfd['fld_resolution_type'] == 1){ echo "selected";}?>>Incorrect</option>
                              <option value="2" <?php if($gfd['fld_resolution_type'] == 2){ echo "selected";}?>>Missing</option>
                              <option value="3" <?php if($gfd['fld_resolution_type'] == 3){ echo "selected";}?>>Disinterested Disputer</option> 
                              <option value="4" <?php if($gfd['fld_resolution_type'] == 4){ echo "selected";}?>>Mutually Agreed Resolution</option> 
                              <option value="5" <?php if($gfd['fld_resolution_type'] == 5){ echo "selected";}?>>Issues outside ODRS</option>    
                              <option value="6" <?php if($gfd['fld_resolution_type'] == 6){ echo "selected";}?>>Annulment/Removal of Credit Data</option>                
                          </select>
                      </div>
                      <div class="form-group">
                        <label for="subject_code">Subject Code</label>
                        <input type="text" class="form-control" id="subject_code" name="subject_code" placeholder="Enter Subject Code">
                      </div>

<!-- 
                      <div class="custom-control custom-checkbox mb-2">
                        <input class="custom-control-input" type="checkbox" id="rdConfidential[<?php echo $gfd['fld_id']; ?>]" name="rdConfidential[<?php echo $gfd['fld_id']; ?>]" value="1">
                        <label for="rdConfidential[<?php echo $gfd['fld_id']; ?>]" class="custom-control-label">Is Confidential?</label>
                      </div> -->

                      <div class="form-group">
                        <label>Remarks</label>
                        <textarea class="form-control" rows="5" name="subject_remarks" placeholder="Enter details"></textarea>
                      </div>
                      <p>
                        <?php echo $gfd['fld_subjcode_remarks']; ?>
                      </p>
                      
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                      <button type="submit" value="1" name="sbtSubjectCode" class="btn btn-success pull-left">Complete</button>
                    </div>
                    </form>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
               <!-- end complete -->

                        <!-- start incomplete -->
              <div class="modal fade" id="modal_enter_incomplete<?php echo $id; ?>" style="display: none;">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title text-warning">Incomplete Validation Remarks for <?php echo $trn. " - ".$name ; ?></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    </div>
                    <form method="post">
                    <div class="modal-body">
                    <input type="hidden" name="tableName" value="<?php echo $tableName; ?>">
                      <input type="hidden" name="fld_id" value="<?php echo $id; ?>">
                      <input type="hidden" name="subj_TRN" value="<?php echo $trn; ?>">
                      <input type="hidden" name="provCode" value="<?php echo $provCode; ?>">
                      <p>Provider Subject Number: <b><?php echo $gfd['fld_provsubj_number']; ?></b></p>
                      <p>Provider Contract Number: <b><?php echo $gfd['fld_provcontr_number']; ?></b></p>
                       <p>Description: <b><?php echo $gfd['fld_description']; ?></b></p>
                      <!-- <p>Dispute Details: <b><?php echo $gfd['fld_dispute_details']; ?></b></p> -->
                      <p>Dispute Remarks: <b><?php echo $gfd['fld_dispute_remarks']; ?></b></p>
                      <p>Filename(s): <b><?php echo $gfd['fld_filename']; ?></b></p>
                      <p>Record Count(s): <b><?php echo $gfd['fld_record_count']; ?></b></p>


                      <div class="form-group">
                        <label>Remarks</label>
                        <textarea class="form-control" rows="5" name="subject_remarks" placeholder="Enter details"></textarea>
                      </div>
                      <p>

                        <?php
                            $gfd['fld_subjcode_remarks'] = str_replace("CIC - Dispute Resolution Representative", $gfd['fld_subjcode_by'] ,$gfd['fld_subjcode_remarks']);

                         echo $gfd['fld_subjcode_remarks']; ?>
                      </p>
                      
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                      <button type="submit" value="1" name="sbtIncomplete" class="btn btn-warning pull-left">Return</button>
                    </div>
                    </form>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
              <!-- end -->

                             <!-- start incomplete -->
              <div class="modal fade" id="modal_view_convo<?php echo $gfd['fld_id']; ?>" style="display: none;">
                <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title text-warning">Conversation Between <?php echo $gfd['fld_TRN']. " - ".$name ; ?></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    </div>
                    <form method="post">
                    <div class="modal-body">
                      
                      <div class="row mt-2">
                                  <div class="col-md-12 ">
                    <?php
                      $sqlDisputeConvo = $dbh2->query("SELECT * from conversation where fld_trn = '".$gfd['fld_TRN']."' and (fld_to = '".$gfd['fld_prov']."' OR fld_from = '".$gfd['fld_prov']."' )");
                      while($sdc = $sqlDisputeConvo->fetch_array()){ 


                                        if($sdc['fld_from'] == $gfd['fld_prov']){
                                            ?>

                                          <div class="col-md-9 text-justified "> 
                                                <small class="float-end"><?php echo $sdc['fld_from']; ?></small><br>
                                                <p class="text-dark" style="height:auto; width:auto; border-radius: 20px; border-width: 2px; overflow: hidden; background-color:rgba(153, 153, 255, 0.20); padding: 1em;"><?php echo $sdc['fld_message']; ?></p>
                                                <small class="float-end"><?php echo $sdc['fld_message_ts']; ?></small><br>                                             
                                            </div>
                                        
                                          
                                            <?php    
                                        }elseif($sdc['fld_from'] == $a['email']){
                                            ?>
                                               <div class="col-md-9 offset-md-3 text-justified">                                          
                                                <small class="pull-right fs-2"><?php echo$sdc['fld_from']?></small><br>
                                                <p class=" text-light" style="height:auto; width:auto; border-radius: 20px; border-width: 2px; overflow: hidden; background-color:#004080; padding: 1em;"><?php echo $sdc['fld_message']; ?></p>
                                                <small class="pull-right fs-2"><?php echo $sdc['fld_message_ts']; ?></small><br>
                                            </div>
                                        
                                            <?php
                                        }
                                      }
                                        ?>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                    </form>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
              <!-- end -->

            </td>
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

</section>
<!-- /.content -->