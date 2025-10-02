<?php

if ($_POST['sbtSubmitApproval']) {
  if (!$_POST['selApprovalOptions']) {
    $err = "Please select validation";
  } else {
    $validation = $_POST['selApprovalOptions'];
    $name = $_SESSION['name'];
    $timestamp = date("Y-m-d H:i:s");

    $remarks = trim($_POST['commentTxt']);


    $dbh4->query("UPDATE tbrequestextension SET fld_status = ".$validation.", fld_approved_by = '".$name."', fld_approved_ts = '".$timestamp."' WHERE fld_id = ".$_GET['id']);

    $msg = " Successfully updated";
  }
}


$get_request_extension_details = $dbh4->query("SELECT * FROM tbrequestextension WHERE fld_id = ".$_GET['id']);

$gred=$get_request_extension_details->fetch_array();

$transType[$gred['fld_submission_type']] = ' selected';

$selvaloptions = array("1" => "Approved", "3" => "Rejected"); 

if ($_POST['selApprovalOptions']) {
    $valsel[$_POST['selApprovalOptions']] = " selected";
} else {
    $valsel[$gred['fld_status']] = " selected";
}



?>

<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <?php
        if($msg){
      ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        <?php echo $msg; ?>
      </div>
      <?php
        }
      ?>
    </div>
    <div class="card-body">
      <div class="row">

          <div class="col-lg-6">
              <div class="form-group">
                  <label for="input-mname" class="col-form-label">Requested Date for Extension</label>
                  <input type="text" class="form-control"  name="requested_date" id="requested_date" value="<?php echo date("F d, Y", strtotime($gred['fld_request_date'])); ?>" disabled>
              </div>
              <div class="form-group">
                      <label class="col-form-label">Submission Type</label>
                      
                      <select class="custom-select transType" name="transType" id="transType" value="<?php echo $_POST['transType']?>" disabled>
                          <option value="x" selected="" disabled="">Select Transmittal Type</option>
                          <option value="1"<?php echo $transType[1]; ?>>Regular Submission</option>
                          <!-- <option value="5" <?php echo $transType['5'];?>>Extended Regular Submission</option> -->
                          <!-- <option value="6"<?php echo $disableLateSub; ?> <?php echo $transType['6'];?>>Regular Submission - LATE</option>
                          <option value="2" <?php echo $transType['2'];?>>Special Submission - Correction File</option>
                          <option value="3" <?php echo $transType['3'];?>>Special Submission - Dispute</option>
                          <option value="4" <?php echo $transType['4'];?>>Special Submission - Historical Data</option> -->
                      </select>
              </div>

              <div class="form-group">
                  <label for="input-mname" class="col-form-label">Date Request Filed</label>
                  <input type="text" class="form-control"  name="requested_date" id="requested_date" value="<?php echo date("F d, Y", strtotime($gred['fld_date_requested'])); ?>" disabled>
              </div>
              <br>
              <div id="all"> 
                  <?php
                      echo $gred['fld_reason'];
                  ?>
              </div>
          </div>  
                  
          <div class="col-lg-6">
              <div class="card">
        <div class="card-header d-flex p-0">
          <h3 class="card-title p-3">Action</h3>
        </div><!-- /.card-header -->
        <div class="card-body">
          <form method="post">
            <div class="form-group">
              <label>Endorsed By</label>
              <input type="text" class="form-control" value="<?php echo $gred['fld_endorsed_by']; ?>" disabled>
            </div>
            <div class="form-group">
              <label>Endorsed Date</label>
              <input type="text" class="form-control" value="<?php echo $gred['fld_endorsed_ts']; ?>" disabled>
            </div>
            <div class="form-group">
              <label>Validation</label>
              <?php
              if ($gred['fld_status'] == 0) {
              ?>
            </div>
                
                <label>Add Comment</label>
                <textarea class="form-control" name="commentTxt"></textarea>
                <br>
                <button type="submit" name="sbtCommentSave" value="1" class="btn btn-default">Save</button>
                <?php
                  } else {
                ?>

                  <label>Comments</label>
                  <textarea class="form-control" name="commentTxt" disabled>
                    <?php
                      echo $gred['fld_validation_remarks'];
                    ?>
                  </textarea>
                <?php
                  }
                ?>

                <?php
                  if ($gred['fld_status'] <= 1) {
                ?>
                <div class="form-group">
                  <label>Action</label>
                  <select class="form-control" name="selApprovalOptions" required>
                    <option selected disabled>--SELECT OPTION---</option>
                    <option value="2">Approved</option>
                    <option value="3">Rejected</option>
                  </select>  
                </div>
                <br>
                <button type="submit" name="sbtSubmitApproval" value="1" class="btn btn-success btn-block">Submit</button>
                <?php
                  } else {
                ?>
                <br>
                <div class="form-group">
                  <label>Approved By</label>
                  <input type="text" class="form-control" value="<?php echo $gred['fld_approved_by']; ?>" disabled>
                </div>
                <div class="form-group">
                  <label>Approved Date</label>
                  <input type="text" class="form-control" value="<?php echo $gred['fld_approved_ts']; ?>" disabled>
                </div>
                <?php
                  }
                ?>
          </form>
        </div><!-- /.card-body -->
      </div>
      <!-- ./card -->
          </div>

      </div>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->