<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
//echo getcwd();

      $ctrl_no = $_GET['ctrlno'];
      $get_name_of_company_selected = $dbh4->query("SELECT AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS company_name, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_provcode FROM `tbentities` WHERE fld_ctrlno='".$ctrl_no."'");
      $com_nm = $get_name_of_company_selected->fetch_array();

      //print_r($ctrl_no);

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

      $mon = Date('m');
      //echo 'https://creditinfoph.freshdesk.com/api/v2/search/tickets?page='.$page.'&query="(group_id:33000215508)%20AND%20(created_by:<%272024-'.$mon.'-01%27%20AND%20created_by:>%272024-'.$mon.'-31%27)"';

?>


<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-body">
      <div class="row">
      <div class="col-md-3">
        <!-- Widget: user widget style 1 -->
        <div class="card card-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <!-- <div class="widget-user-header"> -->
                <!-- <div class="widget-user-image"> -->
                    <!-- <img class="img-circle" src="images/CIClogo3.png" alt="User Avatar" height="100"> -->
                <!--</div> -->
            <!-- </div> -->
            <div class="card-footer">
                <h3 class="widget-user-username"><?php echo $com_nm['company_name']; ?></h3>
                <h5 class="widget-user-desc"><?php echo $com_nm['fld_provcode']; ?></h5>
            </div>
        </div>
            <!-- /.widget-user -->
      </div>
      <!-- new line -->
      <div class="col-md-9">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Latest Added Tickets</h3>

                <div class="card-tools">
                  <div class="input-group input-group-sm" style="width: 150px;">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Ticket # </th>
                      <th>Subject</th>
                      <th>Status</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      //echo "SELECT * FROM $dbTickets WHERE fld_ctrlno = '".$ctrl_no."' AND fld_status IN (2,3,9)";

                      $fetchTickets = $dbh4->query("SELECT * FROM tbict_freshdesk WHERE fld_ctrlno = '".$ctrl_no."' AND fld_status IN (2,3,9) $group");
                      while ($fets = $fetchTickets->fetch_array()) {
                    ?>
                    <tr>
                      <td><?php echo $fets['fld_ticket_id']; ?></td>
                      <td>
                        <!-- <a href="/Github/developing/views/email.php">Ticket 1</a> -->
                        <a href="main.php?nid=149&sid=2&rid=2&ctrlno=<?php echo $fets['fld_ticket_id']; ?>"><?php if (empty($fets['fld_subject'])) {echo "(this ticket has no subject title)";} else {echo $fets['fld_subject'];} ?></a>
                      </td>
                      <td>
                        <?php
                          if ($fets['fld_status'] == 2) {
                            echo "Open";
                          }
                          elseif ($fets['fld_status'] == 3) {
                            echo "Pending";
                          }
                          elseif ($fets['fld_status'] == 9) {
                            echo "In Progress";
                          }
                        ?>
                      </td>
                      <td><?php echo $fets['fld_created_at']; ?></td>
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
          </div>
      <!-- new line -->
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</section>
<!-- /.content -->