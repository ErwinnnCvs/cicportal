<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

//$controlno_se = $_POST['controlno_close'];

$currDate = Date('Y-m-d H:i:s');

$companyselected[$_POST['company_select']] = " selected";
$groupselected[$_POST['group_select']] = " selected";

//echo $fchTkts['fld_ticket_id'];
//echo $_POST['group_select'];

// see if ict personnel is equals to user_id
$session = $dbh->query("SELECT fld_stage FROM tbictpersonnel WHERE fld_userid ='".$_SESSION['user_id']."'");
$sesh = $session->fetch_array();

// if personnel is stage 1
if ($sesh['fld_stage'] == 1) {
  $group = "AND fld_group_id = 33000148658";
}
// if personnel is stage 2
elseif ($sesh['fld_stage'] == 2 && ($_SESSION['user_id'] == 149 || $_SESSION['user_id'] == 175 || $_SESSION['user_id'] == 162)) { //change userid to 149 (rayson dc)
  $group = "AND fld_group_id = 33000215509";
}
// if personnel is stage 3
elseif ($sesh['fld_stage'] == 3) {
  $group = "AND fld_group_id = 33000212005";
}
// if personnel is LOC
elseif ($sesh['fld_stage'] == 4) {
  $group = "AND fld_group_id = 33000215508";
}
else {
  $group = "";
  $msg = "403 Page not Found";
  $msgclr = "warning";
}
//$groupOptions = array("1" => "Data Submission (Stage 1)", "2" => "Initial Compliance UAT (Stage 2)", "3" => "SE Prod Updates (Stage 3)", "4" => "Initial Compliance LOC");

// getting the timestamp of the last ticket fetched
$getLastFetchedTimestamp = $dbh4->query("SELECT fld_last_fetched_ts FROM `tbict_freshdesk` WHERE fld_last_fetched_ts IS NOT NULL $group ORDER BY fld_last_fetched_ts DESC LIMIT 1");
$lastFetched = $getLastFetchedTimestamp->fetch_array();

// fetching tickets
$fetchTickets = $dbh4->query("SELECT * FROM tbict_freshdesk WHERE fld_ticket_id <> '' AND fld_ctrlno IS NULL AND fld_status IN (2, 3, 9) $group ORDER BY fld_created_at DESC");

// counting of open tickets
$countOpenTicket = $dbh4->query("SELECT fld_status FROM tbict_freshdesk WHERE fld_status = 2 $group");
$open = mysqli_num_rows($countOpenTicket);

// counting of pending tickets
$countPendingTicket = $dbh4->query("SELECT fld_status FROM tbict_freshdesk WHERE fld_status = 3 $group");
$pending = mysqli_num_rows($countPendingTicket);

// counting of resolved tickets
$countResolvedTicket = $dbh4->query("SELECT fld_status FROM tbict_freshdesk WHERE fld_status = 4 $group");
$resolved = mysqli_num_rows($countResolvedTicket);

// counting of closed tickets
$countClosedTicket = $dbh4->query("SELECT fld_status FROM tbict_freshdesk WHERE fld_status = 5 $group");
$closed = mysqli_num_rows($countClosedTicket);

// counting of in progress tickets
$countInProgressTicket = $dbh4->query("SELECT fld_status FROM tbict_freshdesk WHERE fld_status = 9 $group");
$inProgress = mysqli_num_rows($countInProgressTicket);

// counting of overdue tickets
$countOverdueTicket = $dbh4->query("SELECT * FROM tbict_freshdesk WHERE fld_status IN (2, 3, 9) AND fld_due_date < '".$currDate."' $group");
$overdue = mysqli_num_rows($countOverdueTicket);

// groups to transfer tickets
if ($_POST['group_select'] == 1) {
  $group_number = "33000148658";
}
elseif ($_POST['group_select'] == 2) {
  $group_number = "33000215509";
}
elseif ($_POST['group_select'] == 3) {
  $group_number = "33000212005";
}
elseif ($_POST['group_select'] == 4) {
  $group_number = "33000215508";
}

