<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
  <!-- Main content -->
  <section class="content">
    <div class="card card-info">
      <!-- Card Body   -->
      <div class="card-header with-border"> 
        <h3 class="card-title">List of Submitting Entities for Batch Credentials Generation</h3>
      </div>
      
      <!-- Custom Tabs -->
      <div class="nav-tabs-custom p-1">
        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
          <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab" role="tab" aria-selected="true">Pending</a></li>
          <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab" role="tab" aria-selected="false">Completed</a></li>
          <li class="nav-item"><a class="nav-link" href="#tab_4" data-toggle="tab" role="tab" aria-selected="false">Update</a></li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane active" id="tab_1">
            <form method="post">
              <div class="col d-flex justify-content-end p-2">
                <button class="btn btn-info text-right" name="sbtGenerate">Generate Credentials</button>
              </div>
              
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th><center>#</center></th>
                    <th><center>Provider Code</center></th>
                    <th>Submitting Entity</th>
                    <th>Submitting Entity Type</th>
                    <th>Datetime</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $c = 0;
                  $sql = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_provcode, AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_name, fld_type, fld_rsrf_ts FROM tbentities WHERE fld_seis_noc_ts is null and fld_seis_noc_status = 0 and fld_status = 1");
                  while ($rows=$sql->fetch_array()) {
                    $c++;
                    $nocts = $rows['fld_rsrf_ts'] ? date("F d, Y H:ia", strtotime($rows['fld_rsrf_ts'])) : 'No date';
                  ?>
                  <tr>
                    <td><center><?php echo $c; ?></center></td>
                    <td><center><?php echo $rows['fld_provcode']; ?></center><input type="text" name="ctrlno" hidden value="<?php echo $rows['fld_ctrlno'] ?>"></td>
                    <td><a data-toggle="modal" data-target="#modal-default<?php echo $rows['fld_ctrlno']; ?>" href="#"><?php echo $rows['fld_name']; ?></a></td>
                    <td><?php echo $ent2[$rows['fld_type']]; ?></td>
                    <td><?php echo $nocts; ?></td>
                  </tr>
                  <?php
                  }
                  ?>
                </tbody>
              </table>
            </form>
          </div>

          <div class="tab-pane" id="tab_3">
            <h2 class="page-headers pt-4 pl-4">Completed Credentials</h2>
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th><center>#</center></th>
                  <th><center>Provider Code</center></th>
                  <th>Submitting Entity</th>
                  <th>Submitting Entity Type</th>
                  <th>Date Generated</th>
                  <th>File</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $s = 0;
                $generatedse = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_provcode, AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_name, fld_type, fld_seis_noc_ts FROM tbentities WHERE fld_noc_pass_status = 1 and fld_seis_noc_status = 1 ORDER BY fld_seis_noc_ts DESC");
                while ($gse=$generatedse->fetch_array()) {
                  $s++;
                  $filename = "SEIS-Users_".date("Y-m-d H-i-s", strtotime($gse['fld_seis_noc_ts'])).".csv";
                ?>
                <tr>
                  <td><center><?php echo $s; ?></center></td>
                  <td><center><?php echo $gse['fld_provcode']; ?></center></td>
                  <td><?php echo $gse['fld_name']; ?></td>
                  <td><?php echo $ent2[$gse['fld_type']]; ?></td>
                  <td><?php echo date("F d, Y - h:ia", strtotime($gse['fld_seis_noc_ts'])); ?></td>
                  <td><center><a href="downloadnoccsv.php?file=<?php echo $filename; ?>" class="btn btn-sm btn-info"><i class="fa fa-download"></i></a></center></td>
                </tr>
                <?php
                }
                ?>
              </tbody>
            </table>
          </div>

          <div class="tab-pane" id="tab_4">
            <h2 class="page-headers pt-4 pl-4">Update Credentials</h2>
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr>
                  <th>Provider Code</th>
                  <th>Submitting Entity</th>
                  <th>Submitting Entity Type</th>
                  <th>Datetime</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sql = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_provcode, AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_name, fld_type, fld_batops_ts FROM tbentities WHERE fld_batops_update = 1 and AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) NOT LIKE 'SAE%' AND (fld_registration_type = 4 || fld_registration_type = 0 || fld_registration_type = 1 && fld_se_testing_status = 3) ORDER BY fld_batops_ts DESC");
                while ($row=$sql->fetch_array()) {
                ?>
                <tr>
                  <form method="post" action="main.php?nid=109&sid=1&rid=0">
                    <td><?php echo $row['fld_provcode']; ?><input type="text" name="ctrlno" hidden value="<?php echo $row['fld_ctrlno'] ?>"></td>
                    <td><?php echo $row['fld_name']; ?></td>
                    <td><?php echo $ent2[$row['fld_type']]; ?></td>
                    <td><?php echo date("F d, Y H:ia", strtotime($row['fld_batops_ts'])); ?></td>
                    <td><button class="btn btn-success btn-block">View</button></td>
                  </form>
                </tr>
                <?php
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>

<?php
if (isset($_POST['sbtGenerate'])) {
  $timestamp = date("Y-m-d H:i:s");
  $get_entities = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_provcode, AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_name FROM tbentities WHERE fld_seis_noc_ts is null and fld_seis_noc_status = 0 and fld_status = 1");
  
  if (mysqli_num_rows($get_entities) > 0) {
    if (!is_dir('nocusers/seis')) {
      mkdir('nocusers/seis', 0777, true);
    }
    $fp = fopen('nocusers/seis/SEIS-Users_'.date("Y-m-d_H-i-s").'.csv', 'w');
    if ($fp) {
      fputcsv($fp, array('Control No', 'Provider Code', 'Entity Name', 'Generated Date'));
      while($row = mysqli_fetch_array($get_entities, MYSQLI_ASSOC)) {
        fputcsv($fp, array($row['fld_ctrlno'], $row['fld_provcode'], $row['fld_name'], $timestamp));
        $dbh4->query("UPDATE tbentities SET fld_seis_noc_ts = '$timestamp', fld_seis_noc_status = 1, fld_noc_pass_status = 1 WHERE fld_ctrlno = '".$row['fld_ctrlno']."'");
      }
      fclose($fp);
      echo "<script>alert('Credentials generated successfully!'); window.location.reload();</script>";
    } else {
      echo "<script>alert('Failed to create CSV file');</script>";
    }
  } else {
    echo "<script>alert('No records found');</script>";
  }
}
?>