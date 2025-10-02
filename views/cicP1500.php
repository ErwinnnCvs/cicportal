<?php

if ($_POST['sbtOperationalYes']) {
  $controlno_se = $_POST['controlno_close'];
  $circular_no = $_POST['circular_no'];
  $circular_subject = addslashes($_POST['circular_subject']);

  if ($dbh4->query("UPDATE tbentities SET fld_operational = 1, fld_delisting_circular_no = '".$circular_no."', fld_delisting_circular_remarks = '".$_SESSION['name']."', fld_delisting_circular_ts = '".date("Y-m-d H:i:s")."' WHERE fld_ctrlno = '".$controlno_se."'")) {


    $path = $_FILES['circular_file']['name'];
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    
    switch ($ext) {
      case 'PNG':
        $uploaddir = 'files/png/';
        break;
      case 'png':
        $uploaddir = 'files/png/';
        break;
      case 'JPEG':
        $uploaddir = 'files/jpg/';
        break;
      case 'jpg':
        $uploaddir = 'files/jpg/';
        break;
      case 'docx':
        $uploaddir = 'files/word/';
        break;
      case 'PDF':
        $uploaddir = 'files/pdf/';
        break;
      case 'pdf':
        $uploaddir = 'files/pdf/';
        break;
      case 'xlsx':
        $uploaddir = 'files/excel/';
        break;
      case 'xlsm':
        $uploaddir = 'files/excel/';
        break;
      default:
        $uploaddir = 'files/';
        break;
    }

    
    $uploadfile = $uploaddir . $controlno_se."_Circular".".".$ext;

    if (move_uploaded_file($_FILES['circular_file']['tmp_name'], $uploadfile)) {
        include("mailer/entityclosing_mailnoc.php");
        include("mailer/entityclosing_mailsecurity.php");
        include("mailer/entityclosing_mailall.php");
        $msg = "Successfully updated";
        $msgclr = "success";
    } else {
        $msg = "Error encountered uploading the file.";
        $msgclr = "danger";    
    }
    
  } else {
    $msg = "Error updating the data.";
    $msgclr = "danger";
  }
}

?>

<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <div class="input-group-prepend">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              Submitting Entities
            </button>
            <ul class="dropdown-menu">
              <li class="dropdown-item"><a href="main.php?nid=15&sid=0&rid=0">Delist</a></li>
              <li class="dropdown-item"><a href="main.php?nid=15&sid=1&rid=1">Delisted</a></li>
              <!-- <li class="dropdown-item"><a href="main.php?nid=3&sid=0&rid=0&fstatus=3">Completed</a></li> -->
            </ul>
          </div>
    </div>
    <div class="card-body">
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
      <table class="table table-bordered" id="delist_se">
        <thead>
          <tr>
            <th>Provider Code</th>
            <th>Company Name</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $get_all_fi = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provcode, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, AES_DECRYPT(fld_ip, md5(CONCAT(fld_ctrlno, 'RA3019'))) as ip, fld_operational FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) <> ''");
            while ($gaf=$get_all_fi->fetch_array()) {
          ?>
          <tr>
            <td><?php echo $gaf['provcode']; ?></td>
            <td><?php echo $gaf['name']; ?></td>
            <td>
              <?php
                if ($gaf['fld_operational'] == 0) {
              ?>
              <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#modal-success<?php echo $gaf['fld_ctrlno']; ?>">Delist</button>
              <div class="modal fade" id="modal-success<?php echo $gaf['fld_ctrlno']; ?>">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Action</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                      <div class="modal-body">
                        <input type="hidden" name="controlno_close" value="<?php echo $gaf['fld_ctrlno']; ?>">
                        Are you sure you want to close <b><?php echo $gaf['name']; ?></b>?
                        <br><br>
                        
                        <div class="form-group">
                          <label>Enter Circular Letter No. <span class="text-danger">*</span></label>
                          <input type="text" name="circular_no" class="form-control" placeholder="e.g CL-1997-001" required>
                        </div>
                        <div class="form-group">
                          <label>Subject <span class="text-danger">*</span></label>
                          <input type="text" name="circular_subject" class="form-control" required>
                        </div>
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" name="circular_file" id="customFile">
                          <label class="custom-file-label" for="customFile">Upload Circular Letter</label>
                        </div>
                        <br><br>
                        <div class="form-group">
                          <label>Remarks</label>
                          <textarea class="form-control" rows="3" placeholder="Enter Remarks..."></textarea>
                        </div>

                        <p class="text-danger">Required Fields *</p>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" name="sbtOperationalYes" value="1">Save</button>
                      </div>
                    </form>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
              <!-- /.modal -->
              <?php
                } else {
              ?>
              <button type="button" class="btn btn-danger">NOT OPERATIONAL</button>
              <?php    
                }
              ?>
            </td>
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
