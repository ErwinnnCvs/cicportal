<?php

	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);
	error_reporting(E_ALL);

	// include('config.php');



	// $dsn = 'mysql:host=localhost;dbname=employeeportal80';
    // $username = 'root';
    // $password = '';

$dsn = "mysql:host=10.250.111.80;dbname=employeeportal";
$username = "cimsazureconn";
$password = "\$HSz1#Zd@d(xwc9";



    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);


	$timestamp = date("Y-m-d H:i:s");
	$filterYear = "2025";   

	if(isset($_POST['filterYear'])){
        $filterYear = $_POST['filterYear'];
    } 

	$start = date($filterYear."-01");
    $end = date($filterYear."-12-");


	//Function to compute difference of 2 dates in MINUTES
	// function computeDiff($time1, $time2){

	// 	//Initalized DateTime
	// 	$time1 = DateTime::createFromFormat("Y-m-d H:i:s", $time1);
	// 	$time2 = DateTime::createFromFormat("Y-m-d H:i:s", $time2);
	// 	// $overDT =  $time1->modify('+1 day');

	// 	if($time1 == FALSE ||  $time2 == FALSE){
	// 		return 0;
	// 	}

	// 	//Compute DateTime Diff
	// 	$minutesInterval = date_diff($time1,$time2);
		
		
	// 	//Compute Time Diff in Mins
	// 	$minutesDiff = $minutesInterval->days * 24 * 60;
	// 	$minutesDiff += $minutesInterval->h * 60;
	// 	$minutesDiff += $minutesInterval->i;

	// 	return $minutesDiff;
	// }




function calculateTimeDiff($startDate, $endDate, $pdo, $phase) {


    // Working hours
    $startOfDay = 8; // 8 AM
    $lunchStart = 12; // 12 PM
    $lunchEnd = 13; // 1 PM
    $endOfDay = 17; // 5 PM

    $morningHours = $lunchStart - $startOfDay; // 4 hours (8 AM to 12 PM)
    $afternoonHours = $endOfDay - $lunchEnd; // 4 hours (1 PM to 5 PM)
    $workingHoursPerDay = $morningHours + $afternoonHours; // Total 8 hours per day

    // Convert inputs to DateTime objects
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);

    // Ensure the start date is earlier than the end date
    if ($start > $end) {
        return "0 hours, 0 minutes";
    }

    $totalMinutes = 0;


	
    while ($start <= $end) {
        $currentDay = $start->format('Y-m-d');
    
		//$phase, checks if active(1) or downtime(2)
		if($phase == 1){
			$isWeekend = in_array($start->format('N'), [6, 7]); // 6=Saturday, 7=Sunday
		}else{
			$isWeekend = FALSE;
		}

		$stmt = $pdo->prepare("SELECT count(*) from tb_hr_holidays where fld_date = ?");
        $stmt->execute([$currentDay]);
        $isHoliday = $stmt->fetchColumn() > 0;

	
        if (!$isWeekend && !$isHoliday) {
            // Define work periods for the day
            $startOfWork = clone $start;
            $startOfWork->setTime($startOfDay, 0);
            $lunchStartTime = clone $start;
            $lunchStartTime->setTime($lunchStart, 0);
            $lunchEndTime = clone $start;
            $lunchEndTime->setTime($lunchEnd, 0);
            $endOfWork = clone $start;
            $endOfWork->setTime($endOfDay, 0);

            // Calculate minutes for the current day
            $dayMinutes = 0;

            if ($start < $lunchStartTime) {
                // Morning work period
                $workStart = max($start, $startOfWork);
                $workEnd = min($end, $lunchStartTime);
                if ($workStart < $workEnd) {
                    $dayMinutes += ($workEnd->getTimestamp() - $workStart->getTimestamp()) / 60;
                }
            }

            if ($end > $lunchEndTime) {
                // Afternoon work period
                $workStart = max($start, $lunchEndTime);
                $workEnd = min($end, $endOfWork);
                if ($workStart < $workEnd) {
                    $dayMinutes += ($workEnd->getTimestamp() - $workStart->getTimestamp()) / 60;
                }
            }

            $totalMinutes += $dayMinutes;
        }

        // Move to the next day
        $start->modify('+1 day')->setTime(0, 0);
    }

    // Convert total minutes to hours and minutes
    $hours = floor($totalMinutes / 60);
    $minutes = $totalMinutes % 60;

    // return "$hours hours, $minutes minutes";
	if($hours == 0 && $minutes == 0){
		return "0";
	}

	return "$hours:$minutes";
}




