<?php

	require_once 'classes/Auth.class.php';

	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);
	error_reporting(E_ALL);

	$timestamp = date("Y-m-d H:i:s");
    $counter = 1;
    $transType = 1;
    $filterYear = "2024";   
    // $startMonth = date("2023-01");
    // $lastMonth = date("2023-12");
    // $start = date("2023-01");
    // $end = date("2023-12-");

 

    if(isset($_POST['transType'])){
        $transType = $_POST['transType'];
    } 

    if(isset($_POST['filterYear'])){
        $filterYear = $_POST['filterYear'];
    } 

    $startMonth = date($filterYear."-01");
    $lastMonth = date($filterYear."-12");

    $start = date($filterYear."-01");
    $end = date($filterYear."-12-");



    

 

?>

 
<!-- Card Body -->
<form method="post" action="main.php?nid=123&sid=0&rid=0">
	<div class="card-body ">	  
		<div class="card card-info">
			<div class="card-header with-border"> 
			<h3 class="card-title">Submission Monitoring Details</h3>
			</div>	

            <div class="d-flex justify-content-start col-md-6">
        
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-form-label">Filter Submission Type</label>
                     
                                <select class="custom-select transType" name="transType" id="transType" onchange="submit()"  value="<?php echo $_POST['transType']?>">
                                    <!-- <option value=""  selected="" disabled="">Select Submission Type</option> -->
                                    <option value="1" selected="" <?php if($_POST['transType'] == 1){echo "selected='selected'";} ?>>Regular Submission</option>
                                    <option value="5" <?php if($_POST['transType'] == 5){echo "selected='selected'";} ?>>Extended Regular Submission</option>
                                    <option value="6" <?php if($_POST['transType'] == 6){echo "selected='selected'";} ?>>Special Submission - Late Submission</option>
                                    <option value="2" <?php if($_POST['transType'] == 2){echo "selected='selected'";} ?>>Special Submission - Correction File</option>
                                    <option value="3" <?php if($_POST['transType'] == 3){echo "selected='selected'";} ?>>Special Submission - Dispute</option>
                                    <option value="4" <?php if($_POST['transType'] == 4){echo "selected='selected'";} ?>>Special Submission - Historical Data</option>
                                    
                                </select>
                        </div>
                    </div>

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
            </div>

            

            

              <!-- /.card-header -->
              
              <div class="card-body">
                <table id="submissionsmonitoringtable" class="table table-bordered table-responsive text-center dataTable dtr-inline">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Provider Code</th>
                            <th>Entity Name</th>
                            <th>Rating</th>
                            <th>Authorized Representative</th>
                            <th>Email</th>
                            <?php
                            for($i=$start; $i<=$end;  $i=date("Y-m",strtotime($i."+1 month"))){ 
                          
                                echo '<th>'.date_format(new DateTime($i), "F Y").'</th>';
                            }
                      
                       
        
                            ?>							
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            
                            <?php 
                            $arrCtr = 0;
                     

                            // $get_all_sep=$dbh4->query("SELECT fld_ctrlno, fld_type, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_provcode, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_name FROM tbentities WHERE fld_registration_type <> 1".$query." OR (fld_registration_type = 1 AND fld_noc_pass_status = 1) LIMIT 200");



                            // echo "SELECT tbtransmittal.fld_ctrlno, tbtransmittal.fld_filed_date_ts,  tbtransmittal.fld_date_covered, tbtransmittal.fld_total_contracts, tbentities.fld_ctrlno, AES_DECRYPT(tbentities.fld_provcode, MD5(CONCAT(tbentities.fld_ctrlno, 'RA3019'))) AS fld_provcode, AES_DECRYPT(tbentities.fld_name, MD5(CONCAT(tbentities.fld_ctrlno, 'RA3019'))) AS fld_name, AES_DECRYPT(tbentities.fld_lname_ar, MD5(CONCAT(tbentities.fld_ctrlno, 'RA3019'))) AS fld_lname_ar, AES_DECRYPT(tbentities.fld_fname_ar, MD5(CONCAT(tbentities.fld_ctrlno, 'RA3019'))) AS fld_fname_ar, AES_DECRYPT(tbentities.fld_mname_ar, MD5(CONCAT(tbentities.fld_ctrlno, 'RA3019'))) AS fld_mname_ar, AES_DECRYPT(tbentities.fld_email_ar, MD5(CONCAT(tbentities.fld_ctrlno, 'RA3019'))) AS fld_email_ar  from tbtransmittal INNER JOIN tbentities on tbtransmittal.fld_ctrlno = tbentities.fld_ctrlno  WHERE tbtransmittal.fld_trans_type = '".$transType."'   ORDER BY tbtransmittal.fld_date_covered DESC";
                            $queryString = "SELECT tbtransmittal.fld_ctrlno, tbtransmittal.fld_filed_date_ts,  tbtransmittal.fld_date_covered, tbtransmittal.fld_total_contracts, tbentities.fld_ctrlno, AES_DECRYPT(tbentities.fld_provcode, MD5(CONCAT(tbentities.fld_ctrlno, 'RA3019'))) AS fld_provcode, AES_DECRYPT(tbentities.fld_name, MD5(CONCAT(tbentities.fld_ctrlno, 'RA3019'))) AS fld_name, AES_DECRYPT(tbentities.fld_lname_ar, MD5(CONCAT(tbentities.fld_ctrlno, 'RA3019'))) AS fld_lname_ar, AES_DECRYPT(tbentities.fld_fname_ar, MD5(CONCAT(tbentities.fld_ctrlno, 'RA3019'))) AS fld_fname_ar, AES_DECRYPT(tbentities.fld_mname_ar, MD5(CONCAT(tbentities.fld_ctrlno, 'RA3019'))) AS fld_mname_ar, AES_DECRYPT(tbentities.fld_email_ar, MD5(CONCAT(tbentities.fld_ctrlno, 'RA3019'))) AS fld_email_ar  from tbtransmittal INNER JOIN tbentities on tbtransmittal.fld_ctrlno = tbentities.fld_ctrlno  WHERE tbtransmittal.fld_trans_type = '".$transType."'  ORDER BY tbtransmittal.fld_date_covered DESC;";

                            //  $queryString = "SELECT tbtransmittal.fld_ctrlno, tbtransmittal.fld_filed_date_ts,  tbtransmittal.fld_date_covered, tbtransmittal.fld_total_contracts, tbentities.fld_ctrlno, AES_DECRYPT(tbentities.fld_provcode, MD5(CONCAT(tbentities.fld_ctrlno, 'RA3019'))) AS fld_provcode, AES_DECRYPT(tbentities.fld_name, MD5(CONCAT(tbentities.fld_ctrlno, 'RA3019'))) AS fld_name, AES_DECRYPT(tbentities.fld_lname_ar, MD5(CONCAT(tbentities.fld_ctrlno, 'RA3019'))) AS fld_lname_ar, AES_DECRYPT(tbentities.fld_fname_ar, MD5(CONCAT(tbentities.fld_ctrlno, 'RA3019'))) AS fld_fname_ar, AES_DECRYPT(tbentities.fld_mname_ar, MD5(CONCAT(tbentities.fld_ctrlno, 'RA3019'))) AS fld_mname_ar, AES_DECRYPT(tbentities.fld_email_ar, MD5(CONCAT(tbentities.fld_ctrlno, 'RA3019'))) AS fld_email_ar  from tbtransmittal INNER JOIN tbentities on tbtransmittal.fld_ctrlno = tbentities.fld_ctrlno  WHERE fld_registration_type <> 1 OR (tbentities.fld_registration_type = 1 AND tbentities.fld_noc_pass_status = 1) and tbtransmittal.fld_trans_type = '".$transType."'";
        
                         
                            $sql = $dbh4->query($queryString);

                            $list = [];
                            while ($gi= $sql->fetch_array()) {
                                $gi['fld_date_covered'] = date("Y-m-d", strtotime($gi['fld_date_covered']));
                               
                                array_push($list,$gi);
                            
                            }
                    
                            $data = json_decode(json_encode($list), true);
                          
                            $GLOBALS['data']= $data;
                     
                                  // Function for filtered data

                            $ratingCtr = 0;

                            $arrReduce = array_reduce($data, function($arr, $item) {

                                // Filter Array using fld_date_covered
                                if(array_filter($arr, function($filterDate) use ($item) {
                                    return $filterDate['fld_ctrlno'] === $item['fld_ctrlno'] && date('n', strtotime($filterDate['fld_date_covered'])) === date('n', strtotime($item['fld_date_covered']));
                                })) return array_merge($arr, []);

                                $filterArray = array_filter($GLOBALS['data'], function($filter) use ($item) {
                                    return $filter['fld_ctrlno'] === $item['fld_ctrlno'] && date('n', strtotime($filter['fld_date_covered'])) === date('n', strtotime($item['fld_date_covered']));
                                });
                                // print_r($filterArray);

                                //Add contract cr
                                $sumContractCR = array_reduce($filterArray, function($a, $b) {
                                    return $a + $b['fld_total_contracts'];
                                }, 0);

                        
                                //Remove computed fld_total_contracts
                                unset($item['fld_total_contracts']);
                                return array_merge($arr, [array_merge($item, ['fld_sum_contracts_cr' => $sumContractCR])]);
                            }, []);
                    
                                                                        
                            
                            foreach($arrReduce as $key => $val){
                                $finalArray[$val['fld_ctrlno']]['ctrlno'] = ($val['fld_ctrlno']);
                                $entityName = $finalArray[$val['fld_ctrlno']]['bankName'] = $val['fld_name'];
                                $finalArray[$val['fld_ctrlno']]['provCode'] = $val['fld_provcode'];
                                $finalArray[$val['fld_ctrlno']]['authRepName'] = $val['fld_fname_ar']." ".$val['fld_mname_ar']." ".$val['fld_lname_ar'];
                                $month = substr($val['fld_date_covered'], 0, -3);
                                
                                $finalArray[$val['fld_ctrlno']]['authRepEmail'] = $val['fld_email_ar'];
                                 $finalArray[$val['fld_ctrlno']][$month] = $val['fld_sum_contracts_cr'];
                                // $finalArray[$val['fld_ctrlno']]['rating'] =  count($val['fld_sum_contracts_cr']);
                                
                            }
                            $counter = 1;
                  

                            
                            // print_r($date1);
                            // print_r($finalArray);

                    

                            


                             
                          
                            foreach($finalArray as $key => $val){

                                $arrCtr = 0; 
                                for($i=$startMonth; $i<=$lastMonth;  $i=date("Y-m",strtotime($i."+1 month"))){ 
                                  
                                    if($val[$i] > 0){
                                        $arrCtr++;
                                    }
                                }

                                        
                                if($arrCtr == 12){
                                    $compliance_rating = "Fully Compliant";
                                    $color = "text-success";
                                    $dataOrder = 'data-order = "1" ';
                                }elseif($arrCtr >= 9 and $arrCtr < 12){
                                    $compliance_rating = "Mostly Compliant";
                                    $color = "text-info";
                                    $dataOrder = 'data-order = "2" ';
                                }elseif($arrCtr >= 7 and $arrCtr <= 9){
                                    $compliance_rating = "Partially Compliant";
                                    $color = "text-primary";
                                    $dataOrder = 'data-order = "3" ';
                                }elseif($arrCtr >= 4 and $arrCtr <= 6){
                                    $compliance_rating = "Minimally Compliant";   
                                    $color = "text-warning";
                                    $dataOrder = 'data-order = "4" ';
                                }
                                elseif($arrCtr >= 0 and $arrCtr <= 3){
                                    $compliance_rating = "Inactive";
                                    $color = "text-danger";
                                    $dataOrder = 'data-order = "5" ';
                                }else {
                                    $compliance_rating = "N/A";
                                    $color = "text-muted";
                                }

                            ?>
                            	<td><?php echo $counter; ?></td>
                                <td><?php echo $val['provCode']; ?></td>
                                <td><?php echo $val['bankName'] ?></td>
                                <td <?php echo $dataOrder ?> class="<?php echo $color ?>"><b><?php echo $compliance_rating ?></b></td>
                                <td><?php echo $val['authRepName'] ?></td>
                                <td><?php echo $val['authRepEmail'] ?></td>
                                <?php 
                                     for($i=$start; $i<=$end;  $i=date("Y-m",strtotime($i."+1 month"))){ 
                                ?>
                    
                                <td><?php echo number_format($val[$i]) ? number_format($val[$i]) : 0 ?></td>
                                <?php   
                                }
                                $counter++; 
                                ?>                                                   		
                            
                              
                              
                                
                    </tr>
                      
                            <?php
                            }
                            
                            ?>
                        </tbody> 
                    </table>
                </form>
              </div>
              <!-- /.card-body -->
            </div>
        <!-- /.card -->   
	</div>

<script>
window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 2500);

</script>


