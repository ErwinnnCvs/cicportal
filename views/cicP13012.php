<?php
if(!$_POST['transType']){
  $_POST['transType'] = "all";
}
?>
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <div class="input-group-prepend">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            Filed Transmittals
          </button>
          <ul class="dropdown-menu">
          <li class="dropdown-item"><a href="main.php?nid=130&sid=0&rid=0">All Tickets Received</a></li>
            <li class="dropdown-item"><a href="main.php?nid=130&sid=1&rid=1">Pending Transmittals</a></li>
            <li class="dropdown-item"><a href="main.php?nid=130&sid=1&rid=2">Filed Transmittals</a></li>
          </ul>
        </div>
    </div>
    <div class="card-body">
      <form method="POST">
        <div class="row">
          <div class="col-lg-3">
              <div class="form-group">
                  <label class="col-form-label">Filter Submission Type</label>
            
                  <select class="custom-select transType" name="transType" id="transType" onchange="submit()"  value="<?php echo $_POST['transType']?>">
                      <option value="all" selected=""  <?php if($_POST['transType'] == "all"){echo "selected='selected'";}  ?>>All</option>
                      <option value="1" <?php if($_POST['transType'] == 1){echo "selected='selected'";}  ?>>Regular Submission</option>
                      <option value="5" <?php if($_POST['transType'] == 5){echo "selected='selected'";} ?>>Extended Regular Submission</option>
                      <option value="6" <?php if($_POST['transType'] == 6){echo "selected='selected'";} ?>>Special Submission - Late Submission</option>
                      <option value="2" <?php if($_POST['transType'] == 2){echo "selected='selected'";} ?>>Special Submission - Correction File</option>
                      <option value="3" <?php if($_POST['transType'] == 3){echo "selected='selected'";} ?>>Special Submission - Dispute</option>
                      <option value="4" <?php if($_POST['transType'] == 4){echo "selected='selected'";} ?>>Special Submission - Historical Data</option>
                      
                  </select>
              </div>
          </div>
        </div>
      </form>
        
       
       <br>
          <table class="table table-bordered table-hover table-sm" id="filedTransmittals">
            <thead>
              <tr>
              <th>#</th>
              <th>Provider Code</th>
              <th>Company</th>
              <th>Filename</th>
              <th>Subject</th>
              <th>Contracts</th>
              <th>Date Covered</th>
              <th>Transmittal Type</th>
              <th>Date Filed</th>
              </tr>
            </thead>
            <tbody>
              <?php

                if ($_POST['transType'] && $_POST['transType'] != "all") {
                  $where = " WHERE fld_trans_type = ".$_POST['transType']; 
                } else {
                  $where = '';
                }
                $c2 = 1;
                $get_filed_transmittals = $dbh4->query("SELECT * FROM tbtransmittal".$where." ORDER BY fld_filed_date_ts DESC;");

                while ($gft=$get_filed_transmittals->fetch_array()) {

                  $file = $gft['fld_filename'];


                  $provcode = $gft['fld_provcode'];

                  $get_entity_name = $dbh4->query("SELECT AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM tbentities WHERE AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$provcode."'");
                  $gen=$get_entity_name->fetch_array();

                  if($gen['name']){
                    $name = $gen['name'];
                  } else {
                    $name = "<b style='color: red;'>INVALID PROVIDER CODE</b>";
                  }

                  if($gft['fld_filed_date_ts']){
                    $filed_date = $gft['fld_filed_date_ts'];
                  } else {
                    $filed_date = "INGESTED DATA";
                  }

                  if ($gft['fld_trans_type'] == 1) {
                    $trans_type = "REGULAR SUBMISSION";
                  } elseif($gft['fld_trans_type'] == 2){
                    $trans_type = "SPECIAL SUBMISSION - CORRECTION FILE";
                  } elseif($gft['fld_trans_type'] == 3){
                    $trans_type = "SPECIAL SUBMISSION - DISPUTE";
                  } elseif($gft['fld_trans_type'] == 4){
                    $trans_type = "SPECIAL SUBMISSION - HISTORICAL DATA";
                  } elseif($gft['fld_trans_type'] == 5){
                    $trans_type = "EXTENDED REGULAR SUBMISSION";
                  } elseif($gft['fld_trans_type'] == 6){
                    $trans_type = "SPECIAL SUBMISSION - LATE SUBMISSION";
                  }

              ?>
              <tr>
                <td><?php echo $c2; ?></td>
                <td><?php echo $provcode; ?></td>
                <td><?php echo $name; ?></td>
                <td><?php echo $file; ?></td>
                <td><?php echo $gft['fld_total_subjects']; ?></td>
                <td><?php echo $gft['fld_total_contracts']; ?></td>
                <td><?php echo $gft['fld_date_covered']; ?></td>
                <td><?php echo $trans_type; ?></td>
                <td><span class="tag tag-success"><?php echo $filed_date; ?></span></td>
                
              </tr>
              <?php
                  $c2++;
                }
              ?>
            </tbody>
          </table>
    </div>
    </div>
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->