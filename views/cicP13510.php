<?php
if ($_GET['provcode']) {

  $get_se=$dbh4->query("SELECT a.fld_ctrlno, a.fld_type, AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) as fld_provcode, AES_DECRYPT(a.fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_name FROM tbentities a WHERE AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$_GET['provcode']."'");
  $gs=$get_se->fetch_array();

  $submission_type = array("rs"=>"REGULAR SUBMISSION", "cf"=>"CORRECTION FILE", "df"=>"DISPUTE", "hd"=>"HISORICAL DATA", "ers"=>"EXTENDED REGULAR SUBMISSION", "ls"=>"LATE SUBMISSION", "clf"=>"LAPSED REGULAR SUBMISSION");

  $selsubtype = $submission_type[$_GET['subtype']];
?>

<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title"><?php echo $_GET['provcode']. " - " .$gs['fld_name']; ?></h3>
    </div>
    <div class="card-body">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th><center>#</center></th>
            <th>Date Covered Period</th>
            <th>Filename</th>
            <th>Subjects</th>
            <th>Contracts</th>
            <th>Submission Type</th>
            <th>Date Filed</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $counter = 1;
            $monthquery = date("m", strtotime($_GET['month']));
            $yearquery = date("Y", strtotime($_GET['month']));
            if ($_GET['subtype'] == 'rs') {
              $submissiontypeval = 1;
            } elseif ($_GET['subtype'] == 'ers') {
              $submissiontypeval = 5;
            } elseif ($_GET['subtype'] == 'cf') {
              $submissiontypeval = 2;
            } elseif ($_GET['subtype'] == 'hd') {
              $submissiontypeval = 4;
            } elseif ($_GET['subtype'] == 'df') {
              $submissiontypeval = 3;
            } elseif ($_GET['subtype'] == 'ls') {
              $submissiontypeval = 6;
            } elseif ($_GET['subtype'] == 'clf') {
              $submissiontypeval = 7;
            }

            // echo "SELECT * FROM tbtransmittal WHERE fld_provcode = '".$_GET['provcode']."' and YEAR(fld_date_covered) = ".$yearquery." and MONTH(fld_date_covered) = ".$monthquery." and fld_trans_type = ".$submissiontypeval;
            $get_transmittal_details = $dbh4->query("SELECT * FROM tbtransmittal WHERE fld_provcode = '".$_GET['provcode']."' and YEAR(fld_date_covered) = ".$yearquery." and MONTH(fld_date_covered) = ".$monthquery." and fld_trans_type = ".$submissiontypeval);
            while ($gtd=$get_transmittal_details->fetch_array()) {
          ?>
          <tr>
            <td><?php echo $counter++; ?></td>
            <td><?php echo date("F Y", strtotime($gtd['fld_date_covered'])); ?></td>
            <td><?php echo $gtd['fld_filename']; ?></td>
            <td><?php echo number_format($gtd['fld_total_subjects']); ?></td>
            <td><?php echo number_format($gtd['fld_total_contracts']); ?></td>
            <td><?php echo $selsubtype; ?></td>
            <td><?php echo ($gtd['fld_filed_date_ts']) ? $gtd['fld_filed_date_ts'] : 'INGESTED DATA'; ?></td>
          </tr>
          <?php
              $total_subjects = $total_subjects + $gtd['fld_total_subjects'];
              $total_contracts = $total_contracts + $gtd['fld_total_contracts'];
            }
          ?>
          <tr>
            <td></td>
            <td><b>TOTAL</b></td>
            <td><b><?php echo $counter - 1; ?> FILES</b></td>
            <td><b><?php echo number_format($total_subjects); ?></b></td>
            <td><b><?php echo number_format($total_contracts); ?></b></td>
            <td><b><?php echo number_format($total_subjects + $total_contracts); ?></b></td>
            <td></td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->
<?php
} else {
  // include("403.php");
}
?>