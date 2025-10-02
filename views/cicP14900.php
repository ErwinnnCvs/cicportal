<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'freshdesk/icmt/fetch_conversations_and_save.php';

if(!$_POST['sel_type']){
    $_POST['sel_type'] = 'MF';
}

// if(!$_POST['stat_type']){
//     $_POST['stat_type'] = 'Registration';
// }

$selectedtype[$_POST['sel_type']] = " selected";
$selectedstat[$_POST['stat_type']] = " selected";

//print_r($_SESSION);
//echo "SELECT * FROM tbictpersonnel WHERE fld_userid ='".$_SESSION['user_id']."'";
$session = $dbh->query("SELECT * FROM tbictpersonnel WHERE fld_userid ='".$_SESSION['user_id']."' ");
$sesh=$session->fetch_array();

if (!$sesh) {
    $msg = "You currently have no access to this module";
    $msgclr = "warning";
}
else { 

?>



<!-- Main content -->
<section class="content">
<?php
        if ($msg) {
      ?>

      <div class="alert alert-<?php echo $msgclr; ?> alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <?php echo $msg; ?>
      </div>

      <?php 
        }
      ?>

  <!-- Default box -->
  <div class="card">
    <!-- <div class="card-header">
      <h3 class="card-title">Title</h3>
    </div> -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <!-- <form method="post" name="entTypeForm"> -->
                <form method="post" name="everythingForm">
                    <div class="form-group">
                        <label>Select Type of Entity</label>
                        <label for="sel_type"></label><select class="form-control select2" name="sel_type" id="sel_type" style="width: 100%;">
                                <option selected="selected" disabled>---SELECT---</option>
                                <option value=1> ALL </option>
                                <?php
                                    $get_all_types = $dbh4->query("SELECT * FROM tbenttypes");
                                    while($gat=$get_all_types->fetch_array()){
                                        echo "<option value='".$gat['fld_type']."'".$selectedtype[$gat['fld_type']].">".$gat['fld_type']." - ".$gat['fld_name']."</option>";
                                        //echo "<option value='".$gat['fld_type']."'".$selectedtype[$gat['fld_type']].">".$gat['fld_name']." - ".$ent2[$gat['fld_name']]."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    <!-- </form>   -->
                </div>
                <?php 
                    if ($sesh['fld_stage'] == 4) {

                        $offset = "";
                ?>
                <div class="col-md-4">
                        <div class="form-group">
                            <label>Select Status</label>
                            <label for="stat_type"></label><select class="form-control select2" name="stat_type" id="stat_type" style="width: 100%;">
                                <option selected="selected" disabled>---SELECT---</option>
                                <option value="1">Registration</option><option value="2">Testing & Validation</option><option value="3">Production</option>  
                            </select>
                        </div>
                    <!-- </form>   -->
                </div>
                <?php
                    }
                    elseif ($sesh['fld_stage'] != 4) {
                        $offset = "offset-md-4";
                    }
                ?>
                <div class="col-md-2 <?php echo $offset; ?>">
                    <label>From:</label>
                    <div class="input-group date" id="start_date" data-target-input="nearest">
                        <input type="text" name="startDateFilter" class="form-control datetimepicker-input" data-target="#start_date">
                        <div class="input-group-append" data-target="#start_date" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <label>To:</label>
                    <div class="input-group date" id="end_date" data-target-input="nearest">
                        <input type="text" name="endDateFilter" class="form-control datetimepicker-input" data-target="#end_date">
                        <div class="input-group-append" data-target="#end_date" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
        </div>
        <!-- <form> -->
        <div class="row">
            <div class="col-md-4">
                <label>STAGE LEGEND</label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <span style="width: 15px; height: 15px; background-color: GRAY; border-radius: 50%; display: inline-block;"></span>
                <span> In Registration</span>
            </div>
            <div class="col-md-3">
                <span style="width: 15px; height: 15px; background-color: YELLOW; border-radius: 50%; display: inline-block;"></span>
                <span> In Testing & Validation</span>
            </div>
            <div class="col-md-3">
                <span style="width: 15px; height: 15px; background-color: BLUE; border-radius: 50%; display: inline-block;"></span>
                <span> In Production</span>
            </div>
            <div class="col-md-2 offset-md-2">
                    <button type="submit" name="applyFilter" value="1" class="btn btn-block btn-success">Apply Filter</button>
            </div>


        </div>
        <?php 

        ?>
        <br>
        <div class="card-body table-responsive p-0">
            <table class="table table-bordered table-striped table-hovered" id="initialcompliance">
                <thead>
                    <tr>
                    <style>
                        th {text-align: center;}
                        td {text-align: center;}
                    </style>          
                      <th></th>
                      <th>Status</th>
                      <th>Provider Code</th>
                      <th>Submitting Entity</th>
                      <th>Entity Type</th>
                      <th>Name</th>
                      <th>Position</th>
                      <th>Contact Number</th>
                      <th>Email Address</th>
                      <th>TSP</th>
                      <th>Tickets</th>
                      <th>Registration<br><small><b><i>(15 Days)</i></b></small></th>
                      <th>Testing & Validation<br><small><b><i>(30 Days)</i></b></small></th>
                      <th>Production<br><small><b><i>(15 Days)</i></b></small></th>
                      <th>Action</th>
                      <th>Corrective Action / Remarks</th>
                      <th>Penalties</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $c = 0;                        

                            //$assignedSE = array();
                            // for stage 2 se assignment
                            $sheeshable = $dbh->query("SELECT * FROM tbictpersonnel WHERE fld_userid ='".$_SESSION['user_id']."'");
                            while ($sssh=$sheeshable->fetch_array()) {

                                $query .= " OR fld_ctrlno = ".$sssh['fld_ctrlno'];
                                $rep = substr_replace($query, " AND", 0, 3);
                            }

                            // STAGE 1 - REGISTRATION
                            if ($sesh['fld_stage'] == 1) {
                                $stageClause = "AND fld_registration_upload IS NOT NULL AND fld_uat_ceportal_sent_ts IS NULL";
                                $group = "AND fld_group_id = 33000148658";
                            }
    
                            // STAGE 2 - TESTING AND DEVELOPMENT
                            elseif ($sesh['fld_stage'] == 2) {
                                // echo "test";
                                // $stageClause = "AND fld_uat_ceportal_sent_ts IS NOT NULL AND fld_se_confirmed_ts IS NULL AND fld_ctrlno = '".$sesh['fld_ctrlno']."' ";
                                $stageClause = "AND fld_uat_ceportal_sent_ts IS NOT NULL AND fld_se_confirmed_ts IS NULL".$rep;
                                $group = "AND fld_group_id = 33000215509";
                            }
    
                            // STAGE 3 - PRODUCTION
                            elseif($sesh['fld_stage'] == 3) {
                                $stageClause = "AND fld_se_confirmed_ts IS NOT NULL";
                                $group = "AND fld_group_id = 33000212005";
                            }
                            
                            // [LOC]
                            elseif($sesh['fld_stage'] == 4) {
                                $stageClause = "";
                                $group = "AND fld_group_id = 33000215508";
                                
                            }

                    if ($_POST['applyFilter']) {
                        // error message if one of the date filters are empty
                        // (NOT empty entity type AND (empty start date field AND NOT empty end date field) OR ((NOT empty start date field AND empty end date field) OR (empty start date field AND NOT empty end date field))
                        if ((!empty($_POST['sel_type'] ) && (empty($_POST['startDateFilter']) && !empty($_POST['endDateFilter'])) || (!empty($_POST['startDateFilter']) && empty($_POST['endDateFilter']))) || ((empty($_POST['startDateFilter']) && !empty($_POST['endDateFilter'])) || (!empty($_POST['startDateFilter']) && empty($_POST['endDateFilter'])))) {
                            $msg = "Please select a proper date range";
                            $msgclr = "warning";

                            $entityFilter = "";
                            $dateFilter = "";
                            $stageFilter = "";
                            
                            echo "working";
                        }
            
                        // ---------- [STAGE 1] filter ----------
                        elseif ($sesh['fld_stage'] == 1) {
                            // show selected entity type and status filter and date filters are empty
                            if ($_POST['sel_type'] != 1 && !empty($_POST['sel_type']) && empty($_POST['stat_type']) && empty($_POST['startDateFilter']) && empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type='".$_POST['sel_type']."'";
                                $dateFilter = "";
                                $stageFilter = "";
                            }
                            // show ALL entity types
                            elseif ($_POST['sel_type'] == 1 && empty($_POST['stat_type']) && empty($_POST['startDateFilter']) && empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type<>''";
                                $dateFilter = "";
                                $stageFilter = "";
                            }
                            // show selected entity type and empty status filter and not empty date filters
                            elseif ($_POST['sel_type'] != 1 && !empty($_POST['sel_type']) && empty($_POST['stat_type']) && !empty($_POST['startDateFilter']) && !empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type='".$_POST['sel_type']."'";
                                $dateFilter = "AND (fld_registration_upload BETWEEN '".$_POST['startDateFilter']."' AND '".$_POST['endDateFilter']."')";
                                $stageFilter = "";
                            }
                            // show ALL entity type and empty status filter and not empty date filters
                            elseif ($_POST['sel_type'] == 1 && empty($_POST['stat_type']) && !empty($_POST['startDateFilter']) && !empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type<>''";
                                $dateFilter = "AND (fld_registration_upload BETWEEN '".$_POST['startDateFilter']."' AND '".$_POST['endDateFilter']."')";
                                $stageFilter = "";
                            }
                        }
                        // ---------- [STAGE 2] filter ----------
                        elseif ($sesh['fld_stage'] == 2) {
                            // show selected entity type and status filter and date filters are empty
                            if ($_POST['sel_type'] != 1 && !empty($_POST['sel_type']) && empty($_POST['stat_type']) && empty($_POST['startDateFilter']) && empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type='".$_POST['sel_type']."'";
                                $dateFilter = "";
                                $stageFilter = "";
                            }
                            // show ALL entity types
                            elseif ($_POST['sel_type'] == 1 && empty($_POST['stat_type']) && empty($_POST['startDateFilter']) && empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type<>''";
                                $dateFilter = "";
                                $stageFilter = "";
                            }
                            // show selected entity type and empty status filter and not empty date filters
                            elseif ($_POST['sel_type'] != 1 && !empty($_POST['sel_type']) && empty($_POST['stat_type']) && !empty($_POST['startDateFilter']) && !empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type='".$_POST['sel_type']."'";
                                $dateFilter = "AND (fld_uat_ceportal_sent_ts BETWEEN '".$_POST['startDateFilter']."' AND '".$_POST['endDateFilter']."')";
                                $stageFilter = "";
                            }
                            // show ALL entity type and empty status filter and not empty date filters
                            elseif ($_POST['sel_type'] == 1 && empty($_POST['stat_type']) && !empty($_POST['startDateFilter']) && !empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type<>''";
                                $dateFilter = "AND (fld_uat_ceportal_sent_ts BETWEEN '".$_POST['startDateFilter']."' AND '".$_POST['endDateFilter']."')";
                                $stageFilter = "";
                            }
                        }
                        // ---------- [STAGE 3] filter ----------
                        elseif ($sesh['fld_stage'] == 3) {
                            // show selected entity type and status filter and date filters are empty
                            if ($_POST['sel_type'] != 1 && !empty($_POST['sel_type']) && empty($_POST['stat_type']) && empty($_POST['startDateFilter']) && empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type='".$_POST['sel_type']."'";
                                $dateFilter = "";
                                $stageFilter = "";
                            }
                            // show ALL entity types
                            elseif ($_POST['sel_type'] == 1 && empty($_POST['stat_type']) && empty($_POST['startDateFilter']) && empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type<>''";
                                $dateFilter = "";
                                $stageFilter = "";
                            }
                            // show selected entity type and empty status filter and not empty date filters
                            elseif ($_POST['sel_type'] != 1 && !empty($_POST['sel_type']) && empty($_POST['stat_type']) && !empty($_POST['startDateFilter']) && !empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type='".$_POST['sel_type']."'";
                                $dateFilter = "AND (fld_se_confirmed_ts BETWEEN '".$_POST['startDateFilter']."' AND '".$_POST['endDateFilter']."')";
                                $stageFilter = "";
                            }
                            // show ALL entity type and empty status filter and not empty date filters
                            elseif ($_POST['sel_type'] == 1 && empty($_POST['stat_type']) && !empty($_POST['startDateFilter']) && !empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type<>''";
                                $dateFilter = "AND (fld_se_confirmed_ts BETWEEN '".$_POST['startDateFilter']."' AND '".$_POST['endDateFilter']."')";
                                $stageFilter = "";
                            }
                        }
                        // ---------- [LOC] filter ----------
                        elseif ($sesh['fld_stage'] == 4) {
                            // show selected entity type and status filter and date filters are empty
                            if ($_POST['sel_type'] != 1 && !empty($_POST['sel_type']) && empty($_POST['stat_type']) && empty($_POST['startDateFilter']) && empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type='".$_POST['sel_type']."'";
                                $dateFilter = "";
                                $stageFilter = "";
                            }
                            // show ALL entity types
                            elseif ($_POST['sel_type'] == 1 && empty($_POST['stat_type']) && empty($_POST['startDateFilter']) && empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type<>''";
                                $dateFilter = "";
                                $stageFilter = "";
                            }
                            // show selected entity type and status filter is empty and date filters are not empty
                            elseif ($_POST['sel_type'] != 1 && !empty($_POST['sel_type']) && empty($_POST['stat_type']) && !empty($_POST['startDateFilter']) && !empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type='".$_POST['sel_type']."'";
                                $dateFilter = "AND (fld_registration_upload BETWEEN '".$_POST['startDateFilter']."' AND '".$_POST['endDateFilter']."')";
                                $stageFilter = "";
                            }
                            // show ALL entity types amd status filter is empty and date filters are not empty
                            elseif ($_POST['sel_type'] == 1 && empty($_POST['stat_type']) && !empty($_POST['startDateFilter']) && !empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type<>''";
                                $dateFilter = "AND (fld_registration_upload BETWEEN '".$_POST['startDateFilter']."' AND '".$_POST['endDateFilter']."')";
                                $stageFilter = "";
                            }

                            // ---------- [STAGE 1] ----------
                            // [STAGE 1] show selected entity type and selected status filter is registration and date filters are empty
                            elseif ($_POST['sel_type'] != 1 && !empty($_POST['sel_type']) && $_POST['stat_type'] == 1 && empty($_POST['startDateFilter']) && empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type='".$_POST['sel_type']."'";
                                $dateFilter = "";
                                $stageFilter = "AND fld_registration_upload IS NOT NULL AND fld_uat_ceportal_sent_ts IS NULL";
                            }
                            // [STAGE 1] show ALL entity types amd selected status filter is registration and date filters are empty
                            elseif ($_POST['sel_type'] == 1 && $_POST['stat_type'] == 1 && empty($_POST['startDateFilter']) && empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type<>''";
                                $dateFilter = "";
                                $stageFilter = "AND fld_registration_upload IS NOT NULL AND fld_uat_ceportal_sent_ts IS NULL";
                            }
                            // [STAGE 1] show selected entity type and selected status filter is registration and date filters are not empty
                            elseif ($_POST['sel_type'] != 1 && !empty($_POST['sel_type']) && $_POST['stat_type'] == 1 && !empty($_POST['startDateFilter']) && !empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type='".$_POST['sel_type']."'";
                                $dateFilter = "AND (fld_registration_upload BETWEEN '".$_POST['startDateFilter']."' AND '".$_POST['endDateFilter']."')";
                                $stageFilter = "AND fld_registration_upload IS NOT NULL AND fld_uat_ceportal_sent_ts IS NULL";
                            }
                            // [STAGE 1] show ALL entity types amd selected status filter is registration and date filters are not empty
                            elseif ($_POST['sel_type'] == 1 && $_POST['stat_type'] == 1 && !empty($_POST['startDateFilter']) && !empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type<>''";
                                $dateFilter = "AND (fld_registration_upload BETWEEN '".$_POST['startDateFilter']."' AND '".$_POST['endDateFilter']."')";
                                $stageFilter = "AND fld_registration_upload IS NOT NULL AND fld_uat_ceportal_sent_ts IS NULL";
                            }

                            // ---------- [STAGE 2] ----------
                            // [STAGE 2] show selected entity type and selected status filter is testing and validation and date filters are empty
                            elseif ($_POST['sel_type'] != 1 && !empty($_POST['sel_type']) && $_POST['stat_type'] == 2 && empty($_POST['startDateFilter']) && empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type='".$_POST['sel_type']."'";
                                $dateFilter = "";
                                $stageFilter = "AND fld_uat_ceportal_sent_ts IS NOT NULL AND fld_se_confirmed_ts IS NULL";
                            }
                            // [STAGE 2] show ALL entity types amd selected status filter is testing and validation and date filters are empty
                            elseif ($_POST['sel_type'] == 1 && $_POST['stat_type'] == 2 && empty($_POST['startDateFilter']) && empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type<>''";
                                $dateFilter = "";
                                $stageFilter = "AND fld_uat_ceportal_sent_ts IS NOT NULL AND fld_se_confirmed_ts IS NULL";
                            }
                            // [STAGE 2] show selected entity type and selected status filter is testing and validation and date filters are not empty
                            elseif ($_POST['sel_type'] != 1 && !empty($_POST['sel_type']) && $_POST['stat_type'] == 2 && !empty($_POST['startDateFilter']) && !empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type='".$_POST['sel_type']."'";
                                $dateFilter = "AND (fld_uat_ceportal_sent_ts BETWEEN '".$_POST['startDateFilter']."' AND '".$_POST['endDateFilter']."')";
                                $stageFilter = "AND fld_uat_ceportal_sent_ts IS NOT NULL AND fld_se_confirmed_ts IS NULL";
                            }
                            // [STAGE 2] show ALL entity types amd selected status filter is testing and validation and date filters are not empty
                            elseif ($_POST['sel_type'] == 1 && $_POST['stat_type'] == 2 && !empty($_POST['startDateFilter']) && !empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type<>''";
                                $dateFilter = "AND (fld_uat_ceportal_sent_ts BETWEEN '".$_POST['startDateFilter']."' AND '".$_POST['endDateFilter']."')";
                                $stageFilter = "AND fld_uat_ceportal_sent_ts IS NOT NULL AND fld_se_confirmed_ts IS NULL";
                            }

                            // ---------- [STAGE 3] ----------
                            // [STAGE 3] show selected entity type and selected status filter is production and date filters are empty
                            elseif ($_POST['sel_type'] != 1 && !empty($_POST['sel_type']) && $_POST['stat_type'] == 3 && empty($_POST['startDateFilter']) && empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type='".$_POST['sel_type']."'";
                                $dateFilter = "";
                                $stageFilter = "AND fld_se_confirmed_ts IS NOT NULL";
                            }
                            // [STAGE 3] show ALL entity types amd selected status filter is production and date filters are empty
                            elseif ($_POST['sel_type'] == 1 && $_POST['stat_type'] == 3 && empty($_POST['startDateFilter']) && empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type<>''";
                                $dateFilter = "";
                                $stageFilter = "AND fld_se_confirmed_ts IS NOT NULL";
                            }
                            // [STAGE 3] show selected entity type and selected status filter is production and date filters are not empty
                            elseif ($_POST['sel_type'] != 1 && !empty($_POST['sel_type']) && $_POST['stat_type'] == 3 && !empty($_POST['startDateFilter']) && !empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type='".$_POST['sel_type']."'";
                                $dateFilter = "AND (fld_se_confirmed_ts BETWEEN '".$_POST['startDateFilter']."' AND '".$_POST['endDateFilter']."')";
                                $stageFilter = "AND fld_se_confirmed_ts IS NOT NULL";
                            }
                            // [STAGE 3] show ALL entity types amd selected status filter is production and date filters are not empty
                            elseif ($_POST['sel_type'] == 1 && $_POST['stat_type'] == 3 && !empty($_POST['startDateFilter']) && !empty($_POST['endDateFilter'])) {
                                $entityFilter = "AND fld_type<>''";
                                $dateFilter = "AND (fld_se_confirmed_ts BETWEEN '".$_POST['startDateFilter']."' AND '".$_POST['endDateFilter']."')";
                                $stageFilter = "AND fld_se_confirmed_ts IS NOT NULL";
                            }
                        }

                        // query
                        $show_seis_table = $dbh4->query("SELECT AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS company_name, 
                        AES_DECRYPT(fld_fname_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_fname_ar, 
                        AES_DECRYPT(fld_mname_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_mname_ar, 
                        AES_DECRYPT(fld_lname_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_lname_ar, 
                        AES_DECRYPT(fld_extname_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_extname_ar, 
                        AES_DECRYPT(fld_position_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_position_ar, 
                        AES_DECRYPT(fld_contactno_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_contactno_ar, 
                        AES_DECRYPT(fld_email_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_email_ar,
                        AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_provcode,
                        AES_DECRYPT(fld_fname_c1, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_fname_c1, 
                        AES_DECRYPT(fld_email_c1, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_email_c1,    
                        fld_registration_upload, fld_uat_ceportal_sent_ts, fld_se_confirmed_ts, fld_ctrlno, fld_type, fld_noc_ts FROM `tbentities` WHERE fld_registration_type = 1 $entityFilter $dateFilter $stageFilter $stageClause");
                    }
                    // default view
                    else {
                        $show_seis_table = $dbh4->query("SELECT AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS company_name, 
                        AES_DECRYPT(fld_fname_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_fname_ar, 
                        AES_DECRYPT(fld_mname_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_mname_ar, 
                        AES_DECRYPT(fld_lname_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_lname_ar, 
                        AES_DECRYPT(fld_extname_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_extname_ar, 
                        AES_DECRYPT(fld_position_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_position_ar, 
                        AES_DECRYPT(fld_contactno_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_contactno_ar, 
                        AES_DECRYPT(fld_email_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_email_ar,
                        AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_provcode,
                        AES_DECRYPT(fld_fname_c1, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_fname_c1, 
                        AES_DECRYPT(fld_email_c1, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_email_c1,     
                        fld_registration_upload, fld_uat_ceportal_sent_ts, fld_se_confirmed_ts, fld_ctrlno, fld_type, fld_noc_ts FROM `tbentities` WHERE fld_registration_type = 1 AND fld_type='".$_POST['sel_type']."' $stageClause");
                    }
                      
                        while ($cs_tb = $show_seis_table->fetch_array()) {

                            // validation for stages
                            if (!empty($cs_tb['fld_registration_upload']) && (empty($cs_tb['fld_uat_ceportal_sent_ts']) || !empty($cs_tb['fld_uat_ceportal_sent_ts']))) {
                                $stgno = 1;
                            }
                            elseif (!empty($cs_tb['fld_uat_ceportal_sent_ts']) && (empty($cs_tb['fld_se_confirmed_ts']) || !empty($cs_tb['fld_se_confirmed_ts']))) {
                                $stgno = 2;
                            }
                            elseif (!empty($cs_tb['fld_se_confirmed_ts']) && (empty($cs_tb['fld_noc_ts']) || !empty($cs_tb['fld_noc_ts']))) {
                                $stgno = 3;
                            }

                            // for counting of tickets
                            $countTickets = $dbh4->query("SELECT fld_ticket_id FROM tbict_freshdesk WHERE fld_ctrlno = '".$cs_tb['fld_ctrlno']."' AND fld_status IN (2, 3, 9) $group");
                            $ticketTotal = mysqli_num_rows($countTickets);

                            // $noc = $dbh4->query("SELECT fld_noc_ts FROM tbentities WHERE fld_ctrlno = '".$cs_tb['fld_ctrlno']."'");
                            // $rownoc = $noc->fetch_array();

                            // checking for approved extension
                            $extCheck = $dbh4->query("SELECT * FROM tbextensionicmt WHERE fld_ext_status = 1 AND fld_ext_date IS NOT NULL AND fld_ctrlno = '".$cs_tb['fld_ctrlno']."'");
                            $ext = $extCheck->fetch_array();

                            // checking for microsession scheduled
                            $microsession = $dbh4->query("SELECT fld_ctrlno, fld_provcode, fld_schedule_date, fld_process_type, fld_status, fld_schedule_ts, GROUP_CONCAT(fld_name SEPARATOR ', ') AS attendees , GROUP_CONCAT(fld_id SEPARATOR '|') AS ids FROM tbmicrosession WHERE fld_ctrlno = '".$cs_tb['fld_ctrlno']."' GROUP BY fld_schedule_date ORDER BY fld_schedule_ts DESC");
                            $mic = $microsession->fetch_array();

                            // checking for corrective action
                            $correctiveAction = $dbh4->query("SELECT * FROM tbict_corrective_action WHERE fld_ctrlno = '".$cs_tb['fld_ctrlno']."' ORDER BY fld_corrective_action_ts DESC LIMIT 1");
                            $crat = $correctiveAction->fetch_array();

                            $stage1StartDate = new DateTime($cs_tb['fld_registration_upload']);
                            $stage1 = $stage1StartDate->format('Y-m-d');

                            $stage2StartDate = new DateTime($cs_tb['fld_uat_ceportal_sent_ts']);
                            $stage2 = $stage2StartDate->format('Y-m-d');

                            $stage3StartDate = new DateTime($cs_tb['fld_se_confirmed_ts']);
                            $stage3 = $stage3StartDate->format('Y-m-d');

                            // holiday validation
                            if (!empty($cs_tb['fld_registration_upload']) && empty($cs_tb['fld_uat_ceportal_sent_ts'])) {
                                $holClause = "WHERE fld_date >= '".$stage1."'";
                            }
                            elseif (!empty($cs_tb['fld_uat_ceportal_sent_ts']) && empty($cs_tb['fld_se_confirmed_ts'])) {
                                $holClause = "WHERE fld_date >= '".$stage2."'";
                            }
                            elseif (!empty($cs_tb['fld_se_confirmed_ts']) && empty($cs_tb['fld_noc_ts'])) {
                                $holClause = "WHERE fld_date >= '".$stage3."'";
                            }
                            elseif (!empty($cs_tb['fld_se_confirmed_ts']) && !empty($cs_tb['fld_noc_ts'])) {
                                $holClause = "";
                            }
                            
                            // checking for holidays
                            $holiDates = array();
                            $holiday = $dbh7->query("SELECT fld_date FROM tb_hr_holidays $holClause");

                            if (!$holiday) {
                                $hDay = 0;
                            }
                            else {
                            //$holiday = $dbh7->query("SELECT fld_date FROM tb_hr_holidays WHERE fld_date >= '".$cs_tb['fld_registration_upload']."'");
                                while ($hDay = $holiday->fetch_array()) {
                                    $holiDates[] = $hDay['fld_date'];
                                }
                            
                            // array of data controllers in stage 2
                            $ict_stage2 = array("1" => "149", "2" => "131", "3" => "177", "4" => "164", "5" => "197");
                            //$cnt = count($ict_stage2);

                            $currDate = Date('Y-m-d H:i:s');

                            // ---------- registration diff ----------
                            $reg_start = new DateTime($cs_tb['fld_registration_upload']); //$cs_tb['fld_registration_upload']
                            $reg_end = new DateTime($cs_tb['fld_uat_ceportal_sent_ts']);
                            $reg_end->modify('+1 day');

                            $rg_start = $reg_start->format('Y-m-d H:i:s');

                            $reg_int = $reg_end->diff($reg_start);

                            $reg_days = $reg_int->days;
                            $reg_period = new DatePeriod($reg_start, new DateInterval('P1D'), $reg_end);

                            foreach($reg_period as $dt){
                                $reg_current = $dt->format('D');

                                if ($reg_current == 'Sat' || $reg_current == 'Sun') {
                                    $reg_days--;
                                }
                                elseif (in_array($dt->format('Y-m-d'), $holiDates)) {
                                    $reg_days--;
                                }
                            }


                            // ---------- testing & validation diff ----------
                            $test_val_start = new DateTime($cs_tb['fld_uat_ceportal_sent_ts']);
                            // for microsession 
                            if ($mic['fld_schedule_date'] >= $currDate) {
                                $test_val_end = new DateTime($mic['fld_schedule_ts']);
                            }
                            elseif (($mic['fld_schedule_date'] < $currDate) || (!$mic)) {
                                 // default 
                                 $test_val_end = new DateTime($cs_tb['fld_se_confirmed_ts']);
 
                                 // for extension continuation
                                 $tv_mss_sched_ts = new DateTime($mic['fld_schedule_ts']);
                                 $tv_mss_sched = new DateTime($mic['fld_schedule_date']);
                                 $tv_mss_sched->modify('+1 day');
 
                                 $testval_mss_int = $tv_mss_sched->diff($tv_mss_sched_ts);
                                 $testval_mss_days = $testval_mss_int->days;
                                 $tv_mss_period = new DatePeriod($tv_mss_sched_ts, new DateInterval('P1D'), $tv_mss_sched);
                            }

                            //$test_val_end->modify('+1 day');

                            $tv_start = $test_val_start->format('Y-m-d');

                            $test_val_int = $test_val_end->diff($test_val_start);

                            $test_val_days = $test_val_int->days;
                            $test_val_period = new DatePeriod($test_val_start, new DateInterval('P1D'), $test_val_end);

                            // weekend and holiday exception in microsession
                            foreach($tv_mss_period as $tv_mss_dt) {
                                $tv_mss_current = $tv_mss_dt->format('D');

                                if ($tv_mss_current == 'Sat' || $tv_mss_current == 'Sun') {
                                    $testval_mss_days--;
                                }
                                elseif (in_array($tv_mss_dt->format('Y-m-d'), $holiDates)) {
                                    $testval_mss_days--;
                                }
                            }

                            // weekend and holiday exception 
                            foreach($test_val_period as $dt1) {
                                $test_val_current = $dt1->format('D');

                                if ($test_val_current == 'Sat' || $test_val_current == 'Sun') {
                                    $test_val_days--;
                                }
                                elseif (in_array($dt1->format('Y-m-d'), $holiDates)) {
                                    $test_val_days--;
                                }
                            }

                            // date diff total with microsession
                            $tv_mss_diff = $test_val_days - $testval_mss_days;


                            // ---------- production diff ----------
                            $prod_start = new DateTime($cs_tb['fld_se_confirmed_ts']);
                            // for microsession
                            if ($mic['fld_schedule_date'] >= $currDate) {
                                $prod_end = new DateTime($mic['fld_schedule_ts']);
                            }
                            elseif (($mic['fld_schedule_date'] < $currDate) || (!$mic)) {
                                // default
                                $prod_end = new DateTime($cs_tb['fld_noc_ts']);

                                // for extension continuation
                                $p_mss_sched_ts = new DateTime($mic['fld_schedule_ts']);
                                $p_mss_sched = new DateTime($mic['fld_schedule_date']);
                                $p_mss_sched->modify('+1 day');
                              
                                $prod_mss_int = $p_mss_sched->diff($p_mss_sched_ts);
                                $prod_mss_days = $prod_mss_int->days;
                                $p_mss_period = new DatePeriod($p_mss_sched_ts, new DateInterval('P1D'), $p_mss_sched);
                            }

                            //$prod_end->modify('+1 day');

                            $pr_start = $prod_start->format('Y-m-d');

                            $prod_int = $prod_end->diff($prod_start);

                            $prod_days = $prod_int->days;
                            $prod_period = new DatePeriod($prod_start, new DateInterval('P1D'), $prod_end);

                            // // weekend and holiday exception in microsession
                            foreach($p_mss_period as $p_mss_dt) {
                                $p_mss_current = $p_mss_dt->format('D');

                                if ($p_mss_current == 'Sat' || $p_mss_current == 'Sun') {
                                    $prod_mss_days--;
                                }
                                elseif (in_array($p_mss_dt->format('Y-m-d'), $holiDates)) {
                                    $prod_mss_days--;
                                }
                            }

                            // weekend and holiday exception
                            foreach($prod_period as $dt2) {
                                $prod_current = $dt2->format('D');

                                if ($prod_current == 'Sat' || $prod_current == 'Sun') {
                                    $prod_days--;
                                }
                                elseif (in_array($dt2->format('Y-m-d'), $holiDates)) {
                                    $prod_days--;
                                }
                            }

                            // date diff total with microsession
                            $p_mss_diff = $prod_days - $prod_mss_days;

                            $limDate = date('Y-m-d H:i:s', strtotime('2025-05-25 00:00:00'));
                            
                            // ---------- logic for registration ----------
                            // completed within 15 days
                            if ($reg_days <= 15 && !empty($cs_tb['fld_uat_ceportal_sent_ts'])) {
                                $registration_stat = "<font color=GREEN><b>Completed</b></font>";
                                $reg_days_w_color = "<font color=GREEN>" . $reg_days . " Days" . "</font>";
                                $reg_start_date = "<font color=GREEN> (". $cs_tb['fld_uat_ceportal_sent_ts'] . ")</font>";

                                // completion email notification
                                if ($cs_tb['fld_uat_ceportal_sent_ts'] > $limDate) {
                                    // check if the SE has not been sent an email
                                    $checkS1email = $dbh4->query("SELECT * FROM tbict_emailer WHERE fld_ctrlno = '".$cs_tb['fld_ctrlno']."' AND fld_emailer_type = 1");
                                    if (!$chkS1email = $checkS1email->fetch_array()) {
                                        $stageNo = "1";
                                        $currProcess = "Registration";
                                        $processType = "2, Testing and Validation";
                                        $include = include 'PHPMailer/mailer-icmt/completion.emailer.php';
                                    }
                                }
                                else {
                                    $include = '';
                                }

                                echo $include;
                            }
                            // completed but overdue
                            elseif ($reg_days > 15 && !empty($cs_tb['fld_uat_ceportal_sent_ts'])) {
                                // completed with extension
                                if ($ext['fld_process_type'] == 1 && (!empty($ext['fld_ext_date_ict']) || !empty($ext['fld_ext_date']))) {
                                    // completed within extension period
                                    if ($ext['fld_process_type'] == 1 && ($ext['fld_ext_date_ict'] >= $cs_tb['fld_uat_ceportal_sent_ts']) || ($ext['fld_ext_date'] >= $cs_tb['fld_uat_ceportal_sent_ts'])) {
                                        $registration_stat = "<font color=GREEN><b>Completed (With Extension)</b></font>";
                                        $reg_days_w_color = "<font color=GREEN>" . $reg_days . " Days" . "</font>";
                                        $reg_start_date = "<font color=GREEN> (". $cs_tb['fld_uat_ceportal_sent_ts'] . ")</font>"; 
                                    }
                                    // completed overdue with extension
                                    elseif ($ext['fld_process_type'] == 1 && ($ext['fld_ext_date_ict'] < $cs_tb['fld_uat_ceportal_sent_ts']) || ($ext['fld_ext_date'] < $cs_tb['fld_uat_ceportal_sent_ts'])) {
                                        $registration_stat = "<font color=RED><b>Completed (With Extension)</b></font>";
                                        $reg_days_w_color = "<font color=RED>" . $reg_days . " Days" . "</font>";
                                        $reg_start_date = "<font color=RED> (". $cs_tb['fld_uat_ceportal_sent_ts'] . ")</font>";
                                    }
                                }
                                // without extension
                                else {
                                    $registration_stat = "<font color=RED><b>Completed</b></font>";
                                    $reg_days_w_color = "<font color=RED>" . $reg_days . " Days" . "</font>";
                                    $reg_start_date = "<font color=RED> (". $cs_tb['fld_uat_ceportal_sent_ts'] . ")</font>";
                                }

                                // completion email notification
                                if ($cs_tb['fld_uat_ceportal_sent_ts'] > $limDate) {
                                    // check if the SE has not been sent an email
                                    $checkS1email = $dbh4->query("SELECT * FROM tbict_emailer WHERE fld_ctrlno = '".$cs_tb['fld_ctrlno']."' AND fld_emailer_type = 1");
                                    if (!$chkS1email = $checkS1email->fetch_array()) {
                                        $stageNo = "1";
                                        $currProcess = "Registration";
                                        $processType = "2, Testing and Validation";
                                        $include = include 'PHPMailer/mailer-icmt/completion.emailer.php';
                                    }
                                }
                                else {
                                    $include = '';
                                }
                                echo $include;
                            }
                            // overdue
                            elseif ($reg_days > 15 && empty($cs_tb['fld_uat_ceportal_sent_ts'])) {
                                // no extension request
                                if (empty($ext['fld_ext_status'])) {
                                    $registration_stat = "<font color=RED><b>In Progress</b></font>";
                                    $reg_days_w_color = "<font color=RED>" . $reg_days . " Days" . "</font>";
                                    $reg_start_date = "<font color=RED> (" . $rg_start . ")</font>";
                                }
                                // has extension and overridden by ict
                                elseif ($ext['fld_process_type'] == 1 && !empty($ext['fld_ext_date_ict'])) {
                                    // extension period done & still overdue
                                    if ($ext['fld_process_type'] == 1 && ($ext['fld_ext_date_ict'] < $currDate)) {
                                        $registration_stat = "<font color=RED><b>In Progress (Extension Period Done)</b></font>";
                                        $reg_days_w_color = "<font color=RED>" . $reg_days . " Days" . "</font>";
                                        $reg_start_date = "<font color=RED>(" . $rg_start . ")</font>"; 
                                    }
                                    // ongoing extension period
                                    elseif ($ext['fld_process_type'] == 1 && ($ext['fld_ext_date_ict'] >= $currDate)) {
                                        $registration_stat = "<font color=ORANGE><b>In Progress (Extended)</b></font>";
                                        $reg_days_w_color = "<font color=ORANGE>" . $reg_days . " Days" . "</font>";
                                        $reg_start_date = "<font color=ORANGE>(" . $rg_start . ")</font>";
                                    }
                                }
                                // has extension
                                elseif ($ext['fld_process_type'] == 1 && empty($ext['fld_ext_date_ict'])) {
                                    // extension period done & still overdue
                                    if ($ext['fld_process_type'] == 1 && ($ext['fld_ext_date'] < $currDate)) {
                                        $registration_stat = "<font color=RED><b>In Progress (Extension Period Done)</b></font>";
                                        $reg_days_w_color = "<font color=RED>" . $reg_days . " Days" . "</font>";
                                        $reg_start_date = "<font color=RED>(" . $rg_start . ")</font>"; 
                                    }
                                    // ongoing extension period
                                    elseif ($ext['fld_process_type'] == 1 && ($ext['fld_ext_date'] >= $currDate)) {
                                        $registration_stat = "<font color=ORANGE><b>In Progress (Extended)</b></font>";
                                        $reg_days_w_color = "<font color=ORANGE>" . $reg_days . " Days" . "</font>";
                                        $reg_start_date = "<font color=ORANGE>(" . $rg_start . ")</font>";
                                    }
                                }
                            }
                            // in progress
                            elseif ($reg_days <= 15 && empty($cs_tb['fld_uat_ceportal_sent_ts'])) {
                                // checking if submitting entity is already in db
                                $checkStage1 = $dbh->query("SELECT * FROM tbictpersonnel WHERE fld_ctrlno = '".$cs_tb['fld_ctrlno']."' AND fld_stage = 1");
                                
                                // submitting entity does not exist in db
                                if (!$cs1 = $checkStage1->fetch_array()) {
                                    // inserting submitting entity in db
                                    if ($dbh->query("INSERT INTO tbictpersonnel (fld_userid, fld_ctrlno, fld_stage, fld_insertpersonnel_ts, fld_freshdesk_id) VALUES ('75', '".$cs_tb['fld_ctrlno']."', '1', '".$currDate."', '33023612951')")) {
                                        $msg = "Auto assign success";
                                        $msgclr = "success";
                                    }
                                }
                                // submitting entity exist
                                else {
                                    $registration_stat = "<font color=ORANGE><b>In Progress</b></font>";
                                    $reg_days_w_color = "<font color=ORANGE>" . $reg_days . " Days" . "</font>";
                                    $reg_start_date = "<font color=ORANGE>(" . $rg_start . ")</font>";
                                }
                            }


                            // ---------- logic for testing & validation ----------
                            // completed within 30 days
                            if ($test_val_days <= 30 && !empty($cs_tb['fld_se_confirmed_ts'])) {
                                $test_val_stat = "<font color=GREEN><b>Completed</b></font>";
                                $test_val_days_w_color = "<font color=GREEN>" . $test_val_days . " Days" . "</font>";
                                $test_val_start_date = "<font color=GREEN>(". $cs_tb['fld_se_confirmed_ts'] . ")</font>";

                                // completion email notification
                                if ($cs_tb['fld_se_confirmed_ts'] > $limDate) {
                                    // check if the SE has not been sent an email
                                    $checkS2email = $dbh4->query("SELECT * FROM tbict_emailer WHERE fld_ctrlno = '".$cs_tb['fld_ctrlno']."' AND fld_emailer_type = 2");
                                    if (!$chkS2email = $checkS2email->fetch_array()) {
                                        $stageNo = "2";
                                        $currProcess = "Testing and Validation";
                                        $processType = "3, Production";
                                        $include = include 'PHPMailer/mailer-icmt/completion.emailer.php';
                                    }
                                }
                                else {
                                    $include = '';
                                }

                                echo $include;
                            }
                            // completed but overdue
                            elseif ($test_val_days > 30 && !empty($cs_tb['fld_se_confirmed_ts'])) {
                                // completed with extension
                                if ($ext['fld_process_type'] == 2 && (!empty($ext['fld_ext_date_ict']) || !empty($ext['fld_ext_date']))) {
                                    // completed within extension period
                                    if ($ext['fld_process_type'] == 2 && ($ext['fld_ext_date_ict'] >= $cs_tb['fld_se_confirmed_ts']) || ($ext['fld_ext_date'] >= $cs_tb['fld_se_confirmed_ts'])) {
                                        $test_val_stat = "<font color=GREEN><b>Completed (With Extension)</b></font>";
                                        $test_val_days_w_color = "<font color=GREEN>" . $test_val_days . " Days" . "</font>";
                                        $test_val_start_date = "<font color=GREEN> (". $cs_tb['fld_se_confirmed_ts'] . ")</font>"; 
                                    }
                                    // completed overdue with extension
                                    elseif ($ext['fld_process_type'] == 2 && ($ext['fld_ext_date_ict'] < $cs_tb['fld_se_confirmed_ts']) || ($ext['fld_ext_date'] < $cs_tb['fld_se_confirmed_ts'])) {
                                        $test_val_stat = "<font color=RED><b>Completed (With Extension)</b></font>";
                                        $test_val_days_w_color = "<font color=RED>" . $test_val_days . " Days" . "</font>";
                                        $test_val_start_date = "<font color=RED> (". $cs_tb['fld_se_confirmed_ts'] . ")</font>";
                                    }
                                }
                                // without extension
                                else {
                                    $test_val_stat = "<font color=RED><b>Completed</b></font>";
                                    $test_val_days_w_color = "<font color=RED>" . $test_val_days . " Days" . "</font>";
                                    $test_val_start_date = "<font color=RED>(". $cs_tb['fld_se_confirmed_ts'] . ")</font>";
                                }

                                // completion email notification
                                if ($cs_tb['fld_se_confirmed_ts'] > $limDate) {
                                    // check if the SE has not been sent an email
                                    $checkS2email = $dbh4->query("SELECT * FROM tbict_emailer WHERE fld_ctrlno = '".$cs_tb['fld_ctrlno']."' AND fld_emailer_type = 2");
                                    if (!$chkS2email = $checkS2email->fetch_array()) {
                                        $stageNo = "2";
                                        $currProcess = "Testing and Validation";
                                        $processType = "3, Production";
                                        $include = include 'PHPMailer/mailer-icmt/completion.emailer.php';
                                    }
                                }
                                else {
                                    $include = '';
                                }

                                echo $include;
                            }
                            // not started
                            elseif (empty($cs_tb['fld_uat_ceportal_sent_ts']) && empty($cs_tb['fld_se_confirmed_ts'])) { 
                                $test_val_stat = "Not Started";
                                $test_val_days_w_color = "";
                                $test_val_start_date = "";
                            }
                            // overdue
                            elseif ($test_val_days > 30 && empty($cs_tb['fld_se_confirmed_ts'])) {
                                // if microsession exists
                                if ($mic) {
                                    // if SE has filled a microsession counting will stop on the day it was filed (regardless if approved or not)
                                    if ($mic && $mic['fld_process_type'] == 2 && ($mic['fld_schedule_date'] >= $currDate)) {
                                        $test_val_stat = "<font color=ORANGE><b>COUNTING STOPPED</b></font>";
                                        $test_val_days_w_color = "<font color=ORANGE>" . $test_val_days . " Days" . "</font>";
                                        $test_val_start_date = "<font color=ORANGE>(Microsession Scheduled Date: ". $mic['fld_schedule_date'] . ")</font>";
                                    }
                                    // resume counting - microsession schedule is done and is overdue
                                    elseif ($mic && $mic['fld_process_type'] == 2 && ($mic['fld_schedule_date'] < $currDate) && ($tv_mss_diff > 30)) {
                                        $test_val_stat = "<font color=RED><b>In Progress (COUNTING RESUMED)</b></font>";
                                        $test_val_days_w_color = "<font color=RED>" . $tv_mss_diff . " Days </font>";
                                        $test_val_start_date = "<font color=RED>(" . $tv_start . ")</font>";
                                    }
                                    // resume counting - microsession is done and is not overdue
                                    elseif ($mic && $mic['fld_process_type'] == 2 && ($mic['fld_schedule_date'] < $currDate) && ($tv_mss_diff <= 30)) {
                                        $test_val_stat = "<font color=ORANGE><b>In Progress (COUNTING RESUMED)</b></font>";
                                        $test_val_days_w_color = "<font color=ORANGE>" . $tv_mss_diff . " Days </font>";
                                        $test_val_start_date = "<font color=ORANGE>(" . $tv_start . ")</font>";
                                    }
                                }
                                // if microsession does not exist or microsession is overdue
                                elseif (!$mic || ($mic && ($tv_mss_diff > 30))) {
                                    // no extension request
                                    if (empty($ext['fld_ext_status'])) {
                                        $test_val_stat = "<font color=RED><b>In Progress</b></font>";
                                        $test_val_days_w_color = "<font color=RED>" . $test_val_days . " Days" . "</font>";
                                        $test_val_start_date = "<font color=RED>(" . $tv_start . ")</font>";
                                    }
                                    // has extension and overridden by ict
                                    elseif ($ext['fld_process_type'] == 2 && !empty($ext['fld_ext_date_ict'])) {
                                        // extension period done & still overdue
                                        if ($ext['fld_process_type'] == 2 && ($ext['fld_ext_date_ict'] < $currDate)) {
                                            $test_val_stat = "<font color=RED><b>In Progress (Extension Period Done)</b></font>";
                                            $test_val_days_w_color = "<font color=RED>" . $test_val_days . " Days" . "</font>";
                                            $test_val_start_date = "<font color=RED>(" . $tv_start . ")</font>"; 
                                        }
                                        // ongoing extension period
                                        elseif ($ext['fld_process_type'] == 2 && ($ext['fld_ext_date_ict'] >= $currDate)) {
                                            $test_val_stat = "<font color=ORANGE><b>In Progress (Extended)</b></font>";
                                            $test_val_days_w_color = "<font color=ORANGE>" . $test_val_days . " Days" . "</font>";
                                            $test_val_start_date = "<font color=ORANGE>(" . $tv_start . ")</font>";
                                        }
                                    }
                                    // has extension
                                    elseif ($ext['fld_process_type'] == 2 && empty($ext['fld_ext_date_ict'])) {
                                        // extension period done & still overdue
                                        if ($ext['fld_process_type'] == 2 && ($ext['fld_ext_date'] < $currDate)) {
                                            $test_val_stat = "<font color=RED><b>In Progress (Extension Period Done)</b></font>";
                                            $test_val_days_w_color = "<font color=RED>" . $test_val_days . " Days" . "</font>";
                                            $test_val_start_date = "<font color=RED>(" . $tv_start . ")</font>"; 
                                        }
                                        // ongoing extension period
                                        elseif ($ext['fld_process_type'] == 2 && ($ext['fld_ext_date'] >= $currDate)) {
                                            $test_val_stat = "<font color=ORANGE><b>In Progress (Extended)</b></font>";
                                            $test_val_days_w_color = "<font color=ORANGE>" . $test_val_days . " Days" . "</font>";
                                            $test_val_start_date = "<font color=ORANGE>(" . $tv_start . ")</font>";
                                        }
                                    }
                                }   
                            }
                            // in progress (still in the 30 days period)
                            elseif ($test_val_days <= 30 && empty($cs_tb['fld_se_confirmed_ts'])) {
                                // checking if submitting entity is already in db
                                $checkStage2 = $dbh->query("SELECT * FROM tbictpersonnel WHERE fld_ctrlno = '".$cs_tb['fld_ctrlno']."' AND fld_stage = 2");

                                // submitting entity does not exist in db
                                if (!$cs2 = $checkStage2->fetch_array()) {
                                    // fetching last assigned data controller in db
                                    $getLastAssigned = $dbh->query("SELECT * FROM (SELECT * FROM tbictpersonnel WHERE fld_stage = 2 AND fld_insertpersonnel_ts IS NOT NULL ORDER BY fld_id DESC LIMIT 1) t ORDER BY fld_insertpersonnel_ts;");
                                    $gla = $getLastAssigned->fetch_array();
                                            
                                    // iteration for assigning SEs to data controllers
                                    for ($i = 1; $i <= count($ict_stage2); $i++) {
                                        // check if user id is in the array
                                        if ( $ict_stage2[$i] == $gla['fld_userid']) {
                                            $echo = "matched";
                                            // if last data controller is the last one in the array, go back to the first one
                                            if ($i == 5) {
                                                $nextAssigned = $ict_stage2[1];
                                                $index = $i;
                                            }
                                            // if data controller is not last
                                            else {
                                                $i++;
                                                $nextAssigned = $ict_stage2[$i];
                                                $index = $i;
                                            }
                                        }
                                    }

                                    // assigning of freshdesk agent ids to their portal
                                    if ($nextAssigned == 149) {
                                        $fd_id = '33038548901';
                                    }
                                    elseif ($nextAssigned == 197) {
                                        $fd_id = '33051285166';
                                    }
                                    elseif ($nextAssigned == 177) {
                                        $fd_id = '33047927537';
                                    }
                                    elseif ($nextAssigned == 131) {
                                        $fd_id = '33038691792';
                                    }
                                    elseif ($nextAssigned == 164) {
                                        $fd_id = '33044295641';
                                    }

                                    // inserting submitting entity in db
                                    if ($dbh->query("INSERT INTO tbictpersonnel (fld_userid, fld_ctrlno, fld_stage, fld_insertpersonnel_ts, fld_freshdesk_id) VALUES ('".$nextAssigned."', '".$cs_tb['fld_ctrlno']."', '2', '".$currDate."', '".$fd_id."')")) {
                                        $msg = "Auto assign success";
                                        $msgclr = "success";
                                    }
                                }
                                // submitting entity exist
                                else {
                                    // microsession doesn't exist
                                    if (!$mic) {
                                        $test_val_stat = "<font color=ORANGE><b>In Progress</b></font>";
                                        $test_val_days_w_color = "<font color=ORANGE>" . $test_val_days . " Days" . "</font>";
                                        $test_val_start_date = "<font color=ORANGE>(" . $tv_start . ")</font>";
                                    }
                                    // SE has filled microsession and counting will stop until microsession schedule has passed
                                    elseif ($mic && $mic['fld_process_type'] == 2 && ($mic['fld_schedule_date'] >= $currDate)) {
                                        $test_val_stat = "<font color=ORANGE><b>COUNTING STOPPED</b></font>";
                                        $test_val_days_w_color = "<font color=ORANGE>" . $test_val_days . " Days" . "</font>";
                                        $test_val_start_date = "<font color=ORANGE>(Microsession Scheduled Date: ". $mic['fld_schedule_date'] . ")</font>";
                                    }
                                    // microsession already done
                                    elseif ($mic && $tv_mss_diff <= 30 && $mic['fld_process_type'] == 2 && ($mic['fld_schedule_date'] < $currDate)) {
                                        $test_val_stat = "<font color=ORANGE><b>In Progress (COUNTING RESUMED)</b></font>";
                                        $test_val_days_w_color = "<font color=ORANGE>" . $tv_mss_diff . " Days </font>";
                                        $test_val_start_date = "<font color=ORANGE>(" . $tv_start . ")</font>";
                                    }
                                    // microsession already done and is already overdue
                                    elseif ($mic && $tv_mss_diff > 30 && $mic['fld_process_type'] == 2 && ($mic['fld_schedule_date'] < $currDate)) {
                                        $test_val_stat = "<font color=RED><b>In Progress (COUNTING RESUMED)</b></font>";
                                        $test_val_days_w_color = "<font color=RED>" . $tv_mss_diff . " Days </font>";
                                        $test_val_start_date = "<font color=RED>(" . $tv_start . ")</font>";
                                    }
                                }
                            }


                            // ---------- logic for production ----------
                            // completed within 15 days
                            if ($prod_days <= 15 && !empty($cs_tb['fld_noc_ts'])) {
                                $prod_stat = "<font color=GREEN><b>Completed</b></font>";
                                $prod_days_w_color = "<font color=GREEN>" . $prod_days . " Days" . "</font>";
                                $prod_start_date = "<font color=GREEN>(". $cs_tb['fld_noc_ts'] . ")</font>";

                                // completion email notification
                                if ($cs_tb['fld_noc_ts'] > $limDate) {
                                    // check if the SE has not been sent an email
                                    $checkS3email = $dbh4->query("SELECT * FROM tbict_emailer WHERE fld_ctrlno = '".$cs_tb['fld_ctrlno']."' AND fld_emailer_type = 3");
                                    if (!$chkS3email = $checkS3email->fetch_array()) {
                                        $include = include 'PHPMailer/mailer-icmt/se.completion.emailer.php';
                                    }
                                }
                                else {
                                    $include = '';
                                }

                                echo $include;
                            }
                            // completed but overdue
                            elseif ($prod_days > 15 && !empty($cs_tb['fld_noc_ts'])) {
                                // completed with extension
                                if ($ext['fld_process_type'] == 3 && (!empty($ext['fld_ext_date_ict']) || !empty($ext['fld_ext_date']))) {
                                    // completed within extension periodd
                                    if ($ext['fld_process_type'] == 3 && ($ext['fld_ext_date_ict'] >= $cs_tb['fld_noc_ts']) || ($ext['fld_ext_date'] >= $cs_tb['fld_noc_ts'])) {
                                        $prod_stat = "<font color=GREEN><b>Completed (With Extension)</b></font>";
                                        $prod_days_w_color = "<font color=GREEN>" . $prod_days . " Days" . "</font>";
                                        $prod_start_date = "<font color=GREEN> (". $cs_tb['fld_noc_ts'] . ")</font>"; 
                                    }
                                    // completed overdue with extension
                                    elseif ($ext['fld_process_type'] == 3 && ($ext['fld_ext_date_ict'] < $cs_tb['fld_noc_ts']) || ($ext['fld_ext_date'] < $cs_tb['fld_noc_ts'])) {
                                        $prod_stat = "<font color=RED><b>Completed (With Extension)</b></font>";
                                        $prod_days_w_color = "<font color=RED>" . $prod_days . " Days" . "</font>";
                                        $prod_start_date = "<font color=RED> (". $cs_tb['fld_noc_ts'] . ")</font>";
                                    }
                                }
                                // without extension
                                else {
                                    $prod_stat = "<font color=RED><b>Completed</b></font>";
                                    $prod_days_w_color = "<font color=RED>" . $prod_days . " Days" . "</font>";
                                    $prod_start_date = "<font color=RED>(". $cs_tb['fld_noc_ts'] . ")</font>";
                                }

                                // completion email notification
                                if ($cs_tb['fld_noc_ts'] > $limDate) {
                                    // check if the SE has not been sent an email
                                    $checkS3email = $dbh4->query("SELECT * FROM tbict_emailer WHERE fld_ctrlno = '".$cs_tb['fld_ctrlno']."' AND fld_emailer_type = 3");
                                    if (!$chkS3email = $checkS3email->fetch_array()) {
                                        $include = include 'PHPMailer/mailer-icmt/se.completion.emailer.php';
                                    }
                                }
                                else {
                                    $include = '';
                                }

                                echo $include;
                            }
                            // not started
                            elseif (empty($cs_tb['fld_se_confirmed_ts']) && empty($cs_tb['fld_noc_ts'])) {
                                $prod_stat = "Not Started";
                                $prod_days_w_color = "";
                                $prod_start_date = "";
                            }
                            // overdue
                            elseif ($prod_days > 15 && empty($cs_tb['fld_noc_ts'])) {
                                // if microsession exists
                                if ($mic) {
                                    // if SE has filled a microsession counting will stop on the day it was filed (regardless if approved or not)
                                    if ($mic && $mic['fld_process_type'] == 3 && ($mic['fld_schedule_date'] >= $currDate)) {
                                        $prod_stat = "<font color=ORANGE><b>COUNTING STOPPED</b></font>";
                                        $prod_days_w_color = "<font color=ORANGE>" . $prod_days . " Days" . "</font>";
                                        $prod_start_date = "<font color=ORANGE>(Microsession Scheduled Date: ". $mic['fld_schedule_date'] . ")</font>";
                                    }
                                    // resume counting - microsession schedule is done and is overdue
                                    elseif ($mic && $mic['fld_process_type'] == 3 && ($mic['fld_schedule_date'] < $currDate) && ($p_mss_diff > 15)) {
                                        $prod_stat = "<font color=RED><b>In Progress (COUNTING RESUMED)</b></font>";
                                        $prod_days_w_color = "<font color=RED>" . $p_mss_diff . " Days" . "</font>";
                                        $prod_start_date = "<font color=RED>(" . $pr_start . ")</font>";
                                    }
                                    // resume counting - microsession is done and is not overdue
                                    elseif ($mic && $mic['fld_process_type'] == 3 && ($mic['fld_schedule_date'] < $currDate) && ($p_mss_diff <= 15)) {
                                        $prod_stat = "<font color=ORANGE><b>In Progress (COUNTING RESUMED)</b></font>";
                                        $prod_days_w_color = "<font color=ORANGE>" . $p_mss_diff . " Days" . "</font>";
                                        $prod_start_date = "<font color=ORANGE>(" . $pr_start . ")</font>";
                                    }
                                }
                                // if microsession does not exist or microsession is overdue
                                elseif (!$mic || ($mic && ($p_mss_diff > 15))) {
                                    // no extension request
                                    if (empty($ext['fld_ext_status'])) {
                                        $prod_stat = "<font color=RED><b>In Progress</b></font>";
                                        $prod_days_w_color = "<font color=RED>" . $prod_days . " Days" . "</font>";
                                        $prod_start_date = "<font color=RED>(" . $pr_start . ")</font>";
                                    }
                                    // has extension and overridden by ict
                                    elseif ($ext['fld_process_type'] == 3 && !empty($ext['fld_ext_date_ict'])) {
                                        // extension period done & still overdue
                                        if ($ext['fld_process_type'] == 3 && ($ext['fld_ext_date_ict'] < $currDate)) { 
                                            $prod_stat = "<font color=RED><b>In Progress (Extension Period Done)</b></font>";
                                            $prod_days_w_color = "<font color=RED>" . $prod_days . " Days" . "</font>";
                                            $prod_start_date = "<font color=RED>(" . $pr_start . ")</font>";
                                        }
                                        // ongoing extension period
                                        elseif ($ext['fld_process_type'] == 3 && ($ext['fld_ext_date_ict'] >= $currDate)) { 
                                            $prod_stat = "<font color=ORANGE><b>In Progress (Extended)</b></font>";
                                            $prod_days_w_color = "<font color=ORANGE>" . $prod_days . " Days" . "</font>";
                                            $prod_start_date = "<font color=ORANGE>(" . $pr_start . ")</font>";
                                        } 
                                    }
                                    // has extension
                                    elseif ($ext['fld_process_type'] == 3 && empty($ext['fld_ext_date_ict'])) {
                                        // extension period done & still overdue
                                        if ($ext['fld_process_type'] == 3 && ($ext['fld_ext_date'] < $currDate)) {
                                            $prod_stat = "<font color=RED><b>In Progress (Extension Period Done)</b></font>";
                                            $prod_days_w_color = "<font color=RED>" . $prod_days . " Days" . "</font>";
                                            $prod_start_date = "<font color=RED>(" . $pr_start . ")</font>";
                                        }
                                        // ongoing extension period
                                        elseif ($ext['fld_process_type'] == 3 && ($ext['fld_ext_date'] >= $currDate)    ) {
                                            $prod_stat = "<font color=ORANGE><b>In Progress (Extended)</b></font>";
                                            $prod_days_w_color = "<font color=ORANGE>" . $prod_days . " Days" . "</font>";
                                            $prod_start_date = "<font color=ORANGE>(" . $pr_start . ")</font>";
                                        }
                                    }
                                }
                            }
                            // in progress (still in the 15 days period)
                            elseif ($prod_days <= 15 && empty($cs_tb['fld_noc_ts'])) {
                                // checking if submitting entity is already in db
                                $checkStage3 = $dbh->query("SELECT * FROM tbictpersonnel WHERE fld_ctrlno = '".$cs_tb['fld_ctrlno']."' AND fld_stage = 3");
                                
                                // submitting entity does not exist in db
                                if (!$cs3 = $checkStage3->fetch_array()) {
                                    // inserting submitting entity in db
                                    if ($dbh->query("INSERT INTO tbictpersonnel (fld_userid, fld_ctrlno, fld_stage, fld_insertpersonnel_ts, fld_freshdesk_id) VALUES ('32', '".$cs_tb['fld_ctrlno']."', '3', '".$currDate."', '33009541782')")) {
                                        $msg = "Auto assign success";
                                        $msgclr = "success";
                                    }
                                }
                                // submitting entity exist
                                else {
                                    // microsession doesn't exist
                                    if (!$mic) {
                                        $prod_stat = "<font color=ORANGE><b>In Progress</b></font>";
                                        $prod_days_w_color = "<font color=ORANGE>" . $prod_days . " Days" . "</font>";
                                        $prod_start_date = "<font color=ORANGE>(" . $pr_start . ")</font>";
                                    }
                                    // SE has filled microsession and counting will stop until microsession schedule has passed
                                    elseif ($mic && $mic['fld_process_type'] == 3 && ($mic['fld_schedule_date'] >= $currDate)) {
                                        $prod_stat = "<font color=ORANGE><b>COUNTING STOPPED</b></font>";
                                        $prod_days_w_color = "<font color=ORANGE>" . $prod_days . " Days" . "</font>";
                                        $prod_start_date = "<font color=ORANGE>(Microsession Scheduled Date: ". $mic['fld_schedule_date'] . ")</font>";
                                    }
                                    // microsession already done
                                    elseif ($mic && $p_mss_diff <= 15 && $mic['fld_process_type'] == 3 && ($mic['fld_schedule_date'] < $currDate)) {
                                        $prod_stat = "<font color=ORANGE><b>In Progress (COUNTING RESUMED)</b></font>";
                                        $prod_days_w_color = "<font color=ORANGE>" . $p_mss_diff . " Days" . "</font>";
                                        $prod_start_date = "<font color=ORANGE>(" . $pr_start . ")</font>";
                                    }
                                    // microsession already done and is already overdue
                                    elseif ($mic && $p_mss_diff > 15 && $mic['fld_process_type'] == 3 && ($mic['fld_schedule_date'] < $currDate)) {
                                        $prod_stat = "<font color=RED><b>In Progress (COUNTING RESUMED)</b></font>";
                                        $prod_days_w_color = "<font color=RED>" . $p_mss_diff . " Days" . "</font>";
                                        $prod_start_date = "<font color=RED>(" . $pr_start . ")</font>";
                                    }
                                }
                            }

                            // ---------- circle color ----------
                            if (!empty($cs_tb['fld_registration_upload']) && empty($cs_tb['fld_uat_ceportal_sent_ts'])) {
                                $circle = "GRAY";
                            }
                            elseif (!empty($cs_tb['fld_uat_ceportal_sent_ts']) && empty($cs_tb['fld_se_confirmed_ts'])) {
                                $circle = "YELLOW";
                            }
                            elseif (!empty($cs_tb['fld_se_confirmed_ts']) && empty($cs_tb['fld_noc_ts']) || !empty($cs_tb['fld_noc_ts'])) {
                                $circle = "BLUE";
                            }

                            // ---------- penalty computation ----------
                            $pen_date_start = new DateTime($crat['fld_icmt_corracc5_off_type_ts']);
                            $pen_date_end = new DateTime();
                        
                            $pen_date_end->modify('+1 day');
                            $pen_date_end->format('Y-m-d');

                            $pen_diff = $pen_date_start->diff($pen_date_end);
                            $pen_amount = 0;
                           
                            $pen_start = new DateTime($crat['fld_icmt_corracc5_off_type_ts']);
                            $penalty_start = $pen_start->format('Y-m-d');
                            
                            // minor penalty
                            if ($crat['fld_icmt_corracc5_off_type'] == 1) {
                                if ($crat['fld_icmt_corracc5_off_lvl'] == 1) {
                                    $pen_amount = 1000;
                                }
                                elseif ($crat['fld_icmt_corracc5_off_lvl'] == 2) {
                                    $pen_amount = 2500;
                                }
                                elseif ($crat['fld_icmt_corracc5_off_lvl'] == 3) {
                                    $pen_amount = 5000;
                                }
                                elseif ($crat['fld_icmt_corracc5_off_lvl'] == 4) {
                                    $pen_amount = 10000;
                                }
                            }

                            // major penalty
                            elseif ($crat['fld_icmt_corracc5_off_type'] == 2) {
                                if ($crat['fld_icmt_corracc5_off_lvl'] == 1) {
                                    $pen_amount = 15000;
                                }
                                elseif ($crat['fld_icmt_corracc5_off_lvl'] == 2) {
                                    $pen_amount = 20000;
                                }
                                elseif ($crat['fld_icmt_corracc5_off_lvl'] == 3) {
                                    $pen_amount = 25000;
                                }
                                elseif ($crat['fld_icmt_corracc5_off_lvl'] == 4) {
                                    $pen_amount = 30000;
                                }
                            }

                            // penalty total
                            $pen_total = $pen_amount * $pen_diff->days;

                            // penalty is equals to zero
                            if ($pen_total == 0) {
                                $pen_view = "";
                                $pen_date = "";
                            }
                            // penalty is greater than zero
                            elseif ($pen_total > 0) {
                                $pen_view = "<b>₱" . number_format($pen_total) . "</b>";
                                $pen_date = "<small><b>Penalty Date: </b>" . $penalty_start . "</small>";
                            }

                            // // email reminder before overdue
                            // $extendedDate = new DateTime($ext['fld_ext_date']);
                            // $extendedDateByICT = new DateTime($ext['fld_ext_date_ict']);
                            // $currDateObj = new DateTime($currDate);
                            
                            // $checkOverdue = $dbh4->query("SELECT * FROM tbict_emailer WHERE fld_ctrlno = '".$cs_tb['fld_ctrlno']."' AND fld_emailer_type = 4");
                            // if(!$chkDue = $checkOverdue->fetch_array()) {
                            //     // with microsession
                            //     if ($mic) {
                            //         //testing & validation
                            //         if ($tv_mss_diff == 20) {
                            //             $stage = "2";
                            //             $days = "ten (10)";
                            //             $include = include 'PHPMailer/mailer-icmt/reminder.mailer.php';
                            //             echo $include;
                            //         }
                            //         //production
                            //         elseif ($p_mss_diff == 10) {
                            //             $stage = "3";
                            //             $days = "five (5)";
                            //             $include = include 'PHPMailer/mailer-icmt/reminder.mailer.php';
                            //             echo $include;
                            //         }
                            //     }
                            //     // with extension but ict overridden the extension date
                            //     elseif (!empty($ext['fld_ext_date_ict']) && !empty($ext['fld_ext_date'])) {
                            //         $ictExtSumInt = $extendedDateByICT->diff($currDateObj);
                            //         $ictExtSum = $ictExtSumInt->days;

                            //         //registration
                            //         if ($ext['fld_process_type'] == 1 && $ictExtSum == 5) {
                            //             $stage = "1";
                            //             $days = "five (5)";
                            //             $include = include 'PHPMailer/mailer-icmt/reminder.mailer.php';
                            //             echo $include;
                            //         }
                            //         // testing & validation
                            //         elseif ($ext['fld_process_type'] == 2 && $ictExtSum == 10) {
                            //             $stage = "2";
                            //             $days = "ten (10)";
                            //             $include = include 'PHPMailer/mailer-icmt/reminder.mailer.php';
                            //             echo $include;
                            //         }
                            //         // production
                            //         elseif ( $ext['fld_process_type'] == 3 && $ictExtSum == 5) {
                            //             $stage = "3";
                            //             $days = "five (5)";
                            //             $include = include 'PHPMailer/mailer-icmt/reminder.mailer.php';
                            //             echo $include;
                            //         }
                            //     }
                            //     // with extension but no ict override
                            //     elseif (empty($ext['fld_ext_date_ict']) && !empty($ext['fld_ext_date'])) {
                            //         $ictExtSumInt = $extendedDate->diff($currDateObj);
                            //         $ictExtSum = $ictExtSumInt->days;

                            //         //registration
                            //         if ($ext['fld_process_type'] == 1 && $ictExtSum == 5) {
                            //             $stage = "1";
                            //             $days = "five (5)";
                            //             $include = include 'PHPMailer/mailer-icmt/reminder.mailer.php';
                            //             echo $include;
                            //         }
                            //         // testing & validation
                            //         elseif ($ext['fld_process_type'] == 2 && $ictExtSum == 10) {
                            //             $stage = "2";
                            //             $days = "ten (10)";
                            //             $include = include 'PHPMailer/mailer-icmt/reminder.mailer.php';
                            //             echo $include;
                            //         }
                            //         // production
                            //         elseif ($ext['fld_process_type'] == 3 && $ictExtSum == 5) {
                            //             $stage = "3";
                            //             $days = "five (5)";
                            //             $include = include 'PHPMailer/mailer-icmt/reminder.mailer.php';
                            //             echo $include;
                            //         }
                            //     }                                                      
                            //     else {
                            //         $ictExtSum = "";
                            //     }
                            // }
                            // else {
                            //     $ictExtSum = "";
                            // }
                        }
                        
                        $c++;
                    ?>
                    <tr>
                    <style>
                        th {text-align: center;}
                        td {text-align: center;}
                    </style> 
                    <!-- num -->
                      <td><?php echo $c; ?></td>
                    <!-- status -->
                      <td>
                        <center>
                            <span style="width: 25px; height: 25px; background-color: <?php echo $circle; ?>; border-radius: 50%; display: inline-block;"></span>
                        </center>
                      </td>
                      <!-- provider code  -->
                      <td>
                        <?php
                            echo $cs_tb['fld_provcode'];
                        ?>
                      </td>
                    <!-- submitting entity -->
                      <td>
                        <a href="main.php?nid=149&sid=1&rid=1&ctrlno=<?php echo $cs_tb['fld_ctrlno']; ?>"><?php echo $cs_tb['company_name']; ?></a>
                      </td>
                    <!-- entity -->
                    <td> <?php echo $cs_tb['fld_type']; ?> </td>
                    <!-- name -->
                    <td> 
                        <?php
                            echo $cs_tb['fld_fname_ar'] . " " . $cs_tb['fld_mname_ar'] . " " . $cs_tb['fld_lname_ar'] . " " . $cs_tb['fld_extname_ar'];
                            echo "<br>";
                            echo $cs_tb['fld_fname_c1'];
                        ?>
                    </td>
                    <!-- position -->
                    <td>
                        <?php 
                            echo $cs_tb['fld_position_ar'];
                        ?>
                    </td>
                    <!-- contact number -->
                    <td>
                        <?php
                            echo $cs_tb['fld_contactno_ar'];
                        ?>
                    </td>
                    <!-- email address -->
                    <td>
                        <?php
                            echo $cs_tb['fld_email_ar'];
                            echo "<br>";
                            echo $cs_tb['fld_email_c1'];
                        ?>
                    </td>
                    <!-- tsp -->
                    <td>
                        <?php
                            echo "n/a";
                        ?>
                    </td>
                    <!-- tickets -->
                    <td>
                        <a href="main.php?nid=149&sid=2&rid=1&ctrlno=<?php echo $cs_tb['fld_ctrlno']; ?>"><?php echo $ticketTotal; ?></a>
                        <!-- <a href="/Github/developing/blank.php">0</a> -->
                    </td>
                    <!-- registration -->
                      <td>
                        <?php 
                            echo $registration_stat;
                            echo "<br>";
                            echo $reg_days_w_color;
                            echo "<br>";
                            echo $reg_start_date;
                        ?>
                      </td>
                    <!-- testing & validation -->
                      <td>
                        <?php  
                            echo $test_val_stat;
                            echo "<br>";
                            if ($test_val_int->days == 0) {
                                echo "";
                            }
                            else {
                                echo $test_val_days_w_color;
                            }
                            echo "<br>";
                            echo $test_val_start_date;
                            //echo "<br>";
                            // echo $echo;
                            // echo "<br>";
                            // echo $gla['fld_userid'];
                            //echo "<br>";
                            // //echo $ict_personnel;
                            //echo $index ."||". $nextAssigned;
                            // //echo $echoAgain;
                            //echo "DIFF: " . $tv_mss_diff;
                        ?>
                      </td>
                    <!-- production -->
                      <td>
                        <?php  
                            echo $prod_stat;
                            echo "<br>";
                            if ($prod_int->days == 0) {
                                echo "";
                            }
                            else {
                                echo $prod_days_w_color;
                            }
                            echo "<br>";
                            echo $prod_start_date;
                        ?>
                      </td>
                    <!-- action needed -->
                    <td> 
                        <?php 
                            if ($crat['fld_corrective_action'] == 1) {
                                $corrAcc = "Follow Up Notice";
                                $btn = "primary";
                            }
                            elseif ($crat['fld_corrective_action'] == 2) {
                                $corrAcc = "Required Attendance in Technical Training Sessions";
                                $btn = "primary";
                            }
                            elseif ($crat['fld_corrective_action'] == 3) {
                                $corrAcc = "Issuance of a Letter of Compliance";
                                $btn = "primary";
                            }
                            elseif ($crat['fld_corrective_action'] == 4) {
                                $corrAcc = "Reporting to Government Regulatory Agencies";
                                $btn = "warning";
                            }
                            elseif ($crat['fld_corrective_action'] == 5) {
                                $corrAcc = "Public Warning for Delinquency";
                                $btn = "warning";
                            }
                            elseif ($crat['fld_corrective_action'] == 6) {
                                $corrAcc = "Imposition of Administrative Sanctions and Penalties";
                                $btn = "danger";
                            }
                            else {
                                $corrAcc = "No Action Yet";
                                $btn = "secondary";
                            }
                        ?>
                            <button type="button" class="btn btn-block btn-<?php echo $btn;?>"><?php echo $corrAcc; ?></button> 
                    </td>
                    <!-- corrective action -->
                      <td>
                        <?php
                            if (empty($crat['fld_corrective_action_remarks'])) {
                                echo "";
                            }
                            elseif (!empty($crat['fld_corrective_action_remarks'])) {
                                echo $crat['fld_corrective_action_remarks'];
                            }

                        ?>
                      </td>
                      <!-- penalties -->
                      <td>
                        <?php                      
                                echo $pen_view;
                                echo "<br>";
                                echo $pen_date;
                        ?>
                      </td>
                      <?php //} ?>
                    </tr>
                <?php
                            
                            }  
                        }
                        //}

                
                ?>
                </tbody>
            </table>
        </div>

    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->