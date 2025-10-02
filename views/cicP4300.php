<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);



$ent2["CO"] = 'Cooperative';
$ent2["CC"] = 'Credit Card Issuer';
$ent2["GF"] = 'Government Financing';
$ent2["IS"] = 'Insurance';
$ent2["IH"] = 'Investment House';
$ent2["LS"] = 'Leasing';
$ent2["MF"] = 'Microfinance';
$ent2["OT"] = 'Others';
$ent2["PS"] = 'Pawnshop';
$ent2["PF"] = 'Private Financing';
$ent2["PLI"] = 'Private Lending Institution';
$ent2["RB"] = 'Rural Bank';
$ent2["TB"] = 'Thrift Bank';
$ent2["TE"] = 'Trust Entity';
$ent2["UB"] = 'Universal/Commercial Bank';
$ent2["UT"] = 'Utility';

 

if (!$_POST['select_tab']) {
  $_POST['select_tab'] = 1;
}
$sel[$_POST['select_tab']] = ' selected';
?>
<!-- Main content -->
<section class="content">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
          <?php
          if ($msg) {
          ?>
          <div class="alert alert-<?php echo $msg_type;?>" role="alert">
            <?php echo $msg;?>
          </div>
          <?php
          }
          ?>


          
          <?php
          if ($_POST['select_tab']) {
            ?>
            <form method="POST">
                 <div style="float:left;">
                   <select name="select_tab" class="form-select form-control" onchange="submit()">
                        <option value="1"<?php echo $sel[1];?>>Anomalous Records</option>
                        <option value="2"<?php echo $sel[2];?>>Top Errors</option>
                      </select>
                 </div>
            </form><br/><br/><br/>



            <?php
            if ($_POST['select_tab']  == '1') {
            ?>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th><center>Type</center></th>
                  <th><center>Total Errors</center></th>
                  <th><center>Total Corrected</center></th>
                </tr>
              </thead>
              <tbody>
                <?php
                $totals = [];
                $sql_total_err = $dbh->query("SELECT fld_provtypecode, SUM(fld_count) AS total FROM `tbanomalous` GROUP BY fld_provtypecode ORDER BY SUM(fld_count) DESC");

                $sql_total_cor = $dbh->query("SELECT fld_provtypecode, SUM(fld_count) AS total FROM `tbanomalous` WHERE fld_status = 'ANN' GROUP BY fld_provtypecode ORDER BY SUM(fld_count) DESC");
                while ($r_total_cor = $sql_total_cor->fetch_array()) {
                  $totals[$r_total_cor['fld_provtypecode']]["total_cor"] = $r_total_cor['total'];
                }
                while ($r_total_err = $sql_total_err->fetch_array()) {
                ?>
                <tr>
                  <td><?php echo $ent2[$r_total_err['fld_provtypecode']];?></td>
                  <td align="right"><?php echo number_format($r_total_err['total']);?></td>
                  <td align="right"><?php echo number_format($totals[$r_total_err['fld_provtypecode']]["total_cor"]);?></td>
                </tr>
                <?php
                }
                ?>
                
              </tbody>
            </table>



            <?php
            }elseif ($_POST['select_tab']  == '2') {
            ?>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th ><center>Description</center></th>
                  <th><center>Count</center></th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sql_errors = $dbh->query("SELECT fld_errorcode, SUM(fld_count) AS sum FROM `tbanomalous` GROUP BY fld_errorcode ORDER BY SUM(fld_count) DESC LIMIT 12");
                while ($r_errors = $sql_errors->fetch_array()) {
                  $sql_desc = $dbh1->query("SELECT * FROM `tbquestions` WHERE `fld_code` LIKE '".$r_errors['fld_errorcode']."' ORDER BY `fld_fid` ASC");
                  $r_desc = $sql_desc->fetch_array();
                ?>
                <tr>
                  <td><?php echo $r_desc['fld_question'];?></td>
                  <td><?php echo number_format($r_errors['sum']);?></td>
                </tr>
                <?php
                }
                ?>
                
              </tbody>
            </table>



            <?php
            }
          }
          ?>
          


          
          
          <?php

          

          

          ?>

          
        </div>
        <div class="col-md-2"></div>
      </div>
      
    </div>
  </div>
  

</section>
<!-- /.content -->