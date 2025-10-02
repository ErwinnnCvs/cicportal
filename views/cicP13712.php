<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);


if ($_SESSION['user_id'] == 12) {
  $type = 1;//For Technical
}elseif ($_SESSION['user_id'] == 143) {
  $type = 2;// FOr Legal
}

if ($_SESSION['user_id'] == 76) {
  $querytype = "";
  $type = 1;
} else {
  $querytype = " and fld_type = ".$type;
}


$get_all_overdue_count = $dbh->query("SELECT COUNT(*) as cnt_overdue FROM tbfreshdesk WHERE fld_provcode = '".$_GET['provcode']."' AND DATE_FORMAT(fld_due_by, '%Y-%m-%d') < '".date('Y-m-d')."' and (fld_status = 2 or fld_status = 3 or fld_status = 9)".$querytype);
$gaoc=$get_all_overdue_count->fetch_array();

$get_all_closed_count = $dbh->query("SELECT COUNT(*) as cnt_closed FROM tbfreshdesk WHERE fld_provcode = '".$_GET['provcode']."' AND fld_status = 5".$querytype);
$gacc=$get_all_closed_count->fetch_array();

$get_all_open_count = $dbh->query("SELECT COUNT(*) as cnt_open FROM tbfreshdesk WHERE fld_provcode = '".$_GET['provcode']."' AND fld_status = 2 ".$querytype);
$gaopc=$get_all_open_count->fetch_array();

$get_all_pending_count = $dbh->query("SELECT COUNT(*) as cnt_pending FROM tbfreshdesk WHERE fld_provcode = '".$_GET['provcode']."' AND fld_status = 3".$querytype);
$gapc=$get_all_pending_count->fetch_array();

?>
<!-- Main content -->
<section class="content">
  <?php
    if ($msg) {
  ?>
  <div class="alert alert-<?php echo $msgclr; ?> alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h5><i class="icon fas fa-check"></i> Alert!</h5>
    <?php echo $msg; ?>
  </div>
  <?php
    }
  ?>
  <div class="row">

          <!-- <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-times"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Overdue</span>
                <span class="info-box-number"><?php echo number_format($gaoc['cnt_overdue']); ?></span>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Closed</span>
                <span class="info-box-number">
                  <?php echo number_format($gacc['cnt_closed']); ?>
                </span>
              </div>
            </div>
          </div>
          
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-circle"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Open</span>
                <span class="info-box-number">
                  <?php echo number_format($gaopc['cnt_open']); ?>
                </span>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Pending</span>
                <span class="info-box-number">
                  <?php echo number_format($gapc['cnt_pending']); ?>
                </span>
              </div>
            </div>
          </div>
        </div> -->
  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      Tickets of <?php echo $_GET['provcode']; ?>
    </div>
    <div class="card-body">
      <input type="hidden" name="getProvCode" id="getProvCode" value="<?php echo $_GET['provcode']; ?>">
      <table class="table table-bordered" id="tickets_table">
          <thead> 
            <tr>
              <th>Ticket #</th>
              <th>Subject</th>
              <th>Status</th>
              <th>Priority</th>
              <th>Ticket Date</th>
              <!-- <th>Freshdesk URL</th> -->
              <th>Date Insert</th>
              <th>Submitting Entity</th>
              <th>Personnel</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $get_all_freshdesk_tickets = $dbh->query("SELECT * FROM tbfreshdesk WHERE fld_ticket_id <> ''".$querytype." AND fld_provcode = '".$_GET['provcode']."' ORDER BY fld_ins_ts ASC");
              while ($gaft=$get_all_freshdesk_tickets->fetch_array()) {

                $get_entity_name = $dbh4->query("SELECT AES_DECRYPT(a.fld_name, MD5(CONCAT(a.fld_ctrlno, 'RA3019'))) as name, b.fld_assign FROM tbentities a JOIN tbassign b ON AES_DECRYPT(a.fld_provcode, MD5(CONCAT(a.fld_ctrlno, 'RA3019'))) = b.fld_provcode WHERE AES_DECRYPT(a.fld_provcode, MD5(CONCAT(a.fld_ctrlno, 'RA3019'))) = '".$gaft['fld_provcode']."' and b.fld_active = 1 and b.fld_type = ".$type);
                $gen=$get_entity_name->fetch_array();

                // echo "SELECT fld_name FROM tbcicusers WHERE pkUserId = ".$gen['fld_assign']."<br>";
                $get_cic_personnel = $dbh->query("SELECT fld_name FROM tbcicusers WHERE pkUserId = ".$gen['fld_assign']);

                if (mysqli_num_rows($get_cic_personnel) > 0) {
                  $gcicp=$get_cic_personnel->fetch_array();
                } else {

                }
            ?>
            <tr>
              <td>
                
                <a href="https://creditinfoph.freshdesk.com/helpdesk/tickets/<?php echo $gaft['fld_ticket_id']; ?>" target="_blank"><?php echo $gaft['fld_ticket_id']; ?></a>  
              </td>
              <td><a href="main.php?nid=139&sid=1&rid=0&ticket=<?php echo $gaft['fld_ticket_id']; ?>"><?php echo $gaft['fld_subject']; ?></a></td>
              <td>
                <?php echo $cmstatus[$gaft['fld_status']]; ?>
                  
                </td>
              <td><?php echo $cmpriority[$gaft['fld_priority']]; ?></td>
              <td><?php echo $gaft['fld_created_at']; ?></td>
              <!-- <td>
                <a href="https://creditinfoph.freshdesk.com/helpdesk/tickets/<?php echo $gaft['fld_ticket_id']; ?>" target="_blank">https://creditinfoph.freshdesk.com/helpdesk/tickets/<?php echo $gaft['fld_ticket_id']; ?></a>
              </td> -->
              <td><?php echo $gaft['fld_ins_ts']; ?></td>
              <td><?php echo $gen['name']; ?></td>
              <td><?php echo $gcicp['fld_name']; ?></td>
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