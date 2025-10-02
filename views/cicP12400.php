<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

$selectedrating[$_POST['compliancerating']] = ' selected';

if (!$_POST['entitytype']) {
  $_POST['entitytype'] = 'CC';
}

$selectedenttype[$_POST['entitytype']] = ' selected';

?>

    <!-- Main content -->
    <!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="card">
    <div class="card-header">
        <h3 class="card-title">Transmittals Monitoring</h3>
    </div>
    <div class="card-body">
    <form method="POST">
    <div class="row">

      <div class="col-3">
          <div class="form-group"> 
              <label class="col-form-label" for="entitytype">Entity Type</label>
              <select class="form-control select2" name="entitytype" id="entitytype" onchange="submit()">
                <option value="all" selected>All</option>
                <?php
              
                  $start = date($filterYear."-01");
                  $end = date($filterYear."-12-");
                  $transType = 1;
                  $filterYear = "2024";
                  
                  if(isset($_POST['transType'])){
                    $transType = $_POST['transType'];
                  } 
                    if(isset($_POST['filterYear'])){
                      $filterYear = $_POST['filterYear'];
                  } 


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

      
      <div class="col-lg-4">
            <div class="form-group">
                <label class="col-form-label">Filter Submission Type</label>
          
                    <select class="custom-select transType" name="transType" id="transType" onchange="submit()"  value="<?php echo $_POST['transType']?>">
                        <!-- <option value=""  disabled="">Select Submission Type</option> -->
                       <option value="1" selected=""  <?php if($_POST['transType'] == 1){echo "selected='selected'";}  ?>>Regular Submission</option>
                        <option value="5" <?php if($_POST['transType'] == 5){echo "selected='selected'";} ?>>Extended Regular Submission</option>
                        <option value="6" <?php if($_POST['transType'] == 6){echo "selected='selected'";} ?>>Regular Submission - Late</option>
                        <option value="2" <?php if($_POST['transType'] == 2){echo "selected='selected'";} ?>>Special Submission - Correction File</option>
                        <option value="3" <?php if($_POST['transType'] == 3){echo "selected='selected'";} ?>>Special Submission - Dispute</option>
                        <option value="4" <?php if($_POST['transType'] == 4){echo "selected='selected'";} ?>>Special Submission - Historical Data</option>

                         <option value="7" <?php if($_POST['transType'] == 7){echo "selected='selected'";} ?>>Lapsed Regular Submission</option>
                        
                        
                    </select>
            </div>
        </div>
        <?php 
     
        ?>

        <div class="col-lg-2">
            <div class="form-group">
                <label class="col-form-label">Filter Year</label>
          
                <select class="custom-select filterYear" name="filterYear" id="filterYear" onchange="submit()"  value="<?php echo $_POST['filterYear']?>">
                                <!-- <option value=""  disabled="">Select Submission Type</option> -->      
                                    <option><?php if(isset($_POST['filterYear'])){print_r($_POST['filterYear']);}else{print_r(date("Y"));} ?></option>
                                    <?php
                                    
                                        $y=(int)date('Y');
                                        ?>
                                        <option value="<?php echo $y;?>" ><?php echo $y;?></option>
                                            <?php
                                            $y--;
                                        for(; $y>'2022'; $y--)
                                        {
                                    ?>
                                    <option value="<?php echo $y;?>"><?php echo $y;?></option>
                                    <?php }?>
                                </select>
            </div>
        </div>

      <div class="col-2">
        <div class="form-group pt-2">
          <br>
          <a href="main.php?nid=126&sid=0&rid=0" class="btn btn-secondary">Clear Filter</a>
          </div>
            
      </div>
      <div class="col-2">
        <div>
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
        </div>
      </div>
      
     

    </div>

    </form>
    
    
    <br><br>
          <?php

          echo "<table class='table table-bordered  table-sm dataTable dtr-inline' id='submissionstablearrival'>";
          echo "<thead>";
          echo "<tr><th bgcolor='#ffffff'>Provider Code</th><th bgcolor='#ffffff'>Company</th><th bgcolor='#ffffff'>Type</th>";
          for ($m=date($filterYear."-01"); $m<=date($filterYear."-12"); $m=date("Y-m",strtotime($m."+1 month"))) {
           
           echo "<th><center>".date_format(new DateTime($m), "F Y")."</center></th>";
          }
          echo "<th><center>Onboarded Date</center></th>";
          echo "<th><center>Missed Months</center></th>";
          echo "<th><center>Compliance Rating</center></th>";
          echo "</tr>";
          echo "</thead>";
          echo "<tbody>";

          if($_POST['entitytype'] != 'all'){
            $query = " AND (fld_type = '".$_POST['entitytype']."' OR AES_DECRYPT(a.fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) LIKE '%".$_POST['entitytype']."%' OR fld_secondary_type = '".$_POST['entitytype']."')";
          } elseif($_POST['entitytype'] == 'all') {
            $query = "";
          }

          $transType = 1;


          if(isset($_POST['transType'])){
              $transType = $_POST['transType'];
          } 

          $comrating = array();


          // echo "SELECT a.fld_ctrlno, a.fld_type, AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) as fld_provcode, AES_DECRYPT(a.fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_name FROM tbentities a LEFT JOIN tbsubmissiondetails b ON AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) = b.fld_provcode  WHERE a.fld_registration_type <> 1".$query." OR (a.fld_registration_type = 1 AND a.fld_noc_pass_status = 1".$query." ) GROUP BY fld_provcode";
          $get_all_seps_sub=$dbh4->query("SELECT a.fld_ctrlno, a.fld_type, AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) as fld_provcode, AES_DECRYPT(a.fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_name FROM tbentities a LEFT JOIN tbsubmissiondetails b ON AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) = b.fld_provcode  WHERE a.fld_registration_type <> 1".$query." OR (a.fld_registration_type = 1 AND a.fld_noc_pass_status = 1".$query." ) GROUP BY b.fld_provcode");
          while ($gass=$get_all_seps_sub->fetch_array()) {
            if($_POST['compliancerating'] == 'all' || !$_POST['compliancerating'] || empty($_POST['compliancerating'])){
              array_push($comrating, $gass['fld_ctrlno']);
              reset($comrating);
            } else {
              
              //Count submission with transmittal each month with date format (YYYY-MM) on tbtransmittal
              $get_submitted_records_by_months = $dbh4->query("SELECT fld_date_covered, COUNT(*) FROM tbtransmittal WHERE fld_provcode = '".$gass['fld_provcode']."' and fld_date_covered LIKE '".$filterYear."%' and fld_trans_type = ".$_POST['transType']." GROUP BY DATE_FORMAT(fld_date_covered, '%Y-%m'  )");
              $gsrbm=$get_submitted_records_by_months->fetch_array();
              reset($comrating);
              $rowcount = mysqli_num_rows( $get_submitted_records_by_months );

              $missed_months_submitted = 12 - $rowcount;

              if($missed_months_submitted == 0 and $_POST['compliancerating'] == 'fullycompliant'){
                array_push($comrating, $gass['fld_ctrlno']);

              } elseif ($missed_months_submitted > 0 and $missed_months_submitted <= 3 and $_POST['compliancerating'] == 'mostlycompliant') {
                array_push($comrating, $gass['fld_ctrlno']);
              } elseif($missed_months_submitted >= 4 and $missed_months_submitted <= 6 and $_POST['compliancerating'] == 'partiallycompliant'){
                array_push($comrating, $gass['fld_ctrlno']);
              } elseif ($missed_months_submitted >= 7 and $missed_months_submitted <= 9 and $_POST['compliancerating'] == 'minimallycompliant') {
                array_push($comrating, $gass['fld_ctrlno']);
              } elseif ($missed_months_submitted > 9 and $_POST['compliancerating'] == 'inactive') {
                array_push($comrating, $gass['fld_ctrlno']);
              } else {

              }
            }
          }

        
          $get_all_sep=$dbh4->query("SELECT a.fld_ctrlno, a.fld_type, AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) as fld_provcode, AES_DECRYPT(a.fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_name, b.fld_referencedate FROM tbentities a LEFT JOIN tbsubmissiondetails b ON AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) = b.fld_provcode  WHERE a.fld_ctrlno IN (".implode(",", $comrating).") and a.fld_registration_type <> 1".$query." OR a.fld_ctrlno IN (".implode(",", $comrating).") and (a.fld_registration_type = 1 AND a.fld_noc_pass_status = 1".$query." ) GROUP BY b.fld_provcode");
  
          $c = 0;
          while($gap=$get_all_sep->fetch_array()){

             $c++;

              if ($gap['fld_referencedate'] and date("Y", strtotime($gap['fld_referencedate'])) == $filterYear ){
              $date1 = $gap['fld_referencedate'];
              $date2 = $filterYear.'-12-31';

              $ts1 = strtotime($date1);
              $ts2 = strtotime($date2);

              $year1 = date('Y', $ts1);
              $year2 = date('Y', $ts2);

              $month1 = date('m', $ts1);
              $month2 = date('m', $ts2);

              $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
              } else {
                $date1 = $filterYear."-01-01";
                $date2 = $filterYear.'-12-31';
                $diff = 12;
              }
       
              $get_submitted_records_by_months = $dbh4->query("SELECT fld_date_covered, COUNT(*) FROM tbtransmittal WHERE fld_provcode = '".$gap['fld_provcode']."' and (fld_date_covered >= '".date("Y-m-01", strtotime($date1))."' and fld_date_covered <= '".$date2."') and fld_trans_type = ".$_POST['transType']." GROUP BY DATE_FORMAT(fld_date_covered, '%Y-%m')");
              // $gsrbm=$get_submitted_records_by_months->fetch_array();


              // echo $gap['fld_provcode']. " " .$gap['fld_name']. " - " .$diff."<br>";
              $rowcount = mysqli_num_rows( $get_submitted_records_by_months );


              // echo "DIFF: ".$diff. "; ROWCOUNT: ".$rowcount. "; SE NAME: ".$gap['fld_name']."<br>"; 

              if($rowcount > $diff) {
                  $missed_months_submitted = 0;
              } else {
                  $missed_months_submitted = $diff - $rowcount;
              }
              if($missed_months_submitted == 0){
                $compliance_rating = "Fully Compliant";
                $color = "text-success";
                $dataOrder = 'data-order = "1" ';
                $reduction_fee = 100;
              } elseif ($missed_months_submitted > 0 and $missed_months_submitted <= 3) {
                $compliance_rating = "Mostly Compliant";
                $color = "text-info";
                $dataOrder = 'data-order = "2" ';
                $reduction_fee = 75;
              } elseif($missed_months_submitted >= 4 and $missed_months_submitted <= 6){
                $compliance_rating = "Partially Compliant";
                $color = "text-primary";
                $dataOrder = 'data-order = "3" ';
                $reduction_fee = 50;
              } elseif ($missed_months_submitted >= 7 and $missed_months_submitted <= 9) {
                $compliance_rating = "Minimally Compliant";
                $color = "text-warning"; 
                $dataOrder = 'data-order = "4" ';
                $reduction_fee = 25;     
              } elseif ($missed_months_submitted > 9) {
                $compliance_rating = "Inactive";
                $color = "text-danger";
                $dataOrder = 'data-order = "5" ';
                $reduction_fee = 0;
              } else {
                // echo $rowcount."<br>";
                $compliance_rating = "N/A";
                // $color = "text-success";
                $dataOrder = 'data-order = "1" ';
                // $reduction_fee = 100;
              }

              if($gap['fld_referencedate'] != '0000-00-00'){
                $date_onboarded = $gap['fld_referencedate'];
              } else {
                $date_onboarded = 'NA';
              }

              echo "<tr>
              <td bgcolor='#ffffff'>".$gap['fld_provcode']."</td><td bgcolor='#ffffff'>".$gap['fld_name']."</td><td bgcolor='#ffffff'>".$gap['fld_type']."</td>";
              $counter = 0;
              for ($m=date($filterYear."-01"); $m<=date($filterYear."-12"); $m=date("Y-m",strtotime($m."+1 month"))) {
                $month_check = $m;
              
                $sql2=$dbh4->query("SELECT COUNT(*) AS rcnt FROM tbtransmittal WHERE fld_provcode = '".$gap['fld_provcode']."' and fld_date_covered LIKE '".$month_check."%' and fld_trans_type = '".$transType ."' ");

                $r2=$sql2->fetch_array();
             
                if($r2['rcnt'] > 0){
                  echo "<td bgcolor='#ffffff' align='right'><center>"."<a href='main.php?nid=124&sid=1&rid=1&enc=".base64_encode($gap['fld_provcode'])."&dm=".base64_encode($month_check)."&st=".base64_encode($transType)."'>".$r2['rcnt']."</a> </center></td>";

                } else {
                  $counter++;
                  echo "<td bgcolor='#ffffff' align='right' style='color:red;'><center>0</center></td>";
                }
               
                
              }

          
              echo "<td><center>".$date_onboarded."</center></td>";
              echo "<td class='$color'><center>".$missed_months_submitted."</center></td>";
              echo "<td $dataOrder class='$color'><center>".$compliance_rating."</center></td>";
              
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
  