// assign to SE button
if ($_POST['sbtAssignSE']) {
  if ($dbh4->query("UPDATE tbict_freshdesk SET fld_ctrlno = '".$_POST['company_select']."', fld_assigned_ts = '".$currDate."', fld_assigned_by = '".$_SESSION['user_id']."' WHERE fld_ticket_id = '".$_POST['ticket_no']."' AND fld_ctrlno IS NULL")) {
    $msg = "Successfully Assigned SE";
    $msgclr = "success";
  }

  else {
    $msg = "Error Assigning SE";
    $msgclr = "danger";
  }
}

// transfer tickets button
if ($_POST['sbtTransfer']) {
  //echo "ihgo buttoneu is pressue";
  if ($dbh4->query("UPDATE tbict_freshdesk SET fld_group_id = $group_number, fld_transfer_ts = '".$currDate."', fld_transfer_by = '".$_SESSION['user_id']."' WHERE fld_ticket_id = '".$_POST['ticket_no2']."' AND fld_ctrlno IS NULL")) {
    $msg = "Successfully Transferred Ticket";
    $msgclr = "success";
  }
  else {
    $msg = "Error Transferring Ticket";
    $msgclr = "danger";
  }
}

// fetch tickets button
if ($_POST['fetchBtn']) {
  //if ($dbh4->query("UPDATE tbict_freshdesk SET fld_last_fetched_ts = '".$currDate."', fld_last_fetched_by = '".$_SESSION['user_id']."' WHERE fld_last_fetched_ts IS NULL")) {
    if ($sesh['fld_stage'] == 1) {
      include 'freshdesk/icmt/fetch_tickets_ict_ds.php';
      $fetchDate = $currDate;
      $msg = "Fetched New Tickets (Click Refresh Button to View New Tickets)";
      $msgclr = "success";
    }
    elseif ($sesh['fld_stage'] == 2) {
      include 'freshdesk/icmt/fetch_tickets_ict_uat.php';
      $fetchDate = $currDate;
      $msg = "Fetched New Tickets (Click Refresh Button to View New Tickets)";
      $msgclr = "success";
    }
    elseif ($sesh['fld_stage'] == 3) {
      include 'freshdesk/icmt/fetch_tickets_ict_sep.php';
      $fetchDate = $currDate;
      $msg = "Fetched Tickets (Click Refresh Button to View New Tickets)";
      $msgclr = "success";
    }
    elseif ($sesh['fld_stage'] == 4) {
      include 'freshdesk/icmt/fetch_tickets_ict_loc.php';
      $fetchDate = $currDate;
      $msg = "Fetched Tickets (Click Refresh Button to View New Tickets)";
      $msgclr = "success";
    }
    else {
      $msg = "Error Fetching Ticket";
      $msgclr = "danger";
    }

    include 'freshdesk/icmt/fetch_conversations_and_save.php';
}

?>


