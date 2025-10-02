<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);




	$timestamp = date("Y-m-d H:i:s");
	$filterYear = "2025";

	$prevYear = $filterYear - 1;
	$nextYear = $filterYear + 1;

	if(isset($_POST['filterYear'])){
        $filterYear = $_POST['filterYear'];
		$prevYear = $filterYear - 1;
		$nextYear = $filterYear + 1;
    } 

	// $start = date($filterYear."-01");
    // $end = date($filterYear."-12-");

	


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
				<h3 class="card-title">SM10 Quarterly Monitoring</h3>
			</div>	
				<form method="post">
					<div class="col-lg-2">
						<div class="form-group">
							<label class="col-form-label">Filter Dispute Filed Year</label>
							<select class="custom-select" name="filterYear" id="filterYear" onchange="this.form.submit()">
								<option value="<?php echo $filterYear; ?>"><?php echo $filterYear; ?></option>
								<?php
									$currentYear = (int)date('Y');
									for ($y = $currentYear; $y >= 2025; $y--) {
								?>
								<option value="<?php echo $y; ?>" <?php echo ($y == $filterYear) ? 'selected' : ''; ?>><?php echo $y; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</form>

					<!-- <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
						</ul> -->

						<div class="tab-content p-2">

							<div class="tab-pane active" id="tab_1">
								<table id="disputeSummary2" class="table table-bordered table-responsive text-center">
									<thead>
								
										<tr>
							
											<th class="col-md-3" >Period Coverage</th>
											<th class="col-md-2">Processed within the applicable time</th>
											<th class="col-md-2 ">Not Processed within the applicable time</th>
											<th class="col-md-2">Total number of Disputes</th>
											<th class="col-md-2">Percentage</th>

									

                      						
								
										</tr>
									</thead>
									<tbody>
										<?php

											// QUARTER 1
											$quarter1Processed = $dbh2->query("SELECT count(c.fld_id) AS countQ1Processed, c.is_processed AS process, s.fld_DateFilled FROM contract AS c JOIN subject AS s ON c.fld_TRN = s.fld_TRN WHERE (s.fld_DateFilled BETWEEN '".$filterYear."-01-01' AND '".$filterYear."-04-01') AND c.is_processed = 1;");
											$q1Processed = $quarter1Processed->fetch_array();	

											$quarter1NotProcessed = $dbh2->query("SELECT count(c.fld_id) AS countQ1NotProcessed, c.is_processed AS process, s.fld_DateFilled FROM contract AS c JOIN subject AS s ON c.fld_TRN = s.fld_TRN WHERE (s.fld_DateFilled BETWEEN '".$filterYear."-01-01' AND '".$filterYear."-04-01') AND (c.is_processed = 2 or c.is_processed = 4 );");
											$q1NotProcessed = $quarter1NotProcessed->fetch_array();

											$quarter1Inaction = $dbh2->query("SELECT count(c.fld_id) AS countQ1Inaction, c.is_processed AS process, s.fld_DateFilled FROM contract AS c JOIN subject AS s ON c.fld_TRN = s.fld_TRN WHERE (s.fld_DateFilled BETWEEN '".$filterYear."-01-01' AND '".$filterYear."-04-01') AND c.is_processed = 3;");
											$q1Inaction = $quarter1Inaction->fetch_array();

											// QUARTER 2
											$quarter2Processed = $dbh2->query("SELECT count(c.fld_id) AS countQ2Processed, c.is_processed AS process, s.fld_DateFilled FROM contract AS c JOIN subject AS s ON c.fld_TRN = s.fld_TRN WHERE (s.fld_DateFilled BETWEEN '".$filterYear."-03-31' AND '".$filterYear."-06-01') AND c.is_processed = 1;");
											$q2Processed = $quarter2Processed->fetch_array();	

											$quarter2NotProcessed = $dbh2->query("SELECT count(c.fld_id) AS countQ2NotProcessed, c.is_processed AS process, s.fld_DateFilled FROM contract AS c JOIN subject AS s ON c.fld_TRN = s.fld_TRN WHERE (s.fld_DateFilled BETWEEN '".$filterYear."-03-31' AND '".$filterYear."-06-01') AND (c.is_processed = 2 or c.is_processed = 4 );");
											$q2NotProcessed = $quarter2NotProcessed->fetch_array();

											$quarter2Inaction = $dbh2->query("SELECT count(c.fld_id) AS countQ2Inaction, c.is_processed AS process, s.fld_DateFilled FROM contract AS c JOIN subject AS s ON c.fld_TRN = s.fld_TRN WHERE (s.fld_DateFilled BETWEEN '".$filterYear."-03-31' AND '".$filterYear."-06-01') AND c.is_processed = 3;");
											$q2Inaction = $quarter2Inaction->fetch_array();	

											// QUARTER 3
											$quarter3Processed = $dbh2->query("SELECT count(c.fld_id) AS countQ3Processed, c.is_processed AS process, s.fld_DateFilled FROM contract AS c JOIN subject AS s ON c.fld_TRN = s.fld_TRN WHERE (s.fld_DateFilled BETWEEN '".$filterYear."-05-31' AND '".$filterYear."-09-01') AND c.is_processed = 1;");
											$q3Processed = $quarter3Processed->fetch_array();

											$quarter3NotProcessed = $dbh2->query("SELECT count(c.fld_id) AS countQ3NotProcessed, c.is_processed AS process, s.fld_DateFilled FROM contract AS c JOIN subject AS s ON c.fld_TRN = s.fld_TRN WHERE (s.fld_DateFilled BETWEEN '".$filterYear."-05-31' AND '".$filterYear."-09-01') AND (c.is_processed = 2 or c.is_processed = 4 );");
											$q3NotProcessed = $quarter3NotProcessed->fetch_array();

											$quarter3Inaction = $dbh2->query("SELECT count(c.fld_id) AS countQ3Inaction, c.is_processed AS process, s.fld_DateFilled FROM contract AS c JOIN subject AS s ON c.fld_TRN = s.fld_TRN WHERE (s.fld_DateFilled BETWEEN '".$filterYear."-05-31' AND '".$filterYear."-09-01') AND c.is_processed = 3;");
											$q3Inaction = $quarter3Inaction->fetch_array();

											// QUARTER 4
											$quarter4Processed = $dbh2->query("SELECT count(c.fld_id) AS countQ4Processed, c.is_processed AS process, s.fld_DateFilled FROM contract AS c JOIN subject AS s ON c.fld_TRN = s.fld_TRN WHERE (s.fld_DateFilled BETWEEN '".$filterYear."-08-31' AND '".$nextYear."-01-01') AND c.is_processed = 1;");
											$q4Processed = $quarter4Processed->fetch_array();
											
											$quarter4NotProcessed = $dbh2->query("SELECT count(c.fld_id) AS countQ4NotProcessed, c.is_processed AS process, s.fld_DateFilled FROM contract AS c JOIN subject AS s ON c.fld_TRN = s.fld_TRN WHERE (s.fld_DateFilled BETWEEN '".$filterYear."-08-31' AND '".$nextYear."-01-01') AND (c.is_processed = 2 or c.is_processed = 4 )");
											$q4NotProcessed = $quarter4NotProcessed->fetch_array();

											$quarter4Inaction = $dbh2->query("SELECT count(c.fld_id) AS countQ4Inaction, c.is_processed AS process, s.fld_DateFilled FROM contract AS c JOIN subject AS s ON c.fld_TRN = s.fld_TRN WHERE (s.fld_DateFilled BETWEEN '".$filterYear."-08-31' AND '".$nextYear."-01-01') AND c.is_processed = 3;");
											$q4Inaction = $quarter4Inaction->fetch_array();

											// TOTAL PROCESSED BY QUARTER
											$totalProcessedQ1 = $q1Processed['countQ1Processed'] + $q1Inaction['countQ1Inaction'];
											$totalProcessedQ2 = $q2Processed['countQ2Processed'] + $q2Inaction['countQ2Inaction'];
											$totalProcessedQ3 = $q3Processed['countQ3Processed'] + $q3Inaction['countQ3Inaction'];
											$totalProcessedQ4 = $q4Processed['countQ4Processed'] + $q4Inaction['countQ4Inaction'];

											// TOTAL PER QUARTER
											$totalQ1 = $q1Processed['countQ1Processed'] + $q1NotProcessed['countQ1NotProcessed'] + $q1Inaction['countQ1Inaction'];
											$totalQ2 = $q2Processed['countQ2Processed'] + $q2NotProcessed['countQ2NotProcessed'] + $q2Inaction['countQ2Inaction'];
											$totalQ3 = $q3Processed['countQ3Processed'] + $q3NotProcessed['countQ3NotProcessed'] + $q3Inaction['countQ3Inaction'];
											$totalQ4 = $q4Processed['countQ4Processed'] + $q4NotProcessed['countQ4NotProcessed'] + $q4Inaction['countQ4Inaction'];

											// TOTAL
											// $overallTotalProcessed = $totalProcessedQ1 + $totalProcessedQ2 + $totalProcessedQ3 + $totalProcessedQ4;
											// $overallTotalNotProcessed = $q1NotProcessed['countQ1NotProcessed'] + $q2NotProcessed['countQ2NotProcessed'] + $q3NotProcessed['countQ3NotProcessed'] + $q4NotProcessed['countQ4NotProcessed'];
											// $overallTotal = $totalQ1 + $totalQ2 + $totalQ3 + $totalQ4;

											$overallTotalProcessed = $totalProcessedQ1 + $totalProcessedQ3 + $totalProcessedQ4;
											$overallTotalNotProcessed = $q1NotProcessed['countQ1NotProcessed'] + $q3NotProcessed['countQ3NotProcessed'] + $q4NotProcessed['countQ4NotProcessed'];
											$overallTotal = $totalQ1  + $totalQ3 + $totalQ4;


											// PERCENTAGE PER QUARTER
												// quarter 1
												if ($totalQ1 == 0) {
													$percentageQ1 = 0;
												} else {
													$percentageQ1 = ($totalProcessedQ1 / $totalQ1) * 100;
												}
												// quarter 2
												if ($totalQ2 == 0) {
													$percentageQ2 = 0;
												} else {
													$percentageQ2 = ($totalProcessedQ2 / $totalQ2) * 100;
												}
												// quarter 3
												if ($totalQ3 == 0) {
													$percentageQ3 = 0;
												} else {
													$percentageQ3 = ($totalProcessedQ3 / $totalQ3) * 100;
												}
												// quarter 4
												if ($totalQ4 == 0) {
													$percentageQ4 = 0;
												} else {
													$percentageQ4 = ($totalProcessedQ4 / $totalQ4) * 100;
												}

											// OVERALL PERCENTAGE
											if ($overallTotal == 0) {
												$overallPercentage = 0;
											} else {
												$overallPercentage = ($overallTotalProcessed / $overallTotal) * 100;
											}
											

										?>
										<tr>
											<td><?php echo '1st Quarter (January - March)'; ?></td>
											<td><?php echo $totalProcessedQ1 ?></td>
                                      		<td><?php echo $q1NotProcessed['countQ1NotProcessed'] ?></td>
                                            <td><?php echo $totalQ1 ?></td>
											<td><?php echo number_format($percentageQ1, 2).'%' ?></td>
										</tr>
										<tr>
											<td><?php echo '2nd Quarter (April - June)'; ?></td>
                                      	<!-- 	<td><?php echo $totalProcessedQ2 ?></td>
                                      		<td><?php echo $q2NotProcessed['countQ2NotProcessed'] ?></td>
                                      		<td><?php echo $totalQ2 ?></td>
											<td><?php echo number_format($percentageQ2, 2).'%' ?></td> -->

											<td><?php echo '0' ?></td>
                                      		<td><?php echo '0' ?></td>
                                      		<td><?php echo '0' ?></td>
											<td><?php echo number_format('0', 2).'%' ?></td>
										</tr>
										<tr>
											<td><?php echo '3rd Quarter (July - September)'; ?></td>
                                      		<td><?php echo $totalProcessedQ3 ?></td>
                                      		<td><?php echo $q3NotProcessed['countQ3NotProcessed'] ?></td>
                                      		<td><?php echo $totalQ3 ?></td>
											<td><?php echo number_format($percentageQ3, 2).'%' ?></td>
										</tr>
										<tr>
											<td><?php echo '4th Quarter (October - December)'; ?></td>
                                      		<td><?php echo $totalProcessedQ4 ?></td>
                                      		<td><?php echo $q4NotProcessed['countQ4NotProcessed'] ?></td>
                                      		<td><?php echo $totalQ4 ?></td>
											<td><?php echo number_format($percentageQ4, 2).'%' ?></td>
										</tr>
										<tr>
											<td><?php echo 'Total' ?></td>
                                      		<td><?php echo '<b>'.$overallTotalProcessed.'</b>' ?></td>
                                      		<td><?php echo '<b>'.$overallTotalNotProcessed.'</b>' ?></td>
                                      		<td><?php echo '<b>'.$overallTotal.'</b>' ?></td>
											<td><?php echo '<b>'.number_format($overallPercentage, 2).'%</b>' ?></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
				</div>
    <!-- /.content -->
	</div>


