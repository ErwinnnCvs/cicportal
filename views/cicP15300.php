<?php

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
			
			
			<form method="post" action="main.php?nid=153sid=0&rid=0">
		<div class="col-lg-2">

		</div>
		</form>

					<!-- <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
						</ul> -->

						<div class="tab-content p-2">

							<div class="tab-pane active" id="tab_1">
								<table id="disputeIngestionQ3" class="table table-bordered table-responsive text-center">
									<thead>
								
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

						
											// c.fld_id > 9434
											$get_filed_disputes = $dbh2->query( "SELECT * from ingestion where fld_id > 2326; " );
											while ($gfd = $get_filed_disputes->fetch_array()) {

		
										?>
										<tr>
											<form method="post" action="main.php?nid=153&sid=0&rid=0">
												<td><?php echo $counter; ?></td>
												<td><?php echo $gfd['fld_disputer_name'] ?></td>
                                                <td><?php echo $gfd['fld_se_name'] ?></td>
                                                <td><?php echo $gfd['fld_filing_date'] ?></td>
                                                <td><?php echo $gfd['fld_filing_time'] ?></td>
                                                <td><?php echo $gfd['fld_se_notif_date'] ?></td>
                                                <td><?php echo $gfd['fld_se_sub_date'] ?></td>
                                                <td><?php echo $gfd['fld_se_sub_time'] ?></td>
                                                <td><?php echo $gfd['fld_dtr_verif_date'] ?></td>
                                                <td><?php echo $gfd['fld_dtr_verif_time'] ?></td>
                                                <td><?php echo $gfd['fld_status'] ?></td>
                                                <td><?php echo $gfd['fld_disp_type'] ?></td>
                                                <td><?php echo $gfd['fld_resolution_type'] ?></td>
                                                <td><?php echo $gfd['fld_resolution_date'] ?></td>
                                                <td><?php echo $gfd['fld_active_phase'] ?></td>
                                                <td><?php echo $gfd['fld_down_time'] ?></td>
                                                <td><?php echo $gfd['fld_remarks'] ?></td>

				
												
					
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


