<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);


if(!$_POST['navbutton']){
  $_POST['navbutton'][0] = "0";
}
// $dbh4->query("INSERT INTO tbmerging (fld_ctrlno, fld_fname, fld_mname, fld_lname, fld_sex, fld_tin, fld_provcode, fld_user) VALUES ('2019070009', AES_ENCRYPT('Juan', md5(CONCAT(fld_ctrlno, 'RA3019'))), AES_ENCRYPT('Reyes', md5(CONCAT(fld_ctrlno, 'RA3019'))), AES_ENCRYPT('Dela Cruz', md5(CONCAT(fld_ctrlno, 'RA3019'))), '1', '123456789', AES_ENCRYPT('RB005670', md5(CONCAT(fld_ctrlno, 'RA3019'))), '1' )");


if ($_POST['sbtMerge']) {
  if ($dbh4->query("UPDATE tbmerging SET fld_status = 2, fld_cic_remarks = '".addslashes($_POST['cic_remarks'])."', fld_cic_ts = '".date('Y-m-d H:i:s')."', fld_merged_by = '".$_SESSION['name']."' WHERE fld_id = '".$_POST['ctrlno_merge']."'")) {
    $msg = "Successfuly updated";
    $msgclr = "success";
  } else {
    $msg = "Error updating the record";
    $msgclr = "danger";
  }
}

if ($_POST['sbtCancelMerge']) {
  if ($dbh4->query("UPDATE tbmerging SET fld_status = 0, fld_merged_by = NULL WHERE fld_ctrlno = '".$_POST['ctrlno_merge']."'")) {
    // $msg = "Successfuly updated";
    // $msgclr = "success";
  } else {
    $msg = "Error updating the record";
    $msgclr = "danger";
  }
}


?>

