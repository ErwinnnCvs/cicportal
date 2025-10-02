<?php
// ini_set('display_errors', 0);
// ini_set('display_startup_errors', 0);
// error_reporting(E_ALL);

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
                <th ><center>Description</center></th>
                <th><center>Count</center></th>
              </tr>
            </thead>
            <tbody>
              <?php
              $warning_codes = [];
              // $sql_warning = $dbh->query("SELECT * FROM `tbquestions` WHERE `fld_question` LIKE '%WARNING%'");
              // while ($r_warning = $sql_warning->fetch_array()) {
              //   $warning_codes[] = $r_warning['fld_code'];
              // }

              $sql_errors = $dbh->query("SELECT fld_errorcode, SUM(fld_count) AS sum FROM `tbanomalous` GROUP BY fld_errorcode ORDER BY SUM(fld_count) DESC LIMIT 12");
              while ($r_errors = $sql_errors->fetch_array()) {
                if (!in_array($r_errors['fld_errorcode'], $warning_codes)) {
                  $sql_desc = $dbh->query("SELECT * FROM `tbquestions` WHERE `fld_code` LIKE '".$r_errors['fld_errorcode']."' ORDER BY `fld_fid` ASC");
                  $r_desc = $sql_desc->fetch_array();
              ?>
              <tr>
                <td><?php echo $r_desc['fld_question'];?></td>
                <td align="right"><?php echo number_format($r_errors['sum']);?></td>
              </tr>
              <?php
                }
              }
              ?>
              
            </tbody>
          </table>

        
      </div>
      <div class="col-md-2"></div>
    </div>

  </div> 
  </div>
  

</section>
<!-- /.content -->