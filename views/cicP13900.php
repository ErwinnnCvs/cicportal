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

if ($_POST['sbtFetchTicket']) {
  $ticket = $_POST['ticket_number'];
  $provcode = $_POST['assignse'];


  if ($dbh->query("UPDATE tbfreshdesk SET fld_provcode = '".$provcode."' WHERE fld_ticket_id = ".$ticket)) {
    $msg = "Successfully linked ".$provcode." to Ticket: ".$ticket;
    $msgclr = "success";
  } else {
    $msg = "Error linking ".$provcode." to Ticket: ".$ticket;
    $msgclr = "success";
  }

}

$get_all_overdue_count = $dbh->query("SELECT COUNT(*) as cnt_overdue FROM tbfreshdesk WHERE DATE_FORMAT(fld_due_by, '%Y-%m-%d') < '".date('Y-m-d')."' and (fld_status = 2 or fld_status = 3 or fld_status = 9)".$querytype);
$gaoc=$get_all_overdue_count->fetch_array();

$get_all_closed_count = $dbh->query("SELECT COUNT(*) as cnt_closed FROM tbfreshdesk WHERE fld_status = 5".$querytype);
$gacc=$get_all_closed_count->fetch_array();

$get_all_open_count = $dbh->query("SELECT COUNT(*) as cnt_open FROM tbfreshdesk WHERE fld_status = 2 ".$querytype);
$gaopc=$get_all_open_count->fetch_array();

$get_all_pending_count = $dbh->query("SELECT COUNT(*) as cnt_pending FROM tbfreshdesk WHERE fld_status = 3".$querytype);
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

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-times"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Overdue</span>
                <span class="info-box-number"><?php echo number_format($gaoc['cnt_overdue']); ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Closed</span>
                <span class="info-box-number">
                  <?php echo number_format($gacc['cnt_closed']); ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          

          <!-- fix for small devices only -->
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
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Pending</span>
                <span class="info-box-number">
                  <?php echo number_format($gapc['cnt_pending']); ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <div class="input-group-prepend">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            Unassigned Tickets
          </button>
          <ul class="dropdown-menu">
            <a href="main.php?nid=139&sid=0&rid=0"><li class="dropdown-item">Unassigned Tickets</li></a>
            <a href="main.php?nid=139&sid=2&rid=0"><li class="dropdown-item">Linked Tickets</li></a>
          </ul>
        </div>
    </div>
    <div class="card-body">
      
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
              <th>Link to SE</th>
              <!-- <th>Personnel</th> -->
            </tr>
          </thead>
          <tbody>
            <?php
              $get_all_freshdesk_tickets = $dbh->query("SELECT * FROM tbfreshdesk WHERE fld_ticket_id <> '' AND fld_provcode IS NULL ORDER BY fld_created_at DESC");
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
              <td><a href="main.php?nid=139&sid=1&rid=0&ticket=<?php echo $gaft['fld_ticket_id']; ?>" target="_blank"><?php echo $gaft['fld_subject']; ?></a></td>
              <td>
                <?php echo $cmstatus[$gaft['fld_status']]; ?>
                  
                </td>
              <td><?php echo $cmpriority[$gaft['fld_priority']]; ?></td>
              <td><?php echo $gaft['fld_created_at']; ?></td>
              <!-- <td>
                <a href="https://creditinfoph.freshdesk.com/helpdesk/tickets/<?php echo $gaft['fld_ticket_id']; ?>" target="_blank">https://creditinfoph.freshdesk.com/helpdesk/tickets/<?php echo $gaft['fld_ticket_id']; ?></a>
              </td> -->
              <td><?php echo $gaft['fld_ins_ts']; ?></td>
              <td>
                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-default<?php echo $gaft['fld_ticket_id']; ?>">
                LINK SE
                </button>

                <div class="modal fade" id="modal-default<?php echo $gaft['fld_ticket_id']; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Fetch Ticket</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                      </div>
                      <form method="POST">  
                      <div class="modal-body">
                          <div class="form-group">
                            <label>Input Ticket Number</label>
                            <input type="text" class="form-control" name="ticket_number" value="<?php echo $gaft['fld_ticket_id']; ?>">
                          </div>
                          <div class="form-group">
                            <label>Select SE</label>
                            <select class="form-control select2" name="assignse" style="width: 100%;" required>
                              <option disabled selected>----SELECT----</option>
                            <?php
                              $get_all_seps_sub=$dbh4->query("SELECT a.fld_ctrlno, a.fld_type, AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) as fld_provcode, AES_DECRYPT(a.fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_name FROM tbentities a WHERE AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) <> ''");
                              while ($gass=$get_all_seps_sub->fetch_array()) {
                                echo "<option value='".$gass['fld_provcode']."'>".$gass['fld_provcode']. " " .$gass['fld_name']."</option>";
                              }
                            ?>
                            </select>
                          </div>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" value="1" name="sbtFetchTicket" class="btn btn-primary">Save</button>
                      </div>
                      </form>
                    </div>

                  </div>

                </div>
              </td>
<!--               <td><?php echo $gcicp['fld_name']; ?></td> -->
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