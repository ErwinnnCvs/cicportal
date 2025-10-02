<?php 

if ($_POST['sbtSave']) {
  if ($dbh4->query("UPDATE tbentities SET fld_tax = '".$_POST['sel_type']."' WHERE fld_ctrlno = '".$_POST['fld_ctrlno']."'")) {
    $msg = "Successfuly updated";
    $msgclr = "success";
  } else {
    $msg = "Error updating";
    $msgclr = "danger";
  }
  
}


?>
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">List of Institutions</h3>
    </div>
    <div class="card-body">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th width="5%"><center>#</center></th>
            <th width="20%">Account Number</th>
            <th width="50%">Company Name</th>
            <th width="15%"><center>Tax Type</center></th>
            <th width="10%"><center>Action</center></th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $get_all_institutions = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as company, fld_tax, fld_accountno FROM tbentities WHERE fld_aeis = 1");
            while($gai=$get_all_institutions->fetch_array()){
              $c++;
          ?>
          <tr>
            <td><center><?php echo $c; ?></center></td>
            <td><?php echo $gai['fld_accountno']; ?></td>
            <td><?php echo $gai['company']; ?></td>
            <td><center><?php if($gai['fld_tax'] == 0) {echo "NONE";} elseif($gai['fld_tax'] == 1){echo "Withholding Tax (2%)";} elseif($gai['fld_tax'] == 2){echo "VAT Exempt (12%)";} ?></center></td>
            <td>
              <center>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default<?php echo $gai['fld_ctrlno']; ?>">
                  <i class="fa fa-edit"></i>
                </button>
                <div class="modal fade" id="modal-default<?php echo $gai['fld_ctrlno']; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Update</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span>
                        </button>
                      </div>
                      <form method="post">
                      <div class="modal-body">
                        <input type="hidden" name="fld_ctrlno" value="<?php echo $gai['fld_ctrlno']; ?>">
                        <div class="form-group">
                          <label>Select Type</label>
                          <select class="custom-select" name="sel_type">
                            <option value="0">--NONE--</option>
                            <option value="1">Withholding Tax (2%)</option>
                            <option value="2">VAT Exempt (12%)</option>
                          </select>
                        </div>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" value="1" name="sbtSave" class="btn btn-primary">Save changes</button>
                      </div>
                      </form>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
              </center>
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