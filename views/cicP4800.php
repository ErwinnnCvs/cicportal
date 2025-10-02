<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);


if ($_POST['uplFile']) {
$ext = strtoupper(pathinfo($_FILES["anomalousFile"]["name"], PATHINFO_EXTENSION));
  if ($ext == 'CSV') {
    $sheetData = array_map('str_getcsv', file($_FILES["anomalousFile"]["tmp_name"]));
    
    #CREATE TEMPORARY TABLE
    $dbh->query("CREATE TABLE IF NOT EXISTS tbanomalous1 LIKE tbanomalous;");
    $dbh->query("TRUNCATE TABLE `tbanomalous1`");


    $stmt = $dbh->prepare("INSERT INTO `tbanomalous1` (`fld_provcode`, `fld_provtypecode`, `fld_status`, `fld_errorcode`, `fld_count`) VALUES (?, ?, ?, ?, ?);");
    foreach ($sheetData as $key => $row) {
      if ($key > 0) {
        $stmt->bind_param('ssssi', $row[0], $row[1], $row[2], $row[3], $row[4]);
        $stmt->execute();

        if ($dbh->warning_count) {
          $msg = 'Please check row '.($key + 1);
          $msg_type = 'danger';
          break;
        }
      }
    }

    if (!$msg) {
      $dbh->query("DROP TABLE `tbanomalous`");
      $dbh->query("RENAME TABLE `tbanomalous1` TO `tbanomalous`;");
      $msg = 'File loaded successfuly.';
      $msg_type = 'success';
    }

    

  }
}
?>
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
      <?php
      if ($msg) {
      ?>
      <div class="alert alert-<?php echo $msg_type;?>" role="alert">
        <?php echo $msg;?>
      </div>
      <?php
      }
      ?>
      <div class="card">
        <div class="card-body">
          <h3 class="page-header">Upload CSV file</h3>
          <form method="POST" enctype="multipart/form-data">
        <div class="form-group">

          <label for="paymentFile">Upload Anomalous file here</label><br/>
          <input type="file" name="anomalousFile" required><br/>
          <p class="help-block">.csv file only</p><br/>

          <button type="submit" name="uplFile" class="btn btn-primary" value="2">Upload File</button>
          

        </div>
      </form>
        </div>
      </div>

      
    </div>
    <div class="col-md-4"></div>
  </div>
  

</section>
<!-- /.content -->