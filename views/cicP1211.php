<?php
$controlno = $_GET['ctrlno'];

$get_details_of_entity = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_provcode, AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_name, AES_DECRYPT(fld_ip, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_ip,fld_type, fld_ts1, fld_status, fld_ts_ipresub, fld_changed_ip_status, fld_changed_ip_timestamp, fld_mnemonics, fld_changed_ip_remarks, fld_mnemonics FROM tbentities WHERE fld_ctrlno = '".$controlno."'");
$gdoe=$get_details_of_entity->fetch_array();

$ipaddr = explode("|", $gdoe['fld_ip']);


?>
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title"><?php echo $gdoe['fld_name']. " - MNEMONICS: <b>".$gdoe['fld_mnemonics']; ?></b></h3>
    </div>
    <div class="card-body">
      <table class="table table-hover table-striped">
        <thead>
          <tr>
            <th>Type</th>
            <th>IP Address</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
            foreach ($ipaddr as $key => $value) {
              $slice = explode("-", $value);
              if ($slice[1] == "o") {
                $line_display = "line-through";
              } else {
                $line_display = "";
              }
          ?>
          <tr>
            <td style="text-decoration: <?php echo $line_display; ?>;">
              <?php
                if ($slice[0] == "a") {
                  echo "Primary IP Address";
                } elseif ($slice[0] == "b") {
                  echo "Secondary IP Address";
                }
              ?>
            </td>
            <td style="text-decoration: <?php echo $line_display; ?>;"><?php echo $slice[4]; ?></td>
            <td>
              <?php
                if ($slice[1] == "i") {
                  echo "<span class='badge bg-info'>ACTIVE</span>";
                } elseif($slice[1] == "o") {
                  echo "<span class='badge bg-warning'>INACTIVE</span>";
                }
              ?>    
            </td>
            <td>
              <?php
              if($slice[1] == "o") {
              } else {
                if($slice[2] == "a"){
              ?>
              <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-success">
                Approved
              </button>
              <div class="modal fade" id="modal-success">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">IP Address - <?php echo $slice[4]; ?></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                      <textarea class="form-control"></textarea>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-success">Submit</button>
                    </div>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
              <!-- /.modal -->
              <?php
                } elseif ($slice[2] == "r") {
              ?>
              <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-danger">
                Rejected
              </button>
              <div class="modal fade" id="modal-danger">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Success Modal</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                      <p>One fine body&hellip;</p>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-success">Save changes</button>
                    </div>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
              <!-- /.modal -->
              <?php
                }
              }
              ?>
            </td>
          </tr>
          <?php
            }
          ?>
        </tbody>
      </table>
      <br>
      <button class="btn btn-success">Save</button>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->