<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
        Completed Items
      </button>
      <ul class="dropdown-menu">
        <li class="dropdown-item"><a href="main.php?nid=16&sid=0&rid=0">Pending</a></li>
        <li class="dropdown-item"><a href="main.php?nid=16&sid=1&rid=1">Completed</a></li>
      </ul>
    </div>
    <div class="card-body">
      <?php
        if ($msg) {
      ?>
      <div class="alert alert-<?php echo $msgclr; ?> alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fas fa-info"></i> Information!</h5>
                  <?php echo $msg; ?>
                </div>
      <?php
        }
      ?>

      <?php
        if ($_POST['sbtPendingMerge']) {
          $check_status = $dbh4->query("SELECT fld_status, AES_DECRYPT(fld_fname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fname, AES_DECRYPT(fld_mname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as mname, AES_DECRYPT(fld_lname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as lname, fld_merged_by FROM tbmerging WHERE fld_id = '".$_POST['sbtPendingMerge']."'");
          $cs = $check_status->fetch_array();

      ?>
      <div class="row">
        <div class="col-4">
          
        </div>
        <div class="col-4">
          <div class="card card-primary card-outline">
            <div class="card-header">
              Action - <?php echo $cs['lname']. ", ".$cs['fname']. " " .$cs['mname'] ; ?>
            </div>
            <div class="card-body">
              <?php
                if ($cs['fld_status'] == 1 and $cs['fld_merged_by'] != $_SESSION['name']) {
              ?>
              <h4 class="text-info">ONGOING PROCESS BY <?php echo $cs['fld_merged_by']; ?></h4>
              <?php
                } elseif($cs['fld_status'] == 0){
                  $dbh4->query("UPDATE tbmerging SET fld_status = 1, fld_merged_by = '".$_SESSION['name']."' WHERE fld_id = '".$_POST['sbtPendingMerge']."'");
              ?>
              <form method="POST">
                <input type="hidden" name="ctrlno_merge" value="<?php echo $_POST['sbtPendingMerge']; ?>">
                <p>Remarks:</p>
                <textarea class="form-control" name="cic_remarks"></textarea>
                <br>
                <button type="submit" name="sbtCancelMerge" class="btn btn-default" value="1">Cancel</button>
                <button type="submit" name="sbtMerge" class="btn btn-success float-right" value="1">Merge</button>
              </form>
              <?php
                } elseif($cs['fld_status'] == 1 and $cs['fld_merged_by'] == $_SESSION['name']) {
              ?>
              <h5 class="text-info">ONGOING PROCESS BY <?php echo $cs['fld_merged_by']; ?></h5>
              <form method="POST">
                <input type="hidden" name="ctrlno_merge" value="<?php echo $_POST['sbtPendingMerge']; ?>">
                <p>Remarks:</p>
                <textarea class="form-control" name="cic_remarks"></textarea>
                <br>
                <button type="submit" name="sbtCancelMerge" class="btn btn-default" value="1">Cancel</button>
                <button type="submit" name="sbtMerge" class="btn btn-success float-right" value="1">Merge</button>
              </form>
              <?php
                }
              ?>
            </div>
          </div>
        </div>
        <div class="col-4">
          
        </div>
      </div>
      <?php
        }
      ?>

        <form method="post">
          <div class="row">
          <div class="col-lg-4">
            <div class="form-group">
  <!--                    <form action="#" method="get" class="sidebar-form">    -->
              <label>Search</label> <small>( By Last Name or ID Number )</small>
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
      <table class="table table-bordered" id="merging_completed">
        <thead>
          <tr>
            <th><center>#</center></th>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Sex</th>
            <th>Birth Date</th>
            <th>ID</th>
            <th>Requested by</th>
            <th>Remarks</th>
            <th><center>Status</center></th>
          </tr>
        </thead>
        <tbody>
          <?php
          if($_POST['txtSearch']){
              $srch = " AND (UPPER( CONVERT(AES_DECRYPT(fld_lname, md5(CONCAT(fld_ctrlno, 'RA3019'))) USING 'utf8' )) LIKE UPPER('%".$_POST['txtSearch']."%') OR UPPER( CONVERT(AES_DECRYPT(fld_id_number, md5(CONCAT(fld_ctrlno, 'RA3019'))) USING 'utf8' )) LIKE UPPER('%".$_POST['txtSearch']."%'))";
            }
            $c = key($_POST['navbutton']);

            $get_all_merging_requests=$dbh4->query("SELECT fld_id, fld_ctrlno, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provcode, AES_DECRYPT(fld_fname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fname, AES_DECRYPT(fld_mname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as mname, AES_DECRYPT(fld_lname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as lname, fld_sex, AES_DECRYPT(fld_tin, md5(CONCAT(fld_ctrlno, 'RA3019'))) as tin, fld_status, fld_user, fld_cic_remarks, fld_cic_ts, fld_merged_by, fld_birthdate, fld_inserted_ts, fld_id_type, AES_DECRYPT(fld_id_number, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_id_number FROM tbmerging WHERE fld_status = 2".$srch." ORDER BY fld_cic_ts DESC LIMIT ".key($_POST['navbutton']).", 10");
            while ($gamr=$get_all_merging_requests->fetch_array()) {
              $c++;
              $get_entity_name = $dbh4->query("SELECT AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provcode FROM tbentities WHERE fld_ctrlno = '".$gamr['fld_ctrlno']."'");
              $gen=$get_entity_name->fetch_array();

              $get_entity_user = $dbh->query("SELECT fld_name, email FROM tbusers WHERE pkUserId = '".$gamr['fld_user']."'");
              $geu=$get_entity_user->fetch_array();
          ?>
          <tr>
            <td><center><?php echo $c; ?></center></td>
            <td><?php echo $gamr['lname']; ?></td>
            <td><?php echo $gamr['fname']; ?></td>
            <td><?php echo $gamr['mname']; ?></td>
            <td><?php if($gamr['fld_sex'] == 0) { echo "Female"; } else { echo "Male"; } ?></td>
            <td><?php echo $gamr['fld_birthdate']; ?></td>
            <td><?php echo "ID: ".$gamr['fld_id_number']."<br><small>".$gamr['fld_id_type']."</small>"; ?></td>
            <td><?php echo $gen['provcode']."<br><span><small>".$geu['fld_name']."<br>".$geu['email']."</small></span>"; ?></td>
            <td><?php if($gamr['fld_status'] == 2) { echo "<small><b>".date("F d, Y h:ia", strtotime($gamr['fld_cic_ts']))."</b></small><br><small>".$gamr['fld_merged_by']."</small><br>".$gamr['fld_cic_remarks']."<br>"; }?></td>
            <td>
              <?php if($gamr['fld_status'] == 0) { ?>
              
                <center><button type="submit" class="btn btn-warning" name="sbtPendingMerge" value="<?php echo $gamr['fld_id']; ?>">
                  Pending
                </button></center>
              
            <?php } elseif($gamr['fld_status'] == 1) {
              ?>
              <center><button class="btn btn-info">Ongoing Process</button></center>
            <?php
            } elseif($gamr['fld_status'] == 2) {
            ?>
              <center><button class="btn btn-success">Completed</button></center>
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
      <div class="row"> 
        <div class="col-xs-6" align="right">
        
          <div class="btn-group">
          <?php
            
            $get_all_merging_requests_cnt=$dbh4->query("SELECT COUNT(*) as rcnt FROM tbmerging WHERE fld_status <= 1".$srch." ORDER BY fld_cic_ts DESC");
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
      </form>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->