<!-- Main content -->
<section class="content">
<div class="container-fluid">
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
    <div class="col-sm">
      <div class="info-box">
        <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Overdue</span>
          <span class="info-box-number"><?php echo $overdue;  ?></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-sm">
      <div class="info-box">
        <span class="info-box-icon bg-primary"><i class="nav-icon fas fa-tachometer-alt"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">In Progress</span>
          <span class="info-box-number"><?php echo $inProgress; ?></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-sm">
      <div class="info-box">
        <span class="info-box-icon bg-secondary"><i class="fas fa-cog"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Closed</span>
            <span class="info-box-number"><?php echo $closed; ?></span>
          </div>
          <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-sm">
      <div class="info-box">
        <span class="info-box-icon bg-success"><i class="far fa-copy"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Open</span>
            <span class="info-box-number"><?php echo $open; ?></span>
          </div>
          <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-sm">
      <div class="info-box">
        <span class="info-box-icon bg-warning"><i class="far fa-bookmark"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Pending</span>
            <span class="info-box-number"><?php echo $pending; ?></span>
          </div>
          <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-sm">
      <div class="info-box">
        <span class="info-box-icon bg-purple"><i class="icon fas fa-check"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Resolved</span>
          <span class="info-box-number"><?php echo $resolved;?></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
  </div>

  <!-- Default box -->
  <div class="card">
    <!-- card header start -->
    <div class="card-header"> 
      <div class="row">
        <div class="input-group-prepend">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            Unassigned Tickets
          </button>
            <ul class="dropdown-menu">
              <li class="dropdown-item"><a href="main.php?nid=150&sid=0&rid=0">Unassigned Tickets</a></li>
              <li class="dropdown-item"><a href="main.php?nid=150&sid=1&rid=1">Assigned</a></li>
              <li class="dropdown-item"><a href="main.php?nid=150&sid=1&rid=3">Tickets Transferred</a></li>
              <!-- <li class="dropdown-item"><a href="main.php?nid=3&sid=0&rid=0&fstatus=3">Completed</a></li> -->
            </ul>
        </div>
        <!-- <button type="button" name="refreshBtn" style="float:right;height:50px;width:150px" font-size="16px" class="btn btn-primary btn-block">Fetch Tickets</button> -->
      </div>
      <form method="post">
        <div class="row">
          <div class="col-md-1 offset-sm-5">
            <button type="submit" value="1" name="refreshBtn" style="height:50px;width:150px" font-size="16px" class="btn btn-block btn-info" onclick="window.location.reload();">Refresh</button>
          </div>
          <div class="col-sm-1">
            <button type="submit" value="1" name="fetchBtn" style="height:50px;width:150px" font-size="16px" class="btn btn-block btn-primary">Fetch Tickets</button>
          </div>
          <div class="col-xs-1">
            <p><small>&ensp;last fetched: <br>&ensp;<?php echo $lastFetched['fld_last_fetched_ts']; ?></small></p>
          </div>
        </div>
  </form>
      <!-- <div class="row">
        <div class="col-md-6 offset-md-11">
          <p><small>&ensp;last fetched: <br>&ensp;<?php //echo $fetchDate; ?></small></p>
        </div>
      </div> -->
      <!-- card header end -->
    </div>
    <div class="card-body">
      <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-striped table-hovered" id="tbAssignSE">
            <thead>
                <tr>
                    <style>
                        /* th {text-align: center;}
                        td {text-align: center;} */
                    </style>
                        <th>Ticket #</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Ticket Date</th>
                        <th>Date Insert</th>
                        <th>Link to SE</th>
                        <th>Transfer Ticket</th>
                </tr>
            </thead>
            <tbody>
              <?php
                while ($fchTkts = $fetchTickets->fetch_array()) {

                  //echo "UPDATE tbict_freshdesk_ict_ds SET fld_ctrlno = '".$_POST['company_select']."', fld_assigned_ts = '".$currDate."', fld_assigned_ts = '".$_SESSION['user_id']."' WHERE fld_ticket_id = '".$_POST['ticket_no']."' AND fld_ctrlno IS NULL";
              ?>
              <tr>
                <td><?php echo $fchTkts['fld_ticket_id']; ?></td>
                <td>
                  <a href="main.php?nid=150&sid=1&rid=2&ctrlno=<?php echo $fchTkts['fld_ticket_id']; ?>"><?php if (empty($fchTkts['fld_subject'])) {echo "(this ticket has no subject title)";} else {echo $fchTkts['fld_subject'];} ?></a>
                </td>
                <td>
                  <?php

                    if (!$ovrtkt = $countOverdueTicket->fetch_array()) {
                      echo "<font color=RED>Overdue</font>";
                    }
                    else {
                      if ($fchTkts['fld_status'] == 2) {
                        echo "Open";
                      }
                      elseif ($fchTkts['fld_status'] == 3) {
                        echo "Pending";
                      }
                      elseif ($fchTkts['fld_status'] == 9) {
                        echo "In Progress";
                      }
                    }
                  
                  ?>
                </td>
                <td><?php echo $fchTkts['fld_created_at']; ?></td>
                <td><?php echo $fchTkts['fld_date_insert']; ?></td>
                <td>
                  <?php 
                      $ticket_no = $fchTkts['fld_ticket_id'];
                      //echo "<br>";
                   // foreach ($fchTkts as $key => $value) {
                      //if ($fchTkts['fld_ctrlno'] == NULL) {
                  
                  ?>
                  <div class="center">
                  <button type="button" name="modal_test" style="height:50px;width:150px" font-size="16px" class="btn btn-success btn-block" data-toggle="modal" data-target="#modal-success<?php echo $ticket_no; ?>">Assign to SE</button>
                  <div class="modal fade" data-keyboard="false" data-backdrop="static" id="modal-success<?php echo $ticket_no; ?>">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Fetch Ticket</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form method="post" enctype="multipart/form-data">
                          <div class="modal-body">
                            <!-- <input type="hidden" name="controlno_close" value="<?php //echo (htmlspecialchars($fchTkts['fld_ticket_id'])); ?>"> -->
                              <div class="form-group">
                                <label>Input Ticket Number</label>
                                <input type="text" name="ticket_no" class="form-control" value="<?php echo $ticket_no; ?>" readonly>
                              </div>

                            <!-- select -->
                            <div class="form-group">
                              <label>Select SE</label>
                                <select class="form-control select2" name="company_select" style="width: 100%;">
                                  <option selected="selected">---SELECT--</option>
                                  <?php
                                    $get_all_registered_entities = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provcode FROM tbentities WHERE fld_registration_type = 1 OR (fld_re_ts IS NOT NULL AND fld_registration_type = 4)");
                                    while ($gare=$get_all_registered_entities->fetch_array()) {
                                      echo "<option value='".$gare['fld_ctrlno']."'".$companyselected[$gare['fld_ctrlno']].">".$gare['provcode']. " - " .$gare['name']."</option>";
                                    }
                                  ?>
                                </select>
                            </div>
                          </div>
                          <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" name="sbtAssignSE" value="1">Save</button>
                          </div>
                        </form>
                      </div>
                      <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                  </div>
                  <!-- /.modal -->
                  </div>
                  <?php
                     // }
                  ?>
                </td>
                <td>
                  <?php 
                      $ticket_no2 = $fchTkts['fld_ticket_id'];
                      //echo "<br>";
                   // foreach ($fchTkts as $key => $value) {
                      //if ($fchTkts['fld_ctrlno'] == NULL) {
                  
                  ?>
                  <button type="button" name="modal_test2" style="height:50px;width:150px" font-size="16px" class="btn btn-warning btn-block" data-toggle="modal" data-target="#modal-success2<?php echo $ticket_no2; ?>">Transfer Ticket</button>
                  <div class="modal fade" data-keyboard="false" data-backdrop="static" id="modal-success2<?php echo $ticket_no2; ?>">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Fetch Ticket</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form method="post" enctype="multipart/form-data">
                          <div class="modal-body">
                            <!-- <input type="hidden" name="controlno_close" value="<?php //echo (htmlspecialchars($fchTkts['fld_ticket_id'])); ?>"> -->
                              <div class="form-group">
                                <label>Input Ticket Number</label>
                                <input type="text" name="ticket_no2" class="form-control" value="<?php echo $ticket_no2; ?>" readonly>
                              </div>

                            <!-- select -->
                            <div class="form-group">
                              <label>Select Group</label>
                                <select class="form-control select2" name="group_select" style="width: 100%;">
                                  <option selected="" disabled="">---SELECT--</option>
                                  <option value="1">Data Submission (Stage 1)</option>
                                  <option value="2">Initial Compliance UAT (Stage 2)</option>
                                  <option value="3">SE Prod Updates (Stage 3)</option>
                                  <option value="4">Initial Compliance LOC</option>
                                  <?php
                                    // $get_all_registered_entities = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provcode FROM tbentities WHERE fld_registration_type = 1 OR (fld_re_ts IS NOT NULL AND fld_registration_type = 4)");
                                    // while ($gare=$get_all_registered_entities->fetch_array()) {
                                    //   echo "<option value='".$gare['fld_ctrlno']."'".$companyselected[$gare['fld_ctrlno']].">".$gare['provcode']. " - " .$gare['name']."</option>";
                                    // }
                                    // foreach ($groupOptions as $key => $value) {
                                    //   echo "option value='".$key."'".$groupselected[$key].">".$value."</option>";
                                    // }
                                  ?>
                                </select>
                            </div>
                          </div>
                          <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" name="sbtTransfer" value="1">Save</button>
                          </div>
                        </form>
                      </div>
                      <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                  </div>
                  <!-- /.modal -->
                  <?php
                      }
                  ?>
                </td>
              </tr>
              <?php
                   // }
                  //}
              ?>
            </tbody>       
    
        </table>  
      </div>  
    </div>
  <!-- /.card -->


</section>
<!-- /.content -->