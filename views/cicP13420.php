<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
  if ($_POST['sbtUpdateAssign']) {

    $array_count = count($_POST['assignse']);

    // echo $array_count." COUNT";
    $currentassoc = [];
    $get_se_assoccount = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) as provcode, AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) as name, fld_assoc_code FROM tbentities WHERE fld_assoc_code = '".$_GET['code']."'");
    while($gsea=$get_se_assoccount->fetch_array()){
      $currentassoc[$gsea['provcode']] = $gsea['fld_ctrlno'];
    }

    if ($array_count > 0) {

      $added = array_diff($_POST['assignse'], $currentassoc);
      $removed = array_diff($currentassoc, $_POST['assignse']);

      foreach ($added as $keyupdate => $valueupdate) {
        $dbh4->query("UPDATE tbentities SET fld_assoc_code = '".$_GET['code']."' WHERE fld_ctrlno = ".$valueupdate);
      }

      foreach ($removed as $keyremove => $valueremove) {
        $dbh4->query("UPDATE tbentities SET fld_assoc_code = NULL WHERE fld_ctrlno = ".$valueremove);
      }

      $msg = "Successfully saved";
    } else {
      // echo "NO DATA";
      $dbh4->query("UPDATE tbentities SET fld_assoc_code = '' WHERE fld_assoc_code = '".$_GET['code']."'");
      $msg = "Successfully removed";
    }
    

  }


$get_assoc_detail = $dbh4->query("SELECT * FROM tbseassociations WHERE fld_code = '".$_GET['code']."'");
$gad=$get_assoc_detail->fetch_array();

if ($gad['fld_id']) {
  $selassign[$_GET['code']] = " selected";
?>

<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title"><?php echo $gad['fld_name']; ?></h3>
    </div>
    <div class="card-body">
      <?php
        if ($msg) {
      ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Information!</h5>
        <?php
          echo $msg;
        ?>
      </div>
      <?php
        }

        if ($error) {
      ?>
      <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h5><i class="icon fas fa-ban"></i> Alert!</h5>
      <?php echo $error; ?>
      </div>
      <?php
        }
      ?>
      <form method="POST">
      <div class="row">
        <div class="col-12">
          <div class="form-group">
            <label>Submitting Entities</label>
            <select class="duallistbox" name="assignse[]" multiple="multiple">
              <?php
                $get_all_seps_sub=$dbh4->query("SELECT a.fld_assoc_code, a.fld_ctrlno, a.fld_type, AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) as fld_provcode, AES_DECRYPT(a.fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_name, b.fld_date FROM tbentities a RIGHT JOIN tbsep b ON AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) = b.fld_provcode  WHERE a.fld_registration_type <> 1 OR (a.fld_registration_type = 1 AND a.fld_noc_pass_status = 1)");

                while($gass=$get_all_seps_sub->fetch_array()){
              ?>
              <option value="<?php echo $gass['fld_ctrlno']; ?>"<?php echo $selassign[$gass['fld_assoc_code']]; ?>><?php echo $gass['fld_provcode']. " - " .$gass['fld_name']; ?></option>
              <?php
                }
              ?>
            </select>
          </div>
          <button type="submit" name="sbtUpdateAssign" value="1" class="btn btn-success btn-block">
            SAVE
          </button>
          <!-- /.form-group -->
        </div>
        <!-- /.col -->
      </div>
      </form>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->
<?php
} else {
?>
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card card-warning">
    <div class="card-header">
      <h3 class="card-title">403</h3>
    </div>
    <div class="card-body">

      Ooops! Page not found. Please contact your developer or go back to <a href="main.php?nid=1&sid=0&rid=0">home</a>.
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->
<?php
}
?>