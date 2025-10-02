
<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

// $assignse[$_POST['assignse']] = " selected";

if ($_POST['sbtSave']) {
  if($dbh4->query("UPDATE tbassign SET fld_active = 0 WHERE fld_provcode = '".$_POST['sbtSave']."'")){
    if ($dbh4->query("INSERT INTO tbassign (fld_provcode, fld_assign, fld_by) VALUES ('".$_POST['sbtSave']."', '".$_POST['assignse']."', '".$_SESSION['name']."')")) {
      echo "UPDATED";
    } else {
      echo "ERROR";
    }
  } else {
    echo "ERROR";
  }
  
  // echo $_POST['sbtSave']. " " .$_POST['assignse'];
}
?>
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <div class="input-group-prepend">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          Assigned
        </button>
        <ul class="dropdown-menu">
          <li class="dropdown-item"><a href="main.php?nid=138&sid=0&rid=0">Unassigned</a></li>
          <li class="dropdown-item"><a href="main.php?nid=138&sid=2&rid=0">Assigned</a></li>
        </ul>
      </div>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-hover" id="assignedse">
        <thead>
          <tr>
            <th><center>#</center></th>
            <th><center>Provider Code</center></th>
            <th>Submitting Entity</th>
            <th><center>Assigned To</center></th>
            <th><center>Assigned DateTime</center></th>
          </tr>
        </thead>
        <tbody>
          <?php
            $c = 1;
            $get_all_seps_sub=$dbh4->query("SELECT a.fld_ctrlno, a.fld_type, AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) as fld_provcode, AES_DECRYPT(a.fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_name, b.fld_date FROM tbentities a RIGHT JOIN tbsep b ON AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) = b.fld_provcode  WHERE fld_assignsep = 1 AND (a.fld_registration_type <> 1 OR (a.fld_registration_type = 1 AND a.fld_noc_pass_status = 1))");
            while ($gass=$get_all_seps_sub->fetch_array()) {
              $get_assignsep_table = $dbh4->query("SELECT * FROM tbassign WHERE fld_provcode = '".$gass['fld_provcode']."' and fld_active = 1 and fld_type = 1 ORDER BY fld_id DESC LIMIT 1");
              $gasept=$get_assignsep_table->fetch_array();

               $get_user=$dbh->query("SELECT fld_name FROM tbcicusers WHERE pkUserId = ".$gasept['fld_assign']);
               $gu=$get_user->fetch_array();
          ?>
          <tr>
            <td><center><?php echo $c++; ?></center></td>
            <td><center><?php echo $gass['fld_provcode']; ?></center></td>
            <td><?php echo $gass['fld_name']; ?></td>
            <td>
              <a href="#" data-toggle="modal" data-target="#modal-default<?php echo $gass['fld_provcode']; ?>">
                <?php
                 echo $gu['fld_name']; 
                ?>
              </a>
              <div class="modal fade" id="modal-default<?php echo $gass['fld_provcode']; ?>" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Update Operator to <?php echo $gass['fld_name']; ?></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                    </div>
                    <form method="POST">
                    <div class="modal-body">
                        <center>
                          <div class="form-group">
                            <label>Current Operator:</label>
                            <p><?php echo $gu['fld_name']; ?></p>
                          </div>
                          <div class="form-group">
                            <label>New Operator</label>
                            <select class="form-control select2" name="assignse" style="width: 100%;">
                              <option disabled selected>----SELECT----</option>
                            <?php
                              $get_all_personnel=$dbh->query("SELECT * FROM tbcicusers WHERE email LIKE 'trixia.basa%' OR email LIKE 'rex.berdandino%' OR email LIKE 'jho.mercado%' OR email LIKE 'jacquiline.cardino%' OR email LIKE 'rowena.castro%' OR email LIKE 'mj.tinagan%' OR email LIKE 'ccu.test%'");
                              while ($gap=$get_all_personnel->fetch_array()) {
                                echo "<option value='".$gap['pkUserId']."'>".$gap['fld_name']."</option>";
                              }
                            ?>
                            </select>
                          </div>
                          <br>
                          <label>History</label>
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th>Was Assigned To</th>
                                <th>Assigned By</th>
                                <th>DateTime</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                                $get_all_history_assignment = $dbh4->query("SELECT * FROM tbassign WHERE fld_provcode = '".$gass['fld_provcode']."' and fld_active = 0 ORDER BY fld_ts DESC");
                                while ($gaha=$get_all_history_assignment->fetch_array()) {
                                  $get_user_history=$dbh->query("SELECT fld_name FROM tbcicusers WHERE pkUserId = ".$gaha['fld_assign']);
                                  $guh=$get_user_history->fetch_array();
                              ?>
                              <tr>
                                <td><?php echo $guh['fld_name']; ?></td>
                                <td><?php echo $gaha['fld_by']; ?></td>
                                <td><?php echo $gaha['fld_ts']; ?></td>
                              </tr>
                              <?php
                              }
                              ?>
                            </tbody>
                          </table>
                        </center>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      <button type="submit" value="<?php echo $gass['fld_provcode']; ?>" name="sbtSave" class="btn btn-primary">Update changes</button>
                    </form>
                    </div>
                  </div>
                </div>
              </div>
            </td>
            <td><center><?php echo $gasept['fld_ts']; ?></center></td>
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