// Example usage
$startDate = '2024-10-21 09:50';
$endDate = '2024-10-22 17:09';
// echo "Total working time 1 - " . calculateTimeDiff($startDate, $endDate);

$date1 = calculateTimeDiff($startDate, $endDate, $pdo, 1);

// echo $date1;

// echo "<Br>";

$startDate1 = '2024-10-24 09:50';
$endDate1 = '2024-10-25 09:44';
// echo "Total working time 2 - " . calculateTimeDiff($startDate1, $endDate1);

$date2 = calculateTimeDiff($startDate1, $endDate1, $pdo, 2);

// echo $date2;

function computePhases($start, $end){
	$explodeTime = explode(":", $start);
	$startHr = $explodeTime[0];
	$startMin = $explodeTime[1];

	$explodeMin = explode(":", $end);
	$endHr = $explodeMin[0];
	$endMin = $explodeMin[1];
	
	$totalMin = $startMin + $endMin;

	$totalHours = $startHr + $endHr;

	if($totalMin >= 60){
		$remainder =  $totalMin - 60;
		$totalHours += 1;

		return $totalHours." hrs, ".$remainder." mins";
	}else{
		return $totalHours." hrs, ".$totalMin." mins";
	}

}

// echo "<br>".computePhases($date1, $date2);



