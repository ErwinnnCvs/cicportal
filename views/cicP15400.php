<?php

	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);
	error_reporting(E_ALL);




	$timestamp = date("Y-m-d H:i:s");
	$filterYear = "2024";   

	if(isset($_POST['filterYear'])){
        $filterYear = $_POST['filterYear'];
    } 

	$start = date($filterYear."-01");
    $end = date($filterYear."-12-");



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
			
			
			<form method="post" action="main.php?nid=151sid=0&rid=0">
		<div class="col-lg-2">

		</div>
		</form>

					<!-- <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
						</ul> -->

						<div class="tab-content p-2">

							<div class="tab-pane active" id="tab_1">
								<table id="disputeSummary" class="table table-bordered table-responsive text-center">
									<thead>
								
										<tr>
							
											<th class="col-md-3" >Period Coverage</th>
											<th class="col-md-2">Processed within the applicable time</th>
											<th class="col-md-2 ">Not Processed within the applicable time</th>
											<th class="col-md-2">Total number of Disputes</th>

                      						
								
										</tr>
									</thead>
									<tbody>
										<?php

											$sql1 = $dbh2->query("SELECT count(fld_id) as ctrProcessedQ1 FROM `ingestion` WHERE fld_remarks = 'Processed within the applicable time' and fld_id < 817;" );
											$fetch1 = $sql1->fetch_array();

											$sql2 = $dbh2->query("SELECT count(fld_id) as ctrNotProcessedQ1  FROM `ingestion` WHERE fld_remarks = 'Not processed within the applicable time' and fld_id < 817;" );
											$fetch2 = $sql2->fetch_array();

											$sql3 = $dbh2->query("SELECT count(fld_id) as ctrInactionQ1 FROM `ingestion` WHERE fld_remarks LIKE '%inaction%' and fld_id < 817;" );
											$fetch3 = $sql3->fetch_array();


											$sql4 = $dbh2->query("SELECT count(fld_id) as ctrProcessedQ2  FROM `ingestion` WHERE fld_remarks = 'Processed within the applicable time' and fld_id >= 817 and fld_id <= 2325;" );
											$fetch4 = $sql4->fetch_array();

											$sql5 = $dbh2->query("SELECT count(fld_id) as ctrNotProcessedQ2  FROM `ingestion` WHERE fld_remarks = 'Not processed within the applicable time' and fld_id >= 817 and fld_id <= 2325;" );
											$fetch5 = $sql5->fetch_array();

											$sql6 = $dbh2->query("SELECT count(fld_id) as ctrInactionQ2 FROM `ingestion` WHERE fld_remarks LIKE '%inaction%' and fld_id >= 817 and fld_id <= 2325;" );
											$fetch6 = $sql6->fetch_array();


											$sql7 = $dbh2->query("SELECT count(fld_id) as ctrProcessedQ3  FROM `ingestion` WHERE fld_remarks = 'Processed within the applicable time' and fld_id >= 2326; " );
											$fetch7 = $sql7->fetch_array();


											$sql8 = $dbh2->query("SELECT count(fld_id) as ctrNotProcessedQ3  FROM `ingestion` WHERE fld_remarks = 'Not processed within the applicable time' and fld_id >= 2326; " );
											$fetch8 = $sql8->fetch_array();

											$sql9 = $dbh2->query("SELECT count(fld_id) as ctrInactionQ3 FROM `ingestion` WHERE fld_remarks LIKE '%inaction%' and fld_id >= 2326; " );
											$fetch9 = $sql9->fetch_array();

											$totalQ1 = $fetch1['ctrProcessedQ1'] + $fetch2['ctrNotProcessedQ1'] + $fetch3['ctrInactionQ1'];
											$totalQ2 = $fetch4['ctrProcessedQ2'] + $fetch5['ctrNotProcessedQ2'] + $fetch6['ctrInactionQ2'];
											$totalQ3 = $fetch7['ctrProcessedQ3'] + $fetch8['ctrNotProcessedQ3'] + $fetch9['ctrInactionQ3'];

											$totalProcessedQ1 =  $fetch1['ctrProcessedQ1'] + $fetch3['ctrInactionQ1'];
											$totalProcessedQ2 =  $fetch4['ctrProcessedQ2'] + $fetch6['ctrInactionQ2'];
											$totalProcessedQ3 =  $fetch7['ctrProcessedQ3'] + $fetch9['ctrInactionQ3'];

											//Processed Q4 2024
											$sql10 = $dbh2->query("SELECT count(*) as ctrProcessedQ4 from contract where is_processed = 1" );
											$fetch10 = $sql10->fetch_array();

											//Not Processed Q4 2024
											$sql11 = $dbh2->query("SELECT count(*) as ctrNotProcessedQ4 from contract where is_processed = 2" );
											$fetch11 = $sql11->fetch_array();

											//Inaction Q4 2024
											$sql12 = $dbh2->query("SELECT count(*) as ctrInactionQ4 from contract where is_processed = 3" );
											$fetch12 = $sql12->fetch_array();

											$totalProcessedQ4 = $fetch10['ctrProcessedQ4'] + $fetch12['ctrInactionQ4'];

											$totalQ4 = $fetch10['ctrProcessedQ4'] + $fetch11['ctrNotProcessedQ4'] +  $fetch12['ctrInactionQ4'] ;



						

										?>
										<tr>
											<td><?php echo '1st Quarter (January - March)'; ?></td>
											<td><?php echo $totalProcessedQ1 ?></td>
                                      		<td><?php echo $fetch2['ctrNotProcessedQ1'] ?></td>
                                            <td><?php echo $totalQ1 ?></td>
                                   						
										</tr>
										<tr>
											<td><?php echo '2nd Quarter (April - June)'; ?></td>
                                      		<td><?php echo $totalProcessedQ2 ?></td>
                                      		<td><?php echo $fetch5['ctrNotProcessedQ2'] ?></td>
                                      		<td><?php echo $totalQ2 ?></td>
										</tr>
										<tr>
											<td><?php echo '3rd Quarter (July - September)'; ?></td>
                                      		<td><?php echo $totalProcessedQ3 ?></td>
                                      		<td><?php echo $fetch8['ctrNotProcessedQ3'] ?></td>
                                      		<td><?php echo $totalQ3 ?></td>
										</tr>
											<tr>
											<td><?php echo '4th Quarter (October - December)'; ?></td>
                                      		<td><?php echo $totalProcessedQ4 ?></td>
                                      		<td><?php echo $fetch11['ctrNotProcessedQ4'] ?></td>
                                      		<td><?php echo $totalQ4 ?></td>
										</tr>



									</tbody>
								</table>
							</div>
						</div>
				</div>
    <!-- /.content -->
	</div>


