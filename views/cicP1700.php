<?php
  if ($_POST['sbtSaveCost']) {
    if ($dbh->query("INSERT INTO tbinquirycost_temp (fld_cost, fld_pricingtype, fld_effectivity_date, fld_added_by, fld_added_ts) VALUES (".$_POST['enter_cost'].", ".$_POST['type'].", '".$_POST['effectivty_end_date']."', '".$_SESSION['name']."', '".date("Y-m-d H:i:s")."')")) {
      # code...
    }
  }
?>
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Billing Pricing</h3>
    </div>
    <div class="card-body">
      <?php
        // if ($_SESSION['usertype'] == 0 || $_SESSION['usertype'] == 10) {
      ?>
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary">
      <i class="fa fa-plus"></i> Add
      </button>
      <?php
        // }
      ?>

      <div class="modal fade" id="modal-primary">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Insert Cost</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="post">
            <div class="modal-body">
              <div class="form-group">
                <label>Cost</label>
                <input type="number" class="form-control" name="enter_cost" placeholder="Enter Cost">
              </div>
              <div class="form-group">
                <label>Type</label>
                <select class="form-control" name="type">
                  <option value="1">Retail</option>
                  <option value="3">Wholesale</option>
                  <option value="2">Consumer</option>
                </select>
              </div>
              <div class="form-group">
                  <label>Effectivity End-Date:</label>

                  <div class="input-group">
                    <input type="date" class="form-control" name="effectivty_end_date" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask>
                  </div>
                  <!-- /.input group -->
                </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" name="sbtSaveCost" value="1" class="btn btn-success">Save changes</button>
            </div></form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

      <br><br>
      <div class="row">
        <div class="col-6">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th width="5%"><center>#</center></th>
                <th width="8%"><center>Cost</center></th>
                <th width="8%"><center>Type</center></th>
                <th width="8%"><center>Effectivity End-Date</center></th>
                <th width="8%"><center>Added By</center></th>
                <th width="8%"><center>Date Time</center></th>
                <th width="8%"><center>Confirmed by</center></th>
                <th width="8%"><center>Date Time</center></th>
                <th width="8%"><center>Action</center></th>
              </tr>
            </thead>
            <tbody>
              <?php
                $get_inquiry_cost = $dbh->query("SELECT * FROM tbinquirycost_temp WHERE fld_pricingtype > 0 and fld_status = 0");
                while ($gic=$get_inquiry_cost->fetch_array()) {
                  $c++;
              ?>
              <tr>
                <td><center><?php echo $c; ?></center></td>
                <td><center>PHP <?php echo $gic['fld_cost']; ?></center></td>
                <td><center><?php if($gic['fld_pricingtype'] == 1) { echo "Retail"; } elseif($gic['fld_pricingtype'] == 2) { echo "Consumer"; } elseif($gic['fld_pricingtype'] == 3) { echo "Wholesale"; } ?></center></td>
                <td><center><?php echo date("F d, Y", strtotime($gic['fld_effectivity_date'])); ?></center></td>
                <td><center><?php if(!empty($gic['fld_added_by'])) { echo $gic['fld_added_by']; } else { echo "CIC Portal"; } ?></center></td>
                <td><center><?php if(!empty($gic['fld_added_ts'])) { echo date("F d, Y h:ia", strtotime($gic['fld_added_ts'])); } ?></center></td>
                <td><center><?php if(!empty($gic['fld_confirmed_by'])) { echo $gic['fld_confirmed_by']; } ?></center></td>
                <td><center><?php if(!empty($gic['fld_confirmed_ts'])) { echo date("F d, Y h:ia", strtotime($gic['fld_confirmed_ts'])); } ?></center></td>
                <td>
                  <center>
                    <?php
                      if ($gic['fld_status'] == 0) {
                    ?>
                    <p>PENDING</p>
                    <?php
                      } else {
                    ?>
                    <p>SAVED</p>
                    <?php
                      }
                    ?>
                  </center>
                </td>
              </tr>
              <?php
                }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->