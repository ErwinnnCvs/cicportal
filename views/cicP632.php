<?php
$date = explode("-", $_GET['dte']);
$provcode = $_GET['provcode'];
$det[0] = $date[0];
$det[1] = $date[1];
$det[2] = $date[2];

$get_entity_name = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, fld_access_limit, fld_accountno FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$provcode."'");
$gen=$get_entity_name->fetch_array();
?>
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-body">
      <center>
        <label>Company Name</label>
        <p><?php echo $gen['name']; ?></p>
        <label>Date</label>
        <p><?php echo date("F d, Y", strtotime($_GET['dte'])); ?></p>
       
      </center>

      <table class="table table-bordered table-hover table-sm">
        <thead>
          <tr>
            <th width="5%">User Code</th>
            <th width="5%">Inquiry Hour</th>
            <th width="5%">Branch Code</th>
            <th width="5%">Service Code</th>
            <th width="5%">Channel Code</th>
            <th width="5%">Source Code</th>
            <th width="5%">Subject Code</th>
            <th width="5%">Error Code</th>
            <th width="5%">Inquiry Count</th>
            <th width="5%">Inquiry Result</th>
            <th width="5%">Inquiry Price</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $get_inquiry_detailed = $dbh->query("SELECT fld_provcode, fld_usercode, fld_inqhour, fld_branchcode, fld_servicecode, fld_channelcode, fld_sourcecode, fld_subjcode, fld_errorcode, fld_inqcount as inqcnt, fld_inqresult, fld_inq_price as inqprice FROM tbinquiries WHERE fld_provcode = '".$provcode."' and YEAR(fld_inqdate) = '".$det[0]."' and MONTH(fld_inqdate) = '".$det[1]."' AND DAY(fld_inqdate) = '".$det[2]."' AND fld_branchcode = 'USERS' AND ((fld_sourcecode = 'CB_ME' AND fld_errorcode = 'NULL') OR (fld_sourcecode = 'CB_NAE' AND fld_errorcode = 'NULL') OR (fld_sourcecode = 'CB_ME' AND fld_errorcode LIKE '%1-100%') OR (fld_sourcecode = 'CB_ME' AND fld_errorcode = 'NULL') OR (fld_sourcecode = 'CB_NAE' AND fld_errorcode = 'NULL'))");
            while ($gid=$get_inquiry_detailed->fetch_array()) {
          ?>
          <tr>
            <td><?php echo $gid['fld_usercode']; ?></td>
            <td><?php echo $gid['fld_inqhour']; ?></td>
            <td><?php echo $gid['fld_branchcode']; ?></td>
            <td><?php echo $gid['fld_servicecode']; ?></td>
            <td><?php echo $gid['fld_channelcode']; ?></td>
            <td><?php echo $gid['fld_sourcecode']; ?></td>
            <td><?php echo $gid['fld_subjcode']; ?></td>
            <td><?php echo $gid['fld_errorcode']; ?></td>
            <td><?php echo $gid['inqcnt']; ?></td>
            <td><?php echo $gid['fld_inqresult']; ?></td>
            <td><?php echo $gid['inqprice']; ?></td>
          </tr>
          <?php
              $totalinqcnt += $gid['inqcnt'];
              $totalinqprice += $gid['inqprice'];
            }
          ?>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><b><?php echo $totalinqcnt; ?></b></td>
            <td></td>
            <td><b><?php echo $totalinqprice; ?></b></td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->