<?php

$submission_type = array("REGULAR SUBMISSION", "CORRECTION FILE", "DISPUTE", "HISORICAL DATA", "EXTENDED REGULAR SUBMISSION", "LATE SUBMISSION");


if(!$_POST['sel_type']){
    $_POST['sel_type'] = 'CC';
}

$selectedtype[$_POST['sel_type']] = " selected";

$selectedassoc[$_POST['sel_assoc']] = " selected";

if (!$_POST['sel_year']) {
  $_POST['sel_year'] = date("Y");
}

$selectedyear[$_POST['sel_year']] = " selected";

$selectdse[$_POST['sel_se']] = " selected";

$years_arr = array_combine(range(date("Y"), 2023), range(date("Y"), 2023));

function calculate_median($arr) {
    $count = count($arr); //total numbers in array
    $middleval = floor(($count-1)/2); // find the middle value, or the lowest middle value
    if($count % 2) { // odd number, middle is the median
        $median = $arr[$middleval];
    } else { // even number, calculate avg of 2 medians
        $low = $arr[$middleval];
        $high = $arr[$middleval+1];
        $median = (($low+$high)/2);
    }
    return $median;
}

function calculate_average($arr) {
    $count = count($arr); //total numbers in array
    foreach ($arr as $value) {
        $total = $total + $value; // total value of array numbers
    }
    $average = ($total/$count); // get average value
    return $average;
}

?>

