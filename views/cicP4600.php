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
          <table class="table table-bordered table-striped">
              <thead>
                <tr style="background-color: #4472c4; color: white;">
                  <th><center>Type</center></th>
                  <th><center>Total Errors</center></th>
                  <th><center>Total Corrected</center></th>
                </tr>
              </thead>
              <tbody>
                <?php
                $totals = [];
                $sql_total_err = $dbh->query("SELECT fld_provtypecode, SUM(fld_count) AS total FROM `tbanomalous` WHERE fld_provtypecode NOT IN ('OT') GROUP BY fld_provtypecode ORDER BY SUM(fld_count) DESC");

                $sql_total_cor = $dbh->query("SELECT fld_provtypecode, SUM(fld_count) AS total FROM `tbanomalous` WHERE fld_status = 'ANN' GROUP BY fld_provtypecode ORDER BY SUM(fld_count) DESC");
                while ($r_total_cor = $sql_total_cor->fetch_array()) {
                  $totals[$r_total_cor['fld_provtypecode']]["total_cor"] = $r_total_cor['total'];
                }
                $t_errors = $t_corrected = 0;
                while ($r_total_err = $sql_total_err->fetch_array()) {
                  $t_errors = $t_errors + $r_total_err['total'];
                  $t_corrected = $t_corrected + $totals[$r_total_err['fld_provtypecode']]["total_cor"];
                ?>
                <tr>
                  <td><?php echo $ent2[$r_total_err['fld_provtypecode']];?></td>
                  <td align="right"><?php echo number_format($r_total_err['total']);?></td>
                  <td align="right"><?php echo number_format($totals[$r_total_err['fld_provtypecode']]["total_cor"]);?></td>
                </tr>
                <?php
                }
                ?>
                <tr>
                  <td><b>TOTAL</b></td>
                  <td align="right"><b><?php echo number_format($t_errors);?></b></td>
                  <td align="right"><b><?php echo number_format($t_corrected);?></b></td>
                </tr>
              </tbody>
            </table>

          
        </div>
        <div class="col-md-2"></div>
      </div>
      
    </div>
  </div>
  

</section>
<!-- /.content -->