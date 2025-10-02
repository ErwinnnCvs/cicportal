<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

//$controlno_se = $_POST['controlno_close'];

$currDate = Date('Y-m-d H:i:s');

$companyselected[$_POST['company_select']] = " selected";

//echo $fchTkts['fld_ticket_id'];

$session = $dbh->query("SELECT fld_stage FROM tbictpersonnel WHERE fld_userid ='".$_SESSION['user_id']."'");
$sesh = $session->fetch_array();

if ($sesh['fld_stage'] == 1) {
  $group = "AND fld_group_id = 33000148658";
}
elseif ($sesh['fld_stage'] == 2) {
  $group = "AND fld_group_id = 33000215509";
}
elseif ($sesh['fld_stage'] == 3) {
  $group = "AND fld_group_id = 33000212005";
}
elseif ($sesh['fld_stage'] == 4) {
  $group = "AND fld_group_id = 33000215508";
}

$fetchTickets = $dbh4->query("SELECT * FROM tbict_freshdesk WHERE fld_ticket_id <> '' AND fld_ctrlno IS NOT NULL AND fld_status IN (2, 3, 9) $group");

$countOpenTicket = $dbh4->query("SELECT fld_status FROM tbict_freshdesk WHERE fld_status = 2 $group");
$open = mysqli_num_rows($countOpenTicket);

$countPendingTicket = $dbh4->query("SELECT fld_status FROM tbict_freshdesk WHERE fld_status = 3 $group");
$pending = mysqli_num_rows($countPendingTicket);

$countResolvedTicket = $dbh4->query("SELECT fld_status FROM tbict_freshdesk WHERE fld_status = 4 $group");
$resolved = mysqli_num_rows($countResolvedTicket);

$countClosedTicket = $dbh4->query("SELECT fld_status FROM tbict_freshdesk WHERE fld_status = 5 $group");
$closed = mysqli_num_rows($countClosedTicket);

$countInProgressTicket = $dbh4->query("SELECT fld_status FROM tbict_freshdesk WHERE fld_status = 9 $group");
$inProgress = mysqli_num_rows($countInProgressTicket);

$countOverdueTicket = $dbh4->query("SELECT * FROM tbict_freshdesk WHERE fld_status IN (2, 3, 9) AND fld_due_date < '".$currDate."' $group");
$overdue = mysqli_num_rows($countOverdueTicket);

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

$get_name_of_company_selected = $dbh4->query("SELECT AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS company_name, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_provcode FROM `tbentities` WHERE fld_ctrlno='".$fchTkts['fld_ctrlno']."'");
$com_nm = $get_name_of_company_selected->fetch_array();

//echo $countOpenTicket
 //echo $_POST['company_select']; - shows ctrlno 

//echo "UPDATE $database SET fld_ctrlno = '".$_POST['company_select']."', fld_assigned_ts = '".$currDate."', fld_assigned_ts = '".$_SESSION['user_id']."' WHERE fld_ticket_id = '".$_POST['ticket_no']."' AND fld_ctrlno IS NULL";

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
    <div class="card-header">
      <div class="input-group-prepend">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          Assigned Tickets
        </button>
          <ul class="dropdown-menu">
            <li class="dropdown-item"><a href="main.php?nid=150&sid=0&rid=0">Unassigned Tickets</a></li>
            <li class="dropdown-item"><a href="main.php?nid=150&sid=1&rid=1">Assigned</a></li>
            <li class="dropdown-item"><a href="main.php?nid=150&sid=1&rid=3">Tickets Transferred</a></li>
            <!-- <li class="dropdown-item"><a href="main.php?nid=3&sid=0&rid=0&fstatus=3">Completed</a></li> -->
          </ul>
      </div>
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
                    if ($fchTkts['fld_status'] == 2) {
                      echo "Open";
                    }
                    elseif ($fchTkts['fld_status'] == 3) {
                      echo "Pending";
                    }
                    elseif ($fchTkts['fld_status'] == 9) {
                      echo "In Progress";
                    }
                  
                  ?>
                </td>
                <td><?php echo $fchTkts['fld_created_at']; ?></td>
                <td><?php echo $fchTkts['fld_date_insert']; ?></td>
                <td>
                  <?php 

                    $get_name_of_company_selected = $dbh4->query("SELECT AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS company_name, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_provcode FROM `tbentities` WHERE fld_ctrlno='".$fchTkts['fld_ctrlno']."'");
                    $com_nm = $get_name_of_company_selected->fetch_array();
                      $ticket_no = $fchTkts['fld_ticket_id'];
                      //echo "<br>";
                   // foreach ($fchTkts as $key => $value) {
                      //if ($fchTkts['fld_ctrlno'] == NULL) {
                        //echo $fchTkts['fld_ctrlno'];
                        echo $com_nm['company_name'];
                  
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