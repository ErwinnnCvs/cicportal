
<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

// $assignse[$_POST['assignse']] = " selected";

if ($_POST['sbtSave']) {
  if ($dbh4->query("INSERT INTO tbassign (fld_provcode, fld_assign, fld_by, fld_type) VALUES ('".$_POST['sbtSave']."', '".$_POST['assignse']."', '".$_SESSION['name']."', 2)")) {
    if ($dbh4->query("UPDATE tbentities SET fld_assignsep_legal = 1 WHERE AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$_POST['sbtSave']."'")) {
      $msg = "Successfully updated.";
      $msgclr = "success";
    } else {
      $msg = "ERROR UPDATING";
      $msgclr = "danger";
    }
  } else {
    $msg = "ERROR "."INSERT INTO tbassign VALUES ('".$_POST['sbtSave']."', '".$_POST['assignse']."', '".$_SESSION['name']."', 2)";
    $msgclr = "danger";
  }
  
  // echo $_POST['sbtSave']. " " .$_POST['assignse'];
}
?>
<!-- Main content -->
<section class="content">

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
  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <div class="input-group-prepend">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          Unassigned
        </button>
        <ul class="dropdown-menu">
          <li class="dropdown-item"><a href="main.php?nid=140&sid=0&rid=0">Unassigned</a></li>
          <li class="dropdown-item"><a href="main.php?nid=140&sid=2&rid=0">Assigned</a></li>
        </ul>
      </div>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-hover" id="unassignedse">
        <thead>
          <tr>
            <th><center>#</center></th>
            <th><center>Provider Code</center></th>
            <th>Submitting Entity</th>
            <th><center>Assign</center></th>
          </tr>
        </thead>
        <tbody>
          <?php
            $c = 1;
            $get_all_seps_sub=$dbh4->query("SELECT a.fld_ctrlno, a.fld_type, AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) as fld_provcode, AES_DECRYPT(a.fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_name FROM tbentities a LEFT JOIN tbsubmissiondetails b ON AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) = b.fld_provcode  WHERE AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) NOT LIKE 'SAE%' AND fld_assignsep_legal = 0 AND (a.fld_registration_type <> 1 OR (a.fld_registration_type = 1 AND a.fld_noc_pass_status = 1)) GROUP BY b.fld_provcode");
            while ($gass=$get_all_seps_sub->fetch_array()) {
          ?>
          <tr>
            <td><center><?php echo $c++; ?></center></td>
            <td><center><?php echo $gass['fld_provcode']; ?></center></td>
            <td><?php echo $gass['fld_name']; ?></td>
            <td>
              <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-default<?php echo trim($gass['fld_provcode']); ?>">
              Assign
              </button>
              <div class="modal fade" id="modal-default<?php echo trim($gass['fld_provcode']); ?>" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Assign Operator to <?php echo $gass['fld_name']; ?></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                    </div>
                    <form method="POST">
                    <div class="modal-body">
                        <center>
                          <div class="form-group">
                            <label>Select Operator</label>
                            <select class="form-control select2" name="assignse" style="width: 100%;">
                              <option disabled selected>----SELECT----</option>
                            <?php
                              $get_all_personnel=$dbh->query("SELECT * FROM tbcicusers WHERE email LIKE 'gilbertlouis.mulano%' OR email LIKE 'jacquesmichele.obias%' OR email LIKE 'renee.gabriel%' OR email LIKE 'anialorraine.wu%' OR email LIKE 'nicolle.delapena%'");
                              while ($gap=$get_all_personnel->fetch_array()) {
                                echo "<option value='".$gap['pkUserId']."'>".$gap['fld_name']."</option>";
                              }
                            ?>
                            </select>
                          </div>
                        </center>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      <button type="submit" value="<?php echo $gass['fld_provcode']; ?>" name="sbtSave" class="btn btn-primary">Save changes</button>
                    </form>
                    </div>
                  </div>
                </div>
              </div>
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