<?php

if (!$_POST['yearmonth']) {
  $det[0] = date("Y");
  $det[1] = date("m");
  $yrsel[date("Y-m")] = " selected";
  $labelyearmonth = $abbrmo[(int)$det[1]]." ".$det[0];
}

if($_POST['yearmonth']){
  $active1[2] = " class='active'";
  $active2[2] = " active";
  $yrsel[$_POST['yearmonth']] = " selected";
  $det = explode("-", $_POST['yearmonth']);
  
  $labelyearmonth = $abbrmo[(int)$det[1]]." ".$det[0];
}

if (!$_POST['filedType']) {
  $filedsel['all'] = " selected";
  $_POST['filedType'] = 'all';
}

if ($_POST['filedType']) {
  $filedsel[$_POST['filedType']] = " selected";
}

if(!$_POST['transType']){
  $_POST['transType'] = "all";
}
// 1 - Regular Submission
// 2 - Special Submission - Correction File
// 3 - Special Submission -Dispute
// 4 - Special Submission - Historical Data
// 5 - Extended Regular Submission
// 6- Regular Submission - Late
$transmittal_type_arr = array(1=>"REGULAR SUBMISSION", 2=>"SPECIAL SUBMISSION", 3=>"SPECIAL SUBMISSION - DISPUTE", 4=>"SPECIAL SUBMISSION - HISTORICAL DATA",
                              5=>"EXTENDED REGULAR SUBMISSION", 6=>"REGULAR SUBMISSION - LATE");
?>
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Submission</h3>
      <!-- <div class="input-group-prepend">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            Submissions
          </button>
          <ul class="dropdown-menu">
          <li class="dropdown-item"><a href="main.php?nid=130&sid=0&rid=0">All Tickets Received</a></li>
            <li class="dropdown-item"><a href="main.php?nid=130&sid=1&rid=1">Pending Transmittals</a></li>
            <li class="dropdown-item"><a href="main.php?nid=130&sid=1&rid=2">Filed Transmittals</a></li>
          </ul>
        </div> -->
    </div>
    <div class="card-body">
      <center>
      <form method="post">
        <label>Month Year</label>
        <select name="yearmonth" class="form-control" style="width: 10%;" onchange="submit()">
        <?php
          $cnt1=0;
          $sql=$dbh5->query("SELECT SUBSTR(fld_created_time, 1, 7) AS ym FROM tbprodtickets WHERE SUBSTR(fld_created_time, 1, 7) <= '".date("Y-m")."' GROUP BY SUBSTR(fld_created_time, 1, 7)");
          while($h=$sql->fetch_array()){
            if(!$_POST['yearmonth']){
              $_POST['yearmonth'] = $h['ym'];
            }
            $dt = explode("-", $h['ym']);
            echo "<option value='".$h['ym']."'".$yrsel[$h['ym']].">".$abbrmo[(int)$dt[1]]." ".$dt[0]."</option>";
          }
        ?>
        </select>
        <br>
        <label>Type</label>
        <select name="filedType" class="form-control" style="width: 10%;" onchange="submit()">
        <?php
            echo "<option value='all'".$filedsel['all'].">All</option>";
            echo "<option value='pending'".$filedsel['pending'].">Pending</option>";
            echo "<option value='filed'".$filedsel['filed'].">Filed</option>";
        ?>
        </select>
      </form>
      <br>
      <h3 class="page-header text-primary"><?php echo $labelyearmonth; ?></h3>
       
      </center>
        
       
       <br>
          <table class="table table-bordered table-hover table-sm" id="filedTransmittals">
            <thead>
              <tr>
              <th>#</th>
              <th>Provider Code</th>
              <th>Company</th>
              <th>Filename</th>
              <th>Arrival</th>
              <th>Subject</th>
              <th>Contracts</th>
              <th>Date Covered</th>
              <th>Transmittal Type</th>
              <th>Date Filed</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $c2 = 1;
                $get_all_submissions = $dbh->query("SELECT * FROM tbprodtickets WHERE YEAR(fld_created_time) = '".$det[0]."' and MONTH(fld_created_time) = '".$det[1]."' ORDER BY fld_created_time");
                while ($gas=$get_all_submissions->fetch_array()) {

                  $filename = explode(":", $gas['fld_subject']);
                  $filess = str_split($filename[1], 36);

                  $file = $filess[1].".TXT";
                  $provcode = $gas['fld_provcode'];

                  $get_entity_name = $dbh4->query("SELECT AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM tbentities WHERE AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$provcode."'");
                  $gen=$get_entity_name->fetch_array();

                  $get_transmittal_data = $dbh4->query("SELECT * FROM tbtransmittal WHERE fld_filename = '".$file."'");
                  $gtd=$get_transmittal_data->fetch_array();

                  if($gen['name']){
                    $name = $gen['name'];
                  } else {
                    $name = "<b style='color: red;'>INVALID PROVIDER CODE</b>";
                  }



                  

              if ($_POST['filedType'] == 'filed' || $_POST['filedType'] == 'all') {
                if ($gtd['fld_id']) {
                    $color = "";
                    if ($gtd['fld_filed_date_ts']) {
                      $filed_date = date("Y-m-d hi: A", strtotime($gtd['fld_filed_date_ts']));
                    } else {
                      $filed_date = "INGESTED DATA";
                    }

                    $total_subjects = $gtd['fld_total_subjects'];
                    $total_contracts = $gtd['fld_total_contracts'];
                    $date_covered = date("F Y", strtotime($gtd['fld_date_covered']));
                    $transmittal_type = $transmittal_type_arr[$gtd['fld_trans_type']];

              ?>
              <tr style="background-color: <?php echo $color; ?>;">
                <td><?php echo $c2; ?></td>
                <td><?php echo $provcode; ?></td>
                <td><?php echo $name; ?></td>
                <td><?php echo $file; ?></td>
                <td><?php echo date("F d, Y", strtotime($gas['fld_created_time'])); ?></td>
                <td><?php echo $total_subjects; ?></td>
                <td><?php echo $total_contracts; ?></td>
                <td><?php echo $date_covered; ?></td>
                <td><?php echo $transmittal_type; ?></td>
                <td><span class="tag tag-success"><?php echo $filed_date; ?></span></td>
                
              </tr>
              <?php
                  $c2++;
                    }
                  }


                  if ($_POST['filedType'] == 'pending' || $_POST['filedType'] == 'all') {
                    $color = "#E5C40C";
                    $filed_date = "PENDING";
                    $total_subjects = "PENDING";
                    $total_contracts = "PENDING";
                    $date_covered = "PENDING";
                    $transmittal_type = "PENDING";

              ?>
              <tr style="background-color: <?php echo $color; ?>;">
                <td><?php echo $c2; ?></td>
                <td><?php echo $provcode; ?></td>
                <td><?php echo $name; ?></td>
                <td><?php echo $file; ?></td>
                <td><?php echo date("F d, Y", strtotime($gas['fld_created_time'])); ?></td>
                <td><?php echo $total_subjects; ?></td>
                <td><?php echo $total_contracts; ?></td>
                <td><?php echo $date_covered; ?></td>
                <td><?php echo $transmittal_type; ?></td>
                <td><span class="tag tag-success"><?php echo $filed_date; ?></span></td>
                
              </tr>
              <?php
                    $c2++;
                  }
                }
              ?>
            </tbody>
          </table>
    </div>
    </div>
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->