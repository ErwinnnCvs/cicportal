<?php




if (!$_POST['yearmonth']) {
    $yrsel[date("Y-m-d", strtotime(date('Y-m-d', strtotime('last day of last month'))))] = ' selected';
}
$yrsel[$_POST['yearmonth']] = ' selected';

?>
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">List of SOC</h3>
    </div>
    <div class="card-body">
        <center>
        <form method="post">
        <select name="yearmonth" class="form-control" style="width: 10%;" onchange="submit()">
        <?php
          $cnt1=0;
          $sql=$dbh->query("SELECT fld_stmt_date FROM tbcrbillingbalance WHERE fld_stmt_date <> '0000-00-00' GROUP BY fld_stmt_date;");
          while($h=$sql->fetch_array()){
            // if(!$_POST['yearmonth']){
            //   $_POST['yearmonth'] = $h['ym'];
            // }
            // $dt = explode("-", $h['ym']);
                echo "<option value='".$h['fld_stmt_date']."'".$yrsel[$h['fld_stmt_date']].">".date("F Y", strtotime($h['fld_stmt_date']))."</option>";
            }
        ?>
        </select>
        </form>
        <br>
        <table class="table table-bordered table-hover table-sm" id="socpdf">
            <thead>
            <tr>
                <th>Account Number</th>
                <th>Provider Code</th>
                <th>SE Name</th>
                <th>Statement Month</th>
                <th>Statement ID</th>
                <th>Beginning Balance</th>
                <th>Date Sent</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
                if($_POST['yearmonth']){
                    $date = $_POST['yearmonth'];
                } else {
                    $date = date("Y-m-d", strtotime(date('Y-m-d', strtotime('last day of last month'))));
                }
                
                $sql1 = $dbh->query("SELECT * FROM `tbcrbillingbalance` WHERE fld_stmt_date = '".$date."'");
                while($r1 = $sql1->fetch_array()){
                    $get_se_details = $dbh4->query("SELECT fld_accountno, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, AES_DECRYPT(fld_bill_contact_lname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_lname, fld_accountno FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$r1['fld_provcode']."' ");
                    $gsd=$get_se_details->fetch_array();


                    $pass = substr($gsd['fld_accountno'], -4).strtoupper(str_replace(' ', '', str_replace('-', '', str_replace('ñ', 'N', strtoupper($gsd['bill_contact_lname'])))));

                    // first_day day of the month.
                    $first_day = date('Y-m-01', strtotime($date));

                    // Last day of the month.
                    $last_day = date('Y-m-t', strtotime($date));
                    $url = "http://10.250.100.165/cicportal/PHPMailer/soc_render.php?provcode=".$r1['fld_provcode']."&statementdate=".$date."&first=".$first_day."&last=".$last_day;
                    $url_download = "http://10.250.100.165/cicportal/PHPMailer/soc_download.php?provcode=".$r1['fld_provcode']."&statementdate=".$date."&first=".$first_day."&last=".$last_day;

                    $file = 'soc_manuals/SOC'.$r1['fld_provcode'].date("Ym", strtotime($first_day)).'_manual.pdf'
            ?>
            <tr>
                <td><?php echo $gsd['fld_accountno']; ?></td>
                <td><?php echo $r1['fld_provcode']; ?></td>
                <td><?php echo $gsd['name']; ?></td>
                <td><?php echo date("F Y", strtotime($date)); ?></td>
                <td><?php echo $r1['fld_stmt_id']; ?></td>
                <td><?php echo number_format($r1['fld_beginbalance'], 2); ?></td>
                <td><?php if($r1['fld_emailsent']){echo date("F d, Y h:ia",strtotime($r1['fld_emailsent']));} else { echo "NOT SENT"; } ?></td>
                <td>
                    <!-- <a href="<?php echo $url_download; ?>" target="_blank" class="btn btn-primary"><i class="fa fa-undo"></i></a> -->
                    <!-- <a href="<?php echo $url; ?>" target="_blank" class="btn btn-success">View</a> -->
                    <a href="<?php echo 'main.php?nid=117&sid=1&rid=1&provcode='.$r1['fld_provcode'].'&statementdate='.$date.'&first='.$first_day.'&last='.$last_day.'&file='.$file; ?>" target="_blank" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                    
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