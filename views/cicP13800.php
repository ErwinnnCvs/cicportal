
<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

// $assignse[$_POST['assignse']] = " selected";
if(!$_POST['navbutton']){
  $_POST['navbutton'][0] = "0";
}


if ($_POST['sbtSave']) {
  if ($dbh4->query("INSERT INTO tbassign (fld_provcode, fld_assign, fld_by, fld_type) VALUES ('".$_POST['sbtSave']."', '".$_POST['assignse']."', '".$_SESSION['name']."', 1)")) {
    if ($dbh4->query("UPDATE tbentities SET fld_assignsep = 1 WHERE AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$_POST['sbtSave']."'")) {
      $msg = "Successfully updated.";
      $msgclr = "success";
    } else {
      $msg = "ERROR UPDATING";
      $msgclr = "danger";
    }
  } else {
    $msg = "ERROR "."INSERT INTO tbassign VALUES ('".$_POST['sbtSave']."', '".$_POST['assignse']."', '".$_SESSION['name']."', 1)";
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
          <li class="dropdown-item"><a href="main.php?nid=138&sid=0&rid=0">Unassigned</a></li>
          <li class="dropdown-item"><a href="main.php?nid=138&sid=2&rid=0">Assigned</a></li>
        </ul>
      </div>
    </div>
    <form method="POST">
    <div class="card-body">
      <div class="row">
          <div class="col-lg-4">
            <div class="form-group">
  <!--                    <form action="#" method="get" class="sidebar-form">    -->
              <label>Search</label> <small>( By Provider Code or Last Name )</small>
              <div class="input-group">
                <input type="text" name="txtSearch" class="form-control" placeholder="Search..." value="<?php echo $_POST['txtSearch']; ?>">
                  <span class="input-group-btn">
                    <button type="submit" name="sbtSearch" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                  </button>
                </span>
              </div>
  <!--                    </form>    -->
            </div>
          </div> 
        </div>
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

            if($_POST['txtSearch']){
              $srch = " AND (UPPER( CONVERT(AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) USING 'utf8' )) LIKE UPPER('%".$_POST['txtSearch']."%') OR UPPER( CONVERT(AES_DECRYPT(a.fld_name, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) USING 'utf8' )) LIKE UPPER('%".$_POST['txtSearch']."%'))";
            }
            $c = key($_POST['navbutton']);
            $get_all_seps_sub=$dbh4->query("SELECT a.fld_ctrlno, a.fld_type, AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) as fld_provcode, AES_DECRYPT(a.fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_name FROM tbentities a WHERE AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) NOT LIKE 'SAE%' AND fld_assignsep = 0 AND AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) <> '' AND AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) <> 'OT888888'".$srch." GROUP BY AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) LIMIT ".key($_POST['navbutton']).", 10");
            while ($gass=$get_all_seps_sub->fetch_array()) {
              $c++;
          ?>
          <tr>
            <td><center><?php echo $c; ?></center></td>
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
                    <!-- <form method="POST"> -->
                    <div class="modal-body">
                        <center>
                          <div class="form-group">
                            <label>Select Operator</label>
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
                        </center>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      <button type="submit" value="<?php echo $gass['fld_provcode']; ?>" name="sbtSave" class="btn btn-primary">Save changes</button>
                    <!-- </form> -->
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
      <br>
      <div class="row"> 
        <div class="col-xs-6" align="right">
        
          <div class="btn-group">
          <?php
            
            $get_all_merging_requests_cnt=$dbh4->query("SELECT COUNT(*) as rcnt FROM tbentities a WHERE AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) NOT LIKE 'SAE%' AND fld_assignsep = 0 AND AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) <> '' AND AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) <> 'OT888888'".$srch);
            $gamrcnt=$get_all_merging_requests_cnt->fetch_array();

            $next = key($_POST['navbutton']) + 10;
            $last = ($gamrcnt['rcnt'] - ($gamrcnt['rcnt'] % 10));
            $previous = key($_POST['navbutton']) - 10;
            if($next > $last){
              $ndisabled = " disabled";
            }
            if($previous < 0){
              $pdisabled = " disabled";
            }
          ?>
            <button type="submit" class="btn btn-default" name="navbutton[0]" value="0"<?php echo $pdisabled; ?>><< First</button>
            <button type="submit" class="btn btn-default" name="navbutton[<?php echo $previous; ?>]" value="20"<?php echo $pdisabled; ?>>< Previous</button>
            <button type="submit" class="btn btn-default" name="navbutton[<?php echo $next; ?>]" value="50"<?php echo $ndisabled; ?>>Next ></button>
            <button type="submit" class="btn btn-default" name="navbutton[<?php echo $last; ?>]" value="100"<?php echo $ndisabled; ?>>Last>></button>
          </div>
      </div>
      </div>
    </div>
  </form>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->