<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-body" style="position: relative; overflow: auto;">
    <form method="post">
      <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Select Type</label>
                    <select class="form-control select2" name="sel_type" id="sel_type" style="width: 100%;" onchange="submit()">
                        <option value="all">All</option>
                        <?php
                            $get_all_se_types = $dbh4->query("SELECT SUBSTRING(AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))), 1, 2) AS types FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) <> '' GROUP BY types ORDER BY types");
                            while($gast=$get_all_se_types->fetch_array()){
                                echo "<option value='".$gast['types']."'".$selectedtype[$gast['types']].">".$gast['types']." - ".$ent2[$gast['types']]."</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                    <label>Select Year</label>
                    <select class="form-control select2" name="sel_year" id="sel_year" style="width: 100%;" onchange="submit()">
                        <?php
                          foreach ($years_arr as $year) {
                            echo "<option value='".$year."'".$selectedyear[$year].">".$year."</option>";
                          }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
              <div class="form-group" data-select2-id="29">
                <label>Search Submitting Entity</label>
                <select class="form-control select2" name="sel_se" id="sel_se" style="width: 100%;" onchange="submit()">
                  <option disabled selected>----SELECT----</option>
                <?php
                  if ($_POST['sel_type'] != 'all') {
                    $query = "a.fld_type = '".$_POST['sel_type']."' AND a.fld_registration_type <> 1 OR (a.fld_type = '".$_POST['sel_type']."' AND a.fld_registration_type = 1 AND a.fld_noc_pass_status = 1)";
                  } else {
                    $query = "a.fld_registration_type <> 1 OR (a.fld_registration_type = 1 AND a.fld_noc_pass_status = 1)";
                  }
                  $get_all_seps_sub=$dbh4->query("SELECT a.fld_ctrlno, a.fld_type, AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) as fld_provcode, AES_DECRYPT(a.fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_name, b.fld_date FROM tbentities a RIGHT JOIN tbsep b ON AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) = b.fld_provcode  WHERE ".$query);
                  while ($gass=$get_all_seps_sub->fetch_array()) {
                    echo "<option value='".$gass['fld_provcode']."'".$selectdse[$gass['fld_provcode']].">".$gass['fld_provcode']." - ".$gass['fld_name']."</option>";
                  }
                ?>
                </select>
              </div>
            </div>
        </div>
        <!-- <div class="row">
          <div class="col-md-3">
                <div class="form-group">
                    <label>Select Association</label>
                    <select class="form-control select2" name="sel_assoc" id="sel_assoc" style="width: 100%;" onchange="submit()">
                        <option selected disabled>---SELECT---</option>
                        <?php
                            $get_all_se_types = $dbh4->query("SELECT fld_code, fld_name FROM tbseassociations;");
                            while($gast=$get_all_se_types->fetch_array()){
                                echo "<option value='".$gast['fld_code']."'".$selectedassoc[$gast['fld_code']].">".$gast['fld_code']." - ".$gast['fld_name']."</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div> -->
      </form>
     <table id="example1" class="table table-bordered table-hover table-striped" cellspacing="0" style="margin: auto; width: 600px; position: relative; overflow: auto; border: 1px solid black;">
       <thead>
         <tr>
           <th rowspan="2"><center>#</center></th>
           <th rowspan="2" style="position: -webkit-sticky; position: sticky; width: 100px; min-width: 100px; max-width: 120px; left: 0px; background-color: white;">Provider Code</th>
           <th rowspan="2" style="position: -webkit-sticky; position: sticky; width: 150px; min-width: 150px; max-width: 150px; left: 104px; background-color: white;">Submitting Entity</th>
           <?php
            for ($m=1; $m<=12; $m++) {
              $month = date('F Y', mktime(0,0,0,$m, 1, $_POST['sel_year']));
           ?>
           <th colspan="6" style="border-right: 1px solid black;"><center><?php echo $month; ?></center></th>

           <?php
            }
           ?>
         </tr>
         <tr>
          <?php
            for ($m=1; $m<=12; $m++) {
              $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
           ?>
           <!-- <th colspan="5"><?php echo $month; ?></th> -->

           <th><center>RS</center></th>
           <th><center>ERS</center></th>
           <th><center>CF</center></th>
           <th><center>HD</center></th>
           <th><center>DF</center></th>
           <th style="border-right: 1px solid black;"><center>RSL</center></th>
           <?php
            }
           ?>
           </tr>
       </thead>
       <tbody>
        <?php
          $total_rs = [];
          $total_ers = [];
          $total_cf = [];
          $total_hd = [];
          $total_df = [];
          $total_rsl = [];
          $counter = 1;

          if ($_POST['sel_type'] != 'all' and !$_POST['sel_assoc']) {
            if (!$_POST['sel_se']) {
              $query = "a.fld_type = '".$_POST['sel_type']."' AND a.fld_registration_type <> 1 OR (a.fld_type = '".$_POST['sel_type']."' AND a.fld_registration_type = 1 AND a.fld_noc_pass_status = 1)";
            } else {
              $query = "AES_DECRYPT(a.fld_provcode, MD5(CONCAT(a.fld_ctrlno, 'RA3019'))) = '".$_POST['sel_se']."' AND a.fld_type = '".$_POST['sel_type']."' AND a.fld_registration_type <> 1 OR (AES_DECRYPT(a.fld_provcode, MD5(CONCAT(a.fld_ctrlno, 'RA3019'))) = '".$_POST['sel_se']."' AND a.fld_type = '".$_POST['sel_type']."' AND a.fld_registration_type = 1 AND a.fld_noc_pass_status = 1)";
            }
          } elseif($_POST['sel_type'] and !$_POST['sel_assoc']) {
            if (!$_POST['sel_se']) {
              $query = "a.fld_registration_type <> 1 OR (a.fld_registration_type = 1 AND a.fld_noc_pass_status = 1)";
            } else {
              $query = "AES_DECRYPT(a.fld_provcode, MD5(CONCAT(a.fld_ctrlno, 'RA3019'))) = '".$_POST['sel_se']."' AND a.fld_registration_type <> 1 OR (AES_DECRYPT(a.fld_provcode, MD5(CONCAT(a.fld_ctrlno, 'RA3019'))) = '".$_POST['sel_se']."' AND a.fld_registration_type = 1 AND a.fld_noc_pass_status = 1)";
            }
          } elseif($_POST['sel_assoc']){
            $query = "a.fld_assoc_code = '".$_POST['sel_assoc']."' AND a.fld_registration_type <> 1 OR (a.fld_assoc_code = '".$_POST['sel_assoc']."' AND a.fld_registration_type = 1 AND a.fld_noc_pass_status = 1)";

          }
          $get_all_seps_sub=$dbh4->query("SELECT a.fld_ctrlno, a.fld_type, AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) as fld_provcode, AES_DECRYPT(a.fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_name, b.fld_referencedate FROM tbentities a LEFT JOIN tbsubmissiondetails b ON AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) = b.fld_provcode  WHERE ".$query. " GROUP BY b.fld_provcode");
          while ($gass=$get_all_seps_sub->fetch_array()) {
        ?>
         <tr>
           <td><?php echo $counter++; ?></td>
           <td style="position: -webkit-sticky; position: sticky; width: 100px; min-width: 100px; max-width: 100px; left: 0px; background-color: white;"><?php echo $gass['fld_provcode']; ?></td>
           <td style="position: -webkit-sticky; position: sticky; width: 150px; min-width: 150px; max-width: 150px; left: 104px; background-color: white;"><?php echo $gass['fld_name']; ?></td>
           <?php
            for ($m=1; $m<=12; $m++) {
              $date = date('Y-m-d', mktime(0,0,0,$m, 1, $_POST['sel_year']));
              $expldate = explode("-", $date);

              $year = $expldate[0];
              $month = $expldate[1];
           ?>
           <td>
             <center>
               <?php

                  $get_rs = $dbh4->query("SELECT COUNT(fld_filename) as cnt_rs FROM tbtransmittal WHERE fld_provcode = '".$gass['fld_provcode']."' and YEAR(fld_date_covered) = ".$year." AND MONTH(fld_date_covered) = ".$month." AND fld_trans_type = 1");
                  $grs=$get_rs->fetch_array();

                  $total_rs[$date] += ($grs['cnt_rs'] > 0 ? $grs['cnt_rs'] : 0);
                  if ($grs['cnt_rs'] > 0) {
                    echo "<a href='main.php?nid=135&sid=1&rid=0&provcode=".$gass['fld_provcode']."&month=".$date."&subtype=rs' target='_blank'>".$grs['cnt_rs']."</a>";
                  } else {
                    echo "0";
                  }
               ?>
             </center>
           </td>
           <td>
             <center>
               <?php

                  $get_ers = $dbh4->query("SELECT COUNT(fld_filename) as cnt_ers FROM tbtransmittal WHERE fld_provcode = '".$gass['fld_provcode']."' and YEAR(fld_date_covered) = ".$year." AND MONTH(fld_date_covered) = ".$month." AND fld_trans_type = 5");
                  $gers=$get_ers->fetch_array();

                  $total_ers[$date] += ($gers['cnt_ers'] > 0 ? $gers['cnt_ers'] : 0);

                  if ($gers['cnt_ers'] > 0) {
                    echo "<a href='main.php?nid=135&sid=1&rid=0&provcode=".$gass['fld_provcode']."&month=".$date."&subtype=ers' target='_blank'>".$gers['cnt_ers']."</a>";
                  } else {
                    echo "0";
                  }
               ?>
             </center>
           </td>
           <td>
             <center>
               <?php

                  $get_cf = $dbh4->query("SELECT COUNT(fld_filename) as cnt_cf FROM tbtransmittal WHERE fld_provcode = '".$gass['fld_provcode']."' and YEAR(fld_date_covered) = ".$year." AND MONTH(fld_date_covered) = ".$month." AND fld_trans_type = 2");
                  $gcf=$get_cf->fetch_array();

                  $total_cf[$date] += ($gcf['cnt_cf'] > 0 ? $gcf['cnt_cf'] : 0);

                  if ($gcf['cnt_cf'] > 0) {
                    echo "<a href='main.php?nid=135&sid=1&rid=0&provcode=".$gass['fld_provcode']."&month=".$date."&subtype=cf' target='_blank'>".$gcf['cnt_cf']."</a>";
                  } else {
                    echo "0";
                  }
               ?>
             </center>
           </td>
           <td>
             <center>
               <?php

                  $get_hd = $dbh4->query("SELECT COUNT(fld_filename) as cnt_hd FROM tbtransmittal WHERE fld_provcode = '".$gass['fld_provcode']."' and YEAR(fld_date_covered) = ".$year." AND MONTH(fld_date_covered) = ".$month." AND fld_trans_type = 4");
                  $ghd=$get_hd->fetch_array();

                  $total_hd[$date] += ($ghd['cnt_hd'] > 0 ? $ghd['cnt_hd'] : 0);

                  if ($ghd['cnt_hd'] > 0) {
                    echo "<a href='main.php?nid=135&sid=1&rid=0&provcode=".$gass['fld_provcode']."&month=".$date."&subtype=hd' target='_blank'>".$ghd['cnt_hd']."</a>";
                  } else {
                    echo "0";
                  }
               ?>
             </center>
           </td>
           <td>
             <center>
               <?php

                  $get_df = $dbh4->query("SELECT COUNT(fld_filename) as cnt_df FROM tbtransmittal WHERE fld_provcode = '".$gass['fld_provcode']."' and YEAR(fld_date_covered) = ".$year." AND MONTH(fld_date_covered) = ".$month." AND fld_trans_type = 3");
                  $gdf=$get_df->fetch_array();

                  $total_df[$date] += ($gdf['cnt_df'] > 0 ? $gdf['cnt_df'] : 0);

                  if ($gdf['cnt_df'] > 0) {
                    echo "<a href='main.php?nid=135&sid=1&rid=0&provcode=".$gass['fld_provcode']."&month=".$date."&subtype=df' target='_blank'>".$gdf['cnt_df']."</a>";
                  } else {
                    echo "0";
                  }
               ?>
             </center>
           </td>
           <td style="border-right: 1px solid black;">
             <center>
               <?php

                  $get_rsl = $dbh4->query("SELECT COUNT(fld_filename) as cnt_rsl FROM tbtransmittal WHERE fld_provcode = '".$gass['fld_provcode']."' and YEAR(fld_date_covered) = ".$year." AND MONTH(fld_date_covered) = ".$month." AND fld_trans_type = 6");
                  $grsl=$get_rsl->fetch_array();

                  $total_rsl[$date] += ($grsl['cnt_rsl'] > 0 ? $grsl['cnt_rsl'] : 0);

                  if ($grsl['cnt_rsl'] > 0) {
                    echo "<a href='main.php?nid=135&sid=1&rid=0&provcode=".$gass['fld_provcode']."&month=".$date."&subtype=ls' target='_blank'>".$grsl['cnt_rsl']."</a>";
                  } else {
                    echo "0";
                  }
               ?>
             </center>
           </td>
           <?php
            }
           ?>
         </tr>
         <?php
          }
         ?>
         <tfoot>
          <tr>
              <td></td>
              <td style="position: -webkit-sticky; position: sticky; width: 100px; min-width: 100px; max-width: 120px; left: 0px; background-color: white;"></td>
              <td style="position: -webkit-sticky; position: sticky; width: 150px; min-width: 150px; max-width: 150px; left: 104px; background-color: white;"><b>TYPES</b></td>
              <?php
              for ($m=1; $m<=12; $m++) {
                $date = date('Y-m-d', mktime(0,0,0,$m, 1, $_POST['sel_year']));
                $expldate = explode("-", $date);

                $year = $expldate[0];
                $month = $expldate[1];
             ?>
             <td>
              <b>
               <center>RS</center>
               </b>
             </td>
             <td>
              <b>
                <center>
                  ERS
                </center>
              </b>
             </td>
             <td>
              <b>
                <center>
                  CF
                </center>
              </b>
             </td>
             <td>
              <b>
                <center>
                  HD
                </center>
              </b>
             </td>
             <td>
              <b>
                <center>
                  DF
                </center>
              </b>
             </td>
             <td style="border-right: 1px solid black;">
              <b>
                <center>
                  RSL
                </center>
             </td>
             <?php
              }
             ?>
           </tr>
           <tr>
              <td></td>
              <td style="position: -webkit-sticky; position: sticky; width: 100px; min-width: 100px; max-width: 120px; left: 0px; background-color: white;"></td>
              <td style="position: -webkit-sticky; position: sticky; width: 150px; min-width: 150px; max-width: 150px; left: 104px; background-color: white;"><b>TOTAL</b></td>
              <?php
              $overall_total = [];
              for ($m=1; $m<=12; $m++) {
                $date = date('Y-m-d', mktime(0,0,0,$m, 1, $_POST['sel_year']));
                $expldate = explode("-", $date);

                $year = $expldate[0];
                $month = $expldate[1];
             ?>
             <td>
              <center>
                
              <b>
               <?php
                echo $total_rs[$date];
               ?>
               </b>
              </center>
             </td>
             <td>
              <center>  
              <b>
                <?php
                  echo $total_ers[$date];
                ?>
              </b>
              </center>
             </td>
             <td>
              <center>
                
              <b>
                <?php
                  echo $total_cf[$date];
                ?>
              </b>
              </center>
             </td>
             <td>
              <center>
                
              <b>
                <?php
                  echo $total_hd[$date];
                ?>
              </b>
              </center>
             </td>
             <td>
              <center>
                
              <b>
                <?php
                  echo $total_df[$date];
                ?>
              </b>
              </center>
             </td>
             <td style="border-right: 1px solid black;">
              <center>
                
              <b>
                <?php
                  echo $total_rsl[$date];
                ?>
              </b>
              </center>
             </td>
             <?php
              $overall_total[$date] += $total_rs[$date] + $total_ers[$date] + $total_cf[$date] + $total_hd[$date] + $total_df[$date] + $total_rsl[$date];
              $arr_totals[$date] = array($total_rs[$date], $total_ers[$date], $total_cf[$date], $total_hd[$date], $total_df[$date], $total_rsl[$date]);
              }
             ?>
           </tr>
           <tr>
              <td></td>
             <td style="position: -webkit-sticky; position: sticky; width: 100px; min-width: 100px; max-width: 120px; left: 0px; background-color: white;"></td>
             <td style="position: -webkit-sticky; position: sticky; width: 150px; min-width: 150px; max-width: 150px; left: 104px; background-color: white;"><b>OVERALL TOTAL</b></td>
             <?php
              for ($m=1; $m<=12; $m++) {
                $date = date('Y-m-d', mktime(0,0,0,$m, 1, $_POST['sel_year']));
                $expldate = explode("-", $date);

                $year = $expldate[0];
                $month = $expldate[1];
             ?>
             <td colspan="6" style="border-right: 1px solid black;"><center>
               <?php echo $overall_total[$date]; ?>
             </center></td>
             <?php
              }
             ?>
           </tr>
           <tr>
              <td></td>
             <td style="position: -webkit-sticky; position: sticky; width: 100px; min-width: 100px; max-width: 120px; left: 0px; background-color: white;"></td>
             <td style="position: -webkit-sticky; position: sticky; width: 150px; min-width: 150px; max-width: 150px; left: 104px; background-color: white;"><b>MEDIAN</b></td>
             <?php
              for ($m=1; $m<=12; $m++) {
                $date = date('Y-m-d', mktime(0,0,0,$m, 1, $_POST['sel_year']));
                $expldate = explode("-", $date);

                $year = $expldate[0];
                $month = $expldate[1];
             ?>
             <td colspan="6" style="border-right: 1px solid black;"><center>
               <?php
               echo "ACTUAL: ".calculate_median($arr_totals[$date])."<br>";
                echo "ROUNDED OFF: ".round(calculate_median($arr_totals[$date]));
              ?>
             </center></td>
             <?php
              }
             ?>
           </tr>
           <tr>
            <td></td>
             <td style="position: -webkit-sticky; position: sticky; width: 100px; min-width: 100px; max-width: 120px; left: 0px; background-color: white;"></td>
             <td style="position: -webkit-sticky; position: sticky; width: 150px; min-width: 150px; max-width: 150px; left: 104px; background-color: white;"><b>AVERAGE</b></td>
             <?php
              for ($m=1; $m<=12; $m++) {
                $date = date('Y-m-d', mktime(0,0,0,$m, 1, $_POST['sel_year']));
                $expldate = explode("-", $date);

                $year = $expldate[0];
                $month = $expldate[1];
             ?>
             <td colspan="6" style="border-right: 1px solid black;"><center>
               <?php
               echo "ACTUAL: ".round(calculate_average($arr_totals[$date]), 2)."<br>";
                echo "ROUNDED OFF: ".round(calculate_average($arr_totals[$date]));
              ?>
             </center></td>
             <?php
              }
             ?>
           </tr>
           <tr>
            <td></td>
             <td style="position: -webkit-sticky; position: sticky; width: 100px; min-width: 100px; max-width: 120px; left: 0px; background-color: white;"></td>
             <td style="position: -webkit-sticky; position: sticky; width: 150px; min-width: 150px; max-width: 150px; left: 104px; background-color: white;"><b></b></td>
             <?php
              for ($m=1; $m<=12; $m++) {
                $date = date('Y-m-d', mktime(0,0,0,$m, 1, $_POST['sel_year']));
                $month = date('F Y', mktime(0,0,0,$m, 1, $_POST['sel_year']));
             ?>
             <td colspan="6" style="border-right: 1px solid black;"><center>
              <b>
               <?php
               echo $month;
              ?>
              </b>
             </center></td>
             <?php
              }
             ?>
           </tr>
         </tfoot>
       </tbody>
     </table>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->