?>

 
<!-- Card Body -->
	<div class="card-body">	
		
		<div class="d-flex justify-content-center">
			<div class="col-md-6">
			<!-- <?php if ($_POST['sbtUploadCount']){ ?>
			<div class="alert alert-<?php echo $msgclr ?> alert-dismissible fade show" role="alert">
				<?php echo $msg ?>
			</div>
			<?php } ?> -->
			
			</div>
		</div>
            <!-- /.card -->
		<div class="card card-info">
			<div class="card-header with-border"> 
			<h3 class="card-title">Dispute Monitoring</h3>
			</div>	
			
			
			<form method="post" action="main.php?nid=143&sid=0&rid=0">
		<div class="col-lg-2">
			<div class="form-group">
				<label class="col-form-label">Filter Dispute Filed Year</label>
		
				<select class="custom-select filterYear" name="filterYear" id="filterYear" onchange="submit()"  value="<?php echo $_POST['filterYear']?>">
								<!-- <option value=""  disabled="">Select Submission Type</option> -->      
									<option><?php if(isset($_POST['filterYear'])){print_r($_POST['filterYear']);}else{print_r(date("Y"));} ?></option>
									<?php
									
										$y=(int)date('Y');
										?>
										<option value="<?php echo $y;?>" ><?php echo $y;?></option>
											<?php
											$y--;
										for(; $y>'2020'; $y--)
										{
									?>
									<option value="<?php echo $y;?>"><?php echo $y;?></option>
									<?php }?>
								</select>
			</div>
		</div>
		</form>

					<!-- <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
						</ul> -->

						<div class="tab-content p-2">

							<div class="tab-pane active" id="tab_1">
								<table id="disputeMonitoring" class="table table-bordered table-responsive text-center">
									<thead>
										<tr>
											<th class="bg-info" colspan="4">Disputer Details</th>
											<th class="bg-info" colspan="7">Dispute Process Details</th>
											<th class="bg-info" colspan="4">Dispute Status</th>
											<th class="bg-info" colspan="4">Dispute Remarks</th>
										</tr>
										<tr>
											<th>#</th>
											<th>TRN</th>
											<th>Disputer Name</th>
											<th>SE Name</th>
											<th>Filing Date</th>
											<th>Filing Time</th>
											<th>SE Notification Date</th>
									<!-- 		<th>SE Classification Date</th>
											<th>SE Classification Date</th> -->
											<th>SE Submission Date</th>
											<th>SE Submission Time</th>
											<!-- <th>DRT Acknowledge Date</th>
											<th>DRT Acknowledge Time</th> -->
											<th>DRT Verification Date</th>
											<th>DRT Verification Time</th>
											<th>Status</th>
											<th>SE Classification</th>
											<th>DRT Classification</th>
											<th>Type of Resolution</th>
											<th>Resolution Date</th>
											<th>Total Active Phase</th>
											<th>Total Down Time</th>
											<!-- <th>Processed</th> -->
											<th>Remarks</th>


                      						
								
										</tr>
									</thead>
									<tbody>
										<?php
											$counter = 1;
											$key = "RA3019";

						
											// c.fld_id > 9434
											$get_filed_disputes = $dbh2->query( "SELECT c.fld_id, c.fld_TRN as contractTRN, c.fld_prov, c.fld_name, c.fld_se_dispdetails_ts, c.fld_resolution_type, c.fld_email_notif_ts, c.fld_resolution_type_ts, c.fld_dispute_verification_status, c.fld_disp_status, c.fld_resolution_type, c.fld_dispute_classification, c.fld_subjcode_ts, c.fld_classification_ts, c.fld_disp_classify_drcp, c.fld_disp_classify_drcp_ts, c.fld_status, c.fld_contractType, c.fld_complaint, c.fld_description, c.fld_resolution_mail_ts, AES_DECRYPT(c.fld_subjcode, CONCAT(s.fld_id,'G3n13')) AS subjcode, s.fld_TRN as subjTRN, c.is_processed, s.fld_DateFilled, AES_DECRYPT(s.fld_Fname, CONCAT(s.fld_Birthday,'G3n13')) AS firstname, s.fld_Birthday, AES_DECRYPT(s.fld_Mname, CONCAT(s.fld_Birthday,'G3n13')) AS middlename, AES_DECRYPT(s.fld_Lname, CONCAT(s.fld_Birthday,'G3n13')) AS lastname, AES_DECRYPT(s.fld_Contact, CONCAT(s.fld_Birthday,'G3n13')) AS contact, s.changes, AES_DECRYPT(s.fld_SSS, CONCAT(s.fld_Birthday,'G3n13')) AS SSS, AES_DECRYPT(s.fld_GSIS, CONCAT(s.fld_Birthday,'G3n13')) AS GSIS, AES_DECRYPT(s.fld_TIN, CONCAT(s.fld_Birthday,'G3n13')) AS TIN, AES_DECRYPT(s.fld_UMID, CONCAT(s.fld_Birthday,'G3n13')) AS UMID, AES_DECRYPT(s.fld_DL, CONCAT(s.fld_Birthday,'G3n13')) AS DL, AES_DECRYPT(s.fld_subjcode, CONCAT(s.fld_Birthday,'G3n13')) AS subjcode FROM contract as c JOIN subject as s ON c.fld_TRN = s.fld_TRN WHERE s.fld_dateFilled LIKE '%".$filterYear."%' and c.fld_id > 9434 
												" );
											while ($gfd = $get_filed_disputes->fetch_array()) {

						
												
											
												//Dispute Status
												if($gfd['fld_disp_status'] == 0 && $gfd['fld_dispute_verification_status'] == 0){
													$disputeStatus = "PENDING";
												}elseif($gfd['fld_disp_status'] == 1 || $gfd['fld_dispute_verification_status'] == 1){
													$disputeStatus = "INPROGRESS";
												}
												
												if($gfd['fld_disp_status'] == 2 && $gfd['fld_dispute_verification_status'] == 1){
													$disputeStatus = "COMPLETED";
													$hidden = 'hidden';
												}

												if($gfd['fld_dispute_classification'] == 1){
													$drtType = "Simple";
												}elseif($gfd['fld_dispute_classification'] == 2){
													$drtType = "Complex";
												}elseif($gfd['fld_dispute_classification'] == 3){
													$drtType = "Highly Technical";
												}else{
													$drtType = "N/A";
												}

												if($gfd['fld_disp_classify_drcp'] == 1){
													$drcpType = "Simple";
												}elseif($gfd['fld_disp_classify_drcp'] == 2){
													$drcpType = "Complex";
												}elseif($gfd['fld_disp_classify_drcp'] == 3){
													$drcpType = "Highly Technical";
												}else{
													$drcpType = "N/A";
												}

												if($gfd['fld_resolution_type'] == 1){
													$resolutionType = "Incorrect";
												}elseif($gfd['fld_resolution_type'] == 2){
													$resolutionType = "Missing";
												}elseif($gfd['fld_resolution_type'] == 3){
													$resolutionType = "Disinterested Disputer";
												}elseif($gfd['fld_resolution_type'] == 4){
													$resolutionType = "Mutually Agreed";
												}elseif($gfd['fld_resolution_type'] == 5){
													$resolutionType = "Issues outside ODRS";
												}elseif($gfd['fld_resolution_type'] == 6){
													$resolutionType = "Annulment/Removal of Credit Data";
												}elseif($gfd['fld_resolution_type'] == 7){
													$resolutionType = "Inaction by the SE";
												}else{
													$resolutionType = "N/A";
												} 



			
												// echo $gfd['fld_id'];
												$stringId = $gfd['fld_id'];
								 
		
												if ($gfd['fld_status'] == 4) {

													if($gfd['fld_prov'] != ""){
														$get_company_name = $dbh4->query("SELECT AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$gfd['fld_prov']."'");
														$gcn=$get_company_name->fetch_array();
														$name = $gcn['name'];
													}else{
														$name = $gfd['fld_name'];
													}

										
												} else {
												$get_company_name = $dbh4->query("SELECT AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$gfd['fld_prov']."'");
												$gcn=$get_company_name->fetch_array();
												$name = $gcn['name'];
												}

												$downTime = 0;
												$activePhase = 0;
												$compClassifyToSubmission = 0;
												$compFillingToClassify = 0;
												$compSubmissionToVerification = 0;
												$compVerificationToResolution = 0;

					
												//Down time
												// if($gfd['fld_classification_ts'] == 0){
												// 	$gfd['fld_classification_ts'] = date("Y-m-d H:i:s");
												// }
											


												// if($gfd['fld_resolution_type'] == 7){
												// 	$resolutionType = "Inaction of the submitting entity";
													
												// }

												// if($gfd['fld_resolution_type'] == 6){
												// 	$resolutionType = "Issues outside ODRS";
													
												// }

													if(is_null($gfd['fld_email_notif_ts'])){
														$emailNotif = "No actions yet.";
														
													}else{
														$emailNotif = date("d-F-Y", strtotime($gfd['fld_email_notif_ts']));
													}

												

				

													//SE Classification Time and Date
													if(is_null($gfd['fld_DateFilled'])){
														$filingDate = 'No actions yet.';
														$filingTime = "No actions yet.";
													}else{
														$filingDate = date("d-F-Y", strtotime($gfd['fld_DateFilled']));
														$filingTime = date("h:i A", strtotime($gfd['fld_DateFilled']));
													}
								
						
	
													//SE Classification Time and Date
													if(is_null($gfd['fld_classification_ts'])){
														// $displayFillingToClassify = sprintf("%02d.%02d", floor($compFillingToClassify/60), $compFillingToClassify%60);

														$classificationDate = 'No actions yet.';
														$classificationTime = "No actions yet.";
													}else{
														$classificationDate = date("d-F-Y", strtotime($gfd['fld_classification_ts']));
														$classificationTime = date("h:i A", strtotime($gfd['fld_classification_ts']));
													}
	
													//SE Submission Details Time and Date
													if(is_null($gfd['fld_se_dispdetails_ts'])){
														$submissionDate = 'No actions yet.';
														$submissionTime = "No actions yet.";
													}else{
														$submissionDate = date("d-F-Y", strtotime($gfd['fld_se_dispdetails_ts']));
														$submissionTime = date("h:i A", strtotime($gfd['fld_se_dispdetails_ts']));
													}
	
													
													//DRT Verification Time and Date
													if(is_null($gfd['fld_subjcode_ts'])){
														$verificationDate = 'No actions yet.';
														$verificationTime = "No actions yet.";
													}else{
														$verificationDate = date("d-F-Y", strtotime($gfd['fld_subjcode_ts']));
														$verificationTime = date("h:i A", strtotime($gfd['fld_subjcode_ts']));
													}
	
													//Resolution time and Date
													if(is_null($gfd['fld_resolution_type_ts'])){
														// $resolutionDate = 'No actions yet.';
														// $resolutionTime = "No actions yet.";

														if($gfd['fld_resolution_type'] == 7){
															$resolutionDate = date("d-F-Y", strtotime($gfd['fld_resolution_mail_ts']));
															$resolutionTime = date("h:i A", strtotime($gfd['fld_resolution_mail_ts']));
														}else{
															$resolutionDate = 'No actions yet.';
															$resolutionTime = "No actions yet.";
														}


													}else{
														$resolutionDate = date("d-F-Y", strtotime($gfd['fld_resolution_type_ts']));
														$resolutionTime = date("h:i A", strtotime($gfd['fld_resolution_type_ts']));



													}

													//Down time - OLD
													// $compFillingToClassify = computeDiff($gfd['fld_DateFilled'],$gfd['fld_classification_ts']);
													// $compClassifyToSubmission = computeDiff($gfd['fld_classification_ts'],$gfd['fld_se_dispdetails_ts']);

													//Active Phase - OLD
													// $compSubmissionToVerification = computeDiff($gfd['fld_se_dispdetails_ts'],$gfd['fld_subjcode_ts']);
													// $compVerificationToResolution = computeDiff($gfd['fld_subjcode_ts'],$gfd['fld_resolution_type_ts']);


													//Down time - NEW
													$compFillingToClassify = calculateTimeDiff($gfd['fld_DateFilled'],$gfd['fld_classification_ts'], $pdo, 2);
													$compClassifyToSubmission = calculateTimeDiff($gfd['fld_classification_ts'],$gfd['fld_se_dispdetails_ts'], $pdo, 2);

													//Active Phase - NEW
													$compSubmissionToVerification = calculateTimeDiff($gfd['fld_se_dispdetails_ts'],$gfd['fld_subjcode_ts'], $pdo, 1);
													$compVerificationToResolution = calculateTimeDiff($gfd['fld_subjcode_ts'],$gfd['fld_resolution_type_ts'], $pdo, 1);

													//Compute Total Down Time
													$displayDownTime = computePhases($compFillingToClassify,$compClassifyToSubmission);
													
													//Compute Total Active Phase
													$displayActivePhase = computePhases($compSubmissionToVerification,$compVerificationToResolution);

													// echo

													
													//  
													// $displayActivePhase = $compSubmissionToVerification + $compSubmissionToVerification;
	
													// $displayDownTime = sprintf("%02d:%02d", floor($downTime/60), $downTime%60);
													// $displayDownTime = $downTime;
													// $displayActivePhase = sprintf("%02d:%02d", floor($activePhase/60), $activePhase%60);
	
													// $displayFillingToClassify = floor($compFillingToClassify / 60).':'.($compFillingToClassify -   floor($compFillingToClassify / 60) * 60);
													$displayFillingToClassify = sprintf("%02d.%02d", floor($compFillingToClassify/60), $compFillingToClassify%60);
													$displayClassifyToSubmission = sprintf("%02d.%02d", floor($compClassifyToSubmission/60), $compClassifyToSubmission%60);
													$displaySubmissionToVerification = sprintf("%02d.%02d", floor($compSubmissionToVerification/60), $compSubmissionToVerification%60);
													$displayVerificationToResolution = sprintf("%02d.%02d", floor($compVerificationToResolution/60), $compVerificationToResolution%60);

													//Simple Dispute 3 days
													// if($gfd['fld_dispute_classification'] == 1){
													// 	if($activePhase <= 4320){
				
													// 		$remarks = "Processed within the applicable time.";
													// 	}elseif($activePhase > 4320){
													// 		$remarks = "Not processed within the applicable time.";
													// 	}

													// //Complex Dispute 7 days
													// }elseif($gfd['fld_dispute_classification'] == 2){
													// 	if($activePhase <= 10080){
													// 		$remarks = "Processed within the applicable time.";
													// 	}elseif($activePhase > 10080){
													// 		$remarks = "Not processed within the applicable time.";
													// 	}
													// //Highly Technical Dispute 20 days	
													// }elseif($gfd['fld_dispute_classification'] == 3){
													// 	if($activePhase <= 28800){
													// 		$remarks = "Processed within the applicable time.";
													// 	}elseif($activePhase > 28800){
													// 		$remarks = "Not processed within the applicable time.";
													// 	}
													// }else{
													// 	$remarks = "N/A";
													// }

													$explodeActive = explode(" ",$displayActivePhase);
													$activeHoursInMins = $explodeActive[0] * 60;
													$activeMins = $explodeActive[2];

													//Convert active phase in minutes
													$totalActiveMins = $activeHoursInMins + $activeMins;

													// echo $totalActiveMins;

													// echo $totalActiveMins;



													//Remarks New	
													if($gfd['fld_dispute_classification'] == 1 && $disputeStatus == "COMPLETED"){

														// 4320
														if($totalActiveMins <= 1440){
				
															$remarks = "Processed within the applicable time.";
															$isProcessed = 1;

														}elseif($totalActiveMins > 1440){
															$remarks = "Not processed within the applicable time.";		
															$isProcessed = 2;
														}

													//Complex Dispute 7 days
													}elseif($gfd['fld_dispute_classification'] == 2 && $disputeStatus == "COMPLETED"){

														//10080
														if($totalActiveMins <= 3360){
															$remarks = "Processed within the applicable time.";
															$isProcessed = 1;
														}elseif($totalActiveMins > 3360){
															$remarks = "Not processed within the applicable time.";

															$isProcessed = 2;
														}
													//Highly Technical Dispute 20 days	
													}elseif($gfd['fld_dispute_classification'] == 3 && $disputeStatus == "COMPLETED"){

														//28800
														if($totalActiveMins <= 9600){
															$remarks = "Processed within the applicable time.";
															$isProcessed = 1;
														}elseif($totalActiveMins > 9600){
															$remarks = "Not processed within the applicable time.";

															$isProcessed = 2;
														}
													}else{

														if($gfd['fld_resolution_type'] == 7){
															$remarks = 'The inaction of the Submitting Entity, mutual agreement of the parties to resolve the dispute, the disinterest of the Disputer to pursue the dispute and those disputes which are beyond the CIC&#39;s mandate and jurisdiction are deemed considered processed within the applicable time.';

															$isProcessed = 3;
														}else{
																$remarks = "N/A";
																$isProcessed = 4;
														}
													
													}


													//Script to update dispute contract table if processed or not DO NOT DELETE
													if($isProcessed == 1){
														$updateDispute = $dbh2->query("UPDATE contract SET is_processed = 1 where fld_id = '".$gfd['fld_id']."'");

														if($updateDispute){
															$isProcessed = 'PROCESSED'.$gfd['fld_id'];
														}
													
													}elseif($isProcessed == 2){
														$updateDispute = $dbh2->query("UPDATE contract SET is_processed = 2 where fld_id = '".$gfd['fld_id']."'");

														if($updateDispute){
																$isProcessed = 'NOT PROCESSED'.$gfd['fld_id'];
														}

													
													}elseif($isProcessed == 3){
														$updateDispute = $dbh2->query("UPDATE contract SET is_processed = 3 where fld_id = '".$gfd['fld_id']."'");

														if($updateDispute){
															$isProcessed = 'inaction'.$gfd['fld_id'];
														}

														
													}else{
														$updateDispute = $dbh2->query("UPDATE contract SET is_processed = 4 where fld_id = '".$gfd['fld_id']."'");

															if($updateDispute){
																$isProcessed = 'N/A';
															}
													}

												


												


	
		
												
										?>
										<tr>
											<form method="post" action="main.php?nid=143&sid=0&rid=0">

											
												<td><?php echo $counter; ?></td>
												<!-- <input type="text" name="aeisctrlno" hidden value="<?php echo $row['fld_ctrlno'] ?>"> -->
												<td><?php echo $gfd['contractTRN']; ?></td>
												<td><?php echo $gfd['firstname']. " " .$gfd['middlename']. " " .$gfd['lastname']; ?></td> 
												<td><?php echo $name; ?></td>
												<td><?php echo $filingDate ?></td>
												<td><?php echo $filingTime ?></td>
												<td><?php echo $emailNotif; ?></td>		
										<!-- 		<td><?php echo $classificationDate ; ?></td>
												<td><?php echo $classificationTime ; ?></td>	 -->								
												<td><?php echo $submissionDate ; ?></td>
												<td><?php echo $submissionTime ; ?></td>
										
												<td><?php echo $verificationDate ; ?></td>
												<td><?php echo $verificationTime ; ?></td>
												<td><?php echo $disputeStatus ; ?></td>
												<td><?php echo $drcpType ; ?></td>
												<td><?php echo $drtType ; ?></td>
												<td><?php echo $resolutionType; ?></td>
												<td><?php echo $resolutionDate." ".$resolutionTime; ?></td>
												<td><?php echo $displayActivePhase; ?></td>
												<td><?php echo $displayDownTime; ?></td>
												<!-- <td><?php echo $isProcessed ?></td> -->
												<td><?php echo $remarks ?></td>
												<!-- <td><?php $gfd['fld_complaint'] != NULL || $gfd['fld_complaint'] == "" ? print_r($gfd['fld_complaint'] ) : "N/A" ; ?></td> -->
												<!-- Time stamp for Resoltion mailers -->
												<!-- <td><?php echo "N/A" ; ?></td> -->
												
					
												<?php $counter++; ?>
											</form>
										</tr>
										<?php
											}
										?>
									</tbody>
								</table>
							</div>
						</div>
				</div>
    <!-- /.content -->
	</div>

