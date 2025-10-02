<?php
  if ($_POST['sbtSaveInquiryCost']) {
    if ($dbh->query("INSERT INTO tbinquirycost (fld_cost, fld_pricingtype, fld_effectivity_date) VALUES (".$_POST['enter_cost'].", ".$_POST['type'].", '".$_POST['effectivty_end_date']."')")) {
      $dbh->query("UPDATE tbinquirycost_temp SET fld_confirmed_by = '".$_SESSION['name']."', fld_confirmed_ts = '".date("Y-m-d H:i:s")."' WHERE fld_id = '".$_POST['costtype_id']."'");
    }
  }
?>
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Validation</h3>
    </div>
    <div class="card-body">
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
                    <form method="post">
                      <input type="hidden" name="costtype_id" value="<?php echo $gic['fld_id']; ?>">
                      <button type="submit" name="sbtSaveInquiryCost" value="1" class="btn btn-warning btn-small">Pending</button>
                    </form>
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