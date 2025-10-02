<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

$selectedrating[$_POST['compliancerating']] = ' selected';

$selectedenttype[$_POST['entitytype']] = ' selected';
?>

    <!-- Main content -->
    <!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="card">
    <div class="card-header">
        <h3 class="card-title">Submissions</h3>
    </div>
    <div class="card-body">
    <form method="POST">
    <div class="row">
      <div class="col-2">
        <div>
        <label for="entitytype">Entity Type</label>
        <select class="form-control select2" name="entitytype" id="entitytype" onchange="submit()">
          <option value="all" selected>All</option>
          <?php
            $entity_type = $dbh4->query("SELECT * FROM tbenttypes;");
            while($et=$entity_type->fetch_array()){
          ?>
          <option value="<?php echo $et['fld_type']; ?>"<?php echo $selectedenttype[$et['fld_type']]; ?>><?php echo $et['fld_name']; ?></option>
          <?php
            }
          ?>
        </select>
      </div>
      </div>
      <div class="col-2">
        <!-- <div>
        <label for="compliancerating">Compliance Rating</label>
          <select class="form-control" name="compliancerating" id="compliancerating" onchange="submit()">
            <option value="all" selected>All</option>
            <?php
              $compliance_ratings = array(
                "fullycompliant"=>"Fully Compliant",
                "mostlycompliant"=>"Mostly Compliant",
                "partiallycompliant"=>"Partially Compliant",
                "minimallycompliant"=>"Minimally Compliant",
                "inactive"=>"Inactive",
              );
              foreach($compliance_ratings as $k=>$v){
            ?>
            <option value="<?php echo $k; ?>"<?php echo $selectedrating[$k]; ?>><?php echo $v; ?></option>
            <?php
              }
            ?>
          </select>
        </div> -->
      </div>
      
      <div class="col-2">
        <div>
        <br>
        <a href="main.php?nid=116&sid=0&rid=0" class="btn btn-secondary">Clear Filter</a>
        </div>
            
      </div>
            </div>

    </form>
    
    
    <br><br>
          <?php

          echo "<table class='table table-bordered table-striped table-sm dataTable dtr-inline' id='submissionstablearrival'>";
          echo "<thead>";
          echo "<tr><th bgcolor='#ffffff'>Provider Code</th><th bgcolor='#ffffff'>Company</th><th bgcolor='#ffffff'>Type</th>";
          for ($m=1; $m<=date("m"); $m++) {
           $month = date('F Y', mktime(0,0,0,$m, 1, date('Y')));
           echo "<th><center>".$month."</center></th>";
          }
          echo "<th><center>Compliance Rating</center></th>";
          echo "</tr>";
          echo "</thead>";
          echo "<tbody>";

          if($_POST['entitytype'] != "all" and $_POST['entitytype']){
            $query = " AND fld_type = '".$_POST['entitytype']."'";
          } else {
            $query = "";
          }

          $get_all_sep=$dbh4->query("SELECT fld_ctrlno, fld_type, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_provcode, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_name FROM tbentities WHERE fld_registration_type <> 1".$query." OR (fld_registration_type = 1 AND fld_noc_pass_status = 1)");
          // echo "SELECT fld_id, fld_provcode, fld_created_time FROM tbprodtickets WHERE fld_subject LIKE '%[CIC PROD]%' and fld_created_time LIKE '2023%'".$query." GROUP BY fld_provcode ORDER BY fld_created_time";
          // $sql1=$dbh->query("SELECT fld_id, fld_provcode, fld_created_time FROM tbprodtickets WHERE fld_subject LIKE '%[CIC PROD]%' and fld_created_time LIKE '2023%'".$query." GROUP BY fld_provcode ORDER BY fld_created_time");
          while($gap=$get_all_sep->fetch_array()){
              // $fd_month = date("Y-m" ,strtotime($r1['fld_created_time']));
              echo "<tr>
              <td bgcolor='#ffffff'>".$gap['fld_provcode']."</td><td bgcolor='#ffffff'>".$gap['fld_name']."</td><td bgcolor='#ffffff'>".$gap['fld_type']."</td>";
              $counter = 0;
              for ($m=1; $m<=date("m"); $m++) {
                $month_check = date('Y-m', mktime(0,0,0,$m, 1, date('Y')));
                $sql2=$dbh->query("SELECT COUNT(*) AS rcnt FROM tbprodtickets WHERE fld_provcode = '".$gap['fld_provcode']."' AND fld_subject LIKE '%[CIC PROD]%' AND fld_created_time LIKE '".$month_check."%'");
                $r2=$sql2->fetch_array();
                if($r2['rcnt'] > 0){
                  echo "<td bgcolor='#ffffff' align='right'><center>".$r2['rcnt']."</center></td>";
                } else {
                  $counter++;
                  echo "<td bgcolor='#ffffff' align='right' style='color:red;'><center>0</center></td>";
                }
                
              }

              if($counter == 0){
                $compliance_rating = "Fully Compliant";
              } elseif ($counter > 0 and $counter <= 3) {
                $compliance_rating = "Mostly Compliant";
              } elseif($counter >= 4 and $counter <= 6){
                $compliance_rating = "Partially Compliant";
              } elseif ($counter >= 7 and $counter <= 9) {
                $compliance_rating = "Minimally Compliant";
              } elseif ($counter > 9) {
                $compliance_rating = "Inactive";
              } else {
                $compliance_rating = "N/A";
              }


              echo "<td bgcolor='#ffffff'><center>".$compliance_rating."</center></td>";
              
            echo "</tr>";
          }
          echo "</tbody>";
          echo "</table>";
          ?>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  
