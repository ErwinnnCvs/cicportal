<?php
	// if ($_SESSION['usertype'] == 0 || $_SESSION['usertype'] == 6 || $_SESSION['usertype'] == 21 || ($_SESSION['usertype'] == 22 && $_SESSION['user_id'] == 107)) {
	require_once 'classes/Auth.class.php';

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	$timestamp = date("Y-m-d H:i:s");
	$filterYear = "2024";   

	if(isset($_POST['filterYear'])){
        $filterYear = $_POST['filterYear'];
    } 

	$start = date($filterYear."-01");
    $end = date($filterYear."-12-");


	//Function to compute difference of 2 dates in MINUTES
	function computeDiff($time1, $time2){

		//Initalized DateTime
		$time1 = DateTime::createFromFormat("Y-m-d H:i:s", $time1);
		$time2 = DateTime::createFromFormat("Y-m-d H:i:s", $time2);
		// $overDT =  $time1->modify('+1 day');

		// if($time1 == FALSE ){
		// 	$time1 = date("Y-m-d H:i:s");
		// 	$time1 = new DateTime($time1);
		// }

		// if($time2 == FALSE ){
		// 	$time2 = date("Y-m-d H:i:s");
		// 	$time2 = new DateTime($time2);
		// }

		if($time1 == FALSE ||  $time2 == FALSE){
			return 0;
		}

		//Compute DateTime Diff
		$minutesInterval = date_diff($time1,$time2);
		

		
		//Compute Time Diff in Mins
		$minutesDiff = $minutesInterval->days * 24 * 60;
		$minutesDiff += $minutesInterval->h * 60;
		$minutesDiff += $minutesInterval->i;

		return $minutesDiff;
	}

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
								<table id="example1" class="table table-bordered table-responsive text-center">
									<thead>
										<tr>
											<th class="bg-info" colspan="4">Disputer Details</th>
											<th class="bg-info" colspan="9">Dispute Process Details</th>
											<th class="bg-info" colspan="4">Dispute Status</th>
											<th class="bg-info" colspan="3">Dispute Remarks</th>
										</tr>
										<tr>
											<th>#</th>
											<!-- <th>TRN</th> -->
											<th>Disputer Name</th>
											<th>SE Name</th>
											<th>Filing Date</th>
											<th>Filing Time</th>
											<th>SE Notification Date</th>
											<th>SE Submission Date</th>
											<th>SE Submission Time</th>
											<th>DRT Acknowledge Date</th>
											<th>DRT Acknowledge Time</th>
											<th>DRT Verification Date</th>
											<th>DRT Verification Time</th>
											<th>Status</th>
											<th>Type of Dispute</th>
											<th>Type of Resolution</th>
											<th>Resolution Date</th>
											<th>Total Active Phase</th>
											<th>Total Down Time</th>
											<th>Remarks</th>


                      						
								
										</tr>
									</thead>
									<tbody>
										<?php
											$counter = 1;
											$key = "RA3019";

											// echo "SELECT fld_id, fld_TRN, fld_prov, fld_name, fld_dispute_classification, fld_status, fld_contractType, fld_complaint, fld_description, AES_DECRYPT(fld_subjcode, CONCAT(fld_id,'G3n13')) AS subjcode FROM contract WHERE fld_id > 51 ORDER BY fld_id DESC LIMIT 20";
                                            // $get_filed_disputes = $dbh2->query("SELECT fld_id, fld_TRN, fld_prov, fld_name, fld_se_dispdetails_ts, fld_resolution_type, fld_resolution_type_ts, fld_dispute_verification_status, fld_disp_status, fld_resolution_type, fld_dispute_classification, fld_subjcode_ts, fld_classification_ts, fld_status, fld_contractType, fld_complaint, fld_description, AES_DECRYPT(fld_subjcode, CONCAT(fld_id,'G3n13')) AS subjcode FROM contract WHERE fld_id > 51 and fld_prov = 'UB000340' ORDER BY fld_id DESC LIMIT 150");

											$get_filed_disputes = $dbh2->query( "SELECT c.fld_id, c.fld_TRN as contractTRN, c.fld_prov, c.fld_name, c.fld_se_dispdetails_ts, c.fld_resolution_type, c.fld_email_notif_ts, c.fld_resolution_type_ts, c.fld_dispute_verification_status, c.fld_disp_status, c.fld_resolution_type, c.fld_dispute_classification, c.fld_subjcode_ts, c.fld_classification_ts, c.fld_status, c.fld_contractType, c.fld_complaint, c.fld_description, AES_DECRYPT(c.fld_subjcode, CONCAT(s.fld_id,'G3n13')) AS subjcode, s.fld_TRN as subjTRN, s.fld_DateFilled, AES_DECRYPT(s.fld_Fname, CONCAT(s.fld_Birthday,'G3n13')) AS firstname, s.fld_Birthday, AES_DECRYPT(s.fld_Mname, CONCAT(s.fld_Birthday,'G3n13')) AS middlename, AES_DECRYPT(s.fld_Lname, CONCAT(s.fld_Birthday,'G3n13')) AS lastname, AES_DECRYPT(s.fld_Contact, CONCAT(s.fld_Birthday,'G3n13')) AS contact, s.changes, AES_DECRYPT(s.fld_SSS, CONCAT(s.fld_Birthday,'G3n13')) AS SSS, AES_DECRYPT(s.fld_GSIS, CONCAT(s.fld_Birthday,'G3n13')) AS GSIS, AES_DECRYPT(s.fld_TIN, CONCAT(s.fld_Birthday,'G3n13')) AS TIN, AES_DECRYPT(s.fld_UMID, CONCAT(s.fld_Birthday,'G3n13')) AS UMID, AES_DECRYPT(s.fld_DL, CONCAT(s.fld_Birthday,'G3n13')) AS DL, AES_DECRYPT(s.fld_subjcode, CONCAT(s.fld_Birthday,'G3n13')) AS subjcode FROM contract as c JOIN subject as s ON c.fld_TRN = s.fld_TRN WHERE s.fld_dateFilled LIKE '%".$filterYear."%' and c.fld_id > 9434
												" );
											while ($gfd = $get_filed_disputes->fetch_array()) {

												// $subject = $dbh2->query("SELECT fld_TRN, fld_DateFilled, AES_DECRYPT(fld_Fname, CONCAT(fld_Birthday,'G3n13')) AS firstname, fld_Birthday, AES_DECRYPT(fld_Mname, CONCAT(fld_Birthday,'G3n13')) AS middlename, AES_DECRYPT(fld_Lname, CONCAT(fld_Birthday,'G3n13')) AS lastname, AES_DECRYPT(fld_Contact, CONCAT(fld_Birthday,'G3n13')) AS contact, fld_DateFilled, changes, AES_DECRYPT(fld_SSS, CONCAT(fld_Birthday,'G3n13')) AS SSS, AES_DECRYPT(fld_GSIS, CONCAT(fld_Birthday,'G3n13')) AS GSIS, AES_DECRYPT(fld_TIN, CONCAT(fld_Birthday,'G3n13')) AS TIN, AES_DECRYPT(fld_UMID, CONCAT(fld_Birthday,'G3n13')) AS UMID, AES_DECRYPT(fld_DL, CONCAT(fld_Birthday,'G3n13')) AS DL, AES_DECRYPT(fld_subjcode, CONCAT(fld_Birthday,'G3n13')) AS subjcode FROM subject WHERE fld_TRN = '".$gfd['fld_TRN']."' ORDER BY subjcode ASC");
												// $s=$subject->fetch_array();

												
											
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
													$classificationType = "Simple";
												}elseif($gfd['fld_dispute_classification'] == 2){
													$classificationType = "Complex";
												}elseif($gfd['fld_dispute_classification'] == 3){
													$classificationType = "Highly Technical";
												}else{
													$classificationType = "N/A";
												}

												if($gfd['fld_resolution_type'] == 1){
													$resolutionType = "Incorrect";
												}elseif($gfd['fld_resolution_type'] == 2){
													$resolutionType = "Missing";
												}elseif($gfd['fld_resolution_type'] == 3){
													$resolutionType = "Disinterested";
												}elseif($gfd['fld_resolution_type'] == 4){
													$resolutionType = "Mutually Agreed";
												}elseif($gfd['fld_resolution_type'] == 3){
													$resolutionType = "Disinterested";
												}elseif($gfd['fld_resolution_type'] == 3){
													$resolutionType = "Disinterested";
												}else{
													$resolutionType = "N/A";
												}



			
												// echo $gfd['fld_id'];
												$stringId = $gfd['fld_id'];
								 
		
												if ($gfd['fld_status'] == 4) {
													$name = $gfd['fld_name'];
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
											


												if($gfd['fld_resolution_type'] == 7){
													$resolutionType = "Inaction of the submitting entity";
													
												}

												if($gfd['fld_resolution_type'] == 6){
													$resolutionType = "Issues outside ODRS";
													
												}

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
														$displayFillingToClassify = sprintf("%02d.%02d", floor($compFillingToClassify/60), $compFillingToClassify%60);

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
														$resolutionDate = 'No actions yet.';
														$resolutionTime = "No actions yet.";
													}else{
														$resolutionDate = date("d-F-Y", strtotime($gfd['fld_resolution_type_ts']));
														$resolutionTime = date("h:i A", strtotime($gfd['fld_resolution_type_ts']));
													}

													//Down time
													$compFillingToClassify = computeDiff($gfd['fld_DateFilled'],$gfd['fld_classification_ts']);
													$compClassifyToSubmission = computeDiff($gfd['fld_classification_ts'],$gfd['fld_se_dispdetails_ts']);
	
													//Active Phase
													$compSubmissionToVerification = computeDiff($gfd['fld_se_dispdetails_ts'],$gfd['fld_subjcode_ts']);
													$compVerificationToResolution = computeDiff($gfd['fld_subjcode_ts'],$gfd['fld_resolution_type_ts']);
	
													$downTime = $compFillingToClassify + $compClassifyToSubmission;
													//  + $compVerificationToResolution
													$activePhase = $compSubmissionToVerification;
	
													$displayDownTime = sprintf("%02d:%02d", floor($downTime/60), $downTime%60);
													$displayActivePhase = sprintf("%02d:%02d", floor($activePhase/60), $activePhase%60);
	
													// $displayFillingToClassify = floor($compFillingToClassify / 60).':'.($compFillingToClassify -   floor($compFillingToClassify / 60) * 60);
													$displayFillingToClassify = sprintf("%02d.%02d", floor($compFillingToClassify/60), $compFillingToClassify%60);
													$displayClassifyToSubmission = sprintf("%02d.%02d", floor($compClassifyToSubmission/60), $compClassifyToSubmission%60);
													$displaySubmissionToVerification = sprintf("%02d.%02d", floor($compSubmissionToVerification/60), $compSubmissionToVerification%60);
													$displayVerificationToResolution = sprintf("%02d.%02d", floor($compVerificationToResolution/60), $compVerificationToResolution%60);

													//Simple Dispute 3 days
													if($gfd['fld_dispute_classification'] == 1){
														if($activePhase <= 4320){
				
															$remarks = "Processed within the applicable time.";
														}elseif($activePhase > 4320){
															$remarks = "Not processed within the applicable time.";
														}

													//Complex Dispute 7 days
													}elseif($gfd['fld_dispute_classification'] == 2){
														if($activePhase <= 10080){
															$remarks = "Processed within the applicable time.";
														}elseif($activePhase > 10080){
															$remarks = "Not processed within the applicable time.";
														}
													//Highly Technical Dispute 20 days	
													}elseif($gfd['fld_dispute_classification'] == 3){
														if($activePhase <= 28800){
															$remarks = "Processed within the applicable time.";
														}elseif($activePhase > 28800){
															$remarks = "Not processed within the applicable time.";
														}
													}else{
														$remarks = "N/A";
													}
	
		
												
										?>
										<tr>
											<form method="post" action="main.php?nid=143&sid=0&rid=0">
												<td><?php echo $counter; ?></td>
												<!-- <input type="text" name="aeisctrlno" hidden value="<?php echo $row['fld_ctrlno'] ?>"> -->
												<!-- <td><?php echo $gfd['fld_id']; ?></td> -->
												<td><?php echo $gfd['firstname']. " " .$gfd['middlename']. " " .$gfd['lastname']; ?></td> 
												<td><?php echo $name; ?></td>
												<td><?php echo $filingDate ?></td>
												<td><?php echo $filingTime ?></td>
												<td><?php echo $emailNotif; ?></td>											
												<td><?php echo $submissionDate ; ?></td>
												<td><?php echo $submissionTime ; ?></td>
												<td><?php echo $classificationDate ; ?></td>
												<td><?php echo $classificationTime ; ?></td>
												<td><?php echo $verificationDate ; ?></td>
												<td><?php echo $verificationTime ; ?></td>
												<td><?php echo $disputeStatus ; ?></td>
												<td><?php echo $classificationType ; ?></td>
												<td><?php echo $resolutionType; ?></td>
												<td><?php echo $resolutionDate." ".$resolutionTime; ?></td>
												<td><?php echo $displayActivePhase." hr/s"; ?></td>
												<td><?php echo $displayDownTime. " hr/s"; ?></td>
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

<script>
window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 5000);

</script>

<?php
	// } else{
	// 	include("404.php");
	// }
?>

