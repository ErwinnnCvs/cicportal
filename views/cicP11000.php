<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
	$thismonth = date("Y-m-01");
	$dates = [
		date("Y-m", strtotime($thismonth."-7 months")), 
		date("Y-m", strtotime($thismonth."-6 months")), 
		date("Y-m", strtotime($thismonth."-5 months")), 
		date("Y-m", strtotime($thismonth."-4 months")), 
		date("Y-m", strtotime($thismonth."-3 months")), 
		date("Y-m", strtotime($thismonth."-2 months")), 
		date("Y-m", strtotime($thismonth."-1 months"))
	];

	include("updatedate.php");
	$extdate = $updatedate;

	
	$sql0=$dbh1->query("SELECT fld_provcode, fld_date, fld_type, fld_amount FROM `tbloaded` WHERE (fld_date = '".$dates[0]."' OR fld_date = '".$dates[1]."' OR fld_date = '".$dates[2]."' OR fld_date = '".$dates[3]."' OR fld_date = '".$dates[4]."' OR fld_date = '".$dates[5]."' OR fld_date = '".$dates[6]."') GROUP BY fld_provcode, fld_date");
	while ($r = $sql0->fetch_array()) {
		$se[$r['fld_provcode']][$r['fld_date']] += $r['fld_amount'];
	}
	
	
	$sql = $dbh1->query("SELECT * FROM `tbpae` ORDER BY fld_date_listed DESC");
	while ($r = $sql->fetch_array()) {
		$rsrf = $dbh1->query("SELECT * FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$r['fld_provcode']."'");
		$rowrsrf = $rsrf->fetch_array();
		if ($rowrsrf['fld_ceportal_sent'] == 0) {
			$notyetapproved[] = $r['fld_provcode'];
		}else{
			$check_ce_registration = $dbh4->query("SELECT AES_DECRYPT(fld_sae, md5(CONCAT(fld_ctrlno, 'RA3019'))) as sae, fld_access_type, fld_aeis, fld_aeis_save_ts, fld_bill_status, fld_bill_emailsent, AES_DECRYPT(fld_bill_contact_fname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_bill_contact_fname, AES_DECRYPT(fld_bill_contact_lname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_bill_contact_lname, AES_DECRYPT(fld_bill_contact_mname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_bill_contact_mname, AES_DECRYPT(fld_bill_contact_sname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_bill_contact_sname, AES_DECRYPT(fld_bill_contact_email, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_bill_contact_email, AES_DECRYPT(fld_bill_email, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_bill_email, fld_bill_emailconfirmed, fld_aeis_com_status, fld_aeisform_ts, fld_aeismoa_ts, fld_webops_ts, fld_aeissec_ts, fld_aesae_ts, fld_moa_status, fld_sae_status, fld_sae_by, fld_sae_ts, fld_sae_reject_remarks, fld_sae_validation_ts, fld_sae_validation_by, fld_sae_validation_status FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$r['fld_provcode']."'");
			$ccr=$check_ce_registration->fetch_array();

			$statuses = $dbh1->query("SELECT * FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$r['fld_provcode']."'");
	        $rowstatus = $statuses->fetch_array();

			$completed = 0;
			if ($ccr['fld_access_type'] == 0) {
			} elseif ($ccr['fld_access_type'] == 1 and $ccr['fld_aeis'] == 1) {
				if ($rowstatus['fld_aeis_process'] == 4) {
				#Completed the CEIS Process
					$completed = 1;
				}
			} elseif ($ccr['fld_access_type'] == 2 and $ccr['sae']) {
				if ($ccr['fld_sae_status'] == 1) {
				#Completed the CEIS Process
					$completed = 1;
				}
			} elseif ($ccr['fld_access_type'] == 3 and $ccr['fld_aeis'] = 1) {
				if ($rowstatus['fld_aeis_process'] == 4) {
				#Completed the CEIS Process
					$completed = 1;
				}
			}
			if ($completed) {
				$approvedcomplete[] = $r['fld_provcode'];
			}else{
				$approvednotcomplete[] = $r['fld_provcode'];
			}
		}
	}
	

	
?>
  <!-- Content Wrapper. Contains page content -->
  
    <!-- Content Header (Page header) -->
    <!-- <section class="content-header">
			<h1>Potential Accessing Entities<small style="color: black;">Date last updated : <?php echo $extdate; ?></small></h1>
			<ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
				<li><i class="fa fa-dashboard"></i> Potential Accessing Entities</li>
			</ol>
		</section> -->

		<!-- Main content -->
		<section class="content">
			<form action="main.php?nid=110&sid=1&rid=0" method="post">

			<div class="row">
				<div class="col-md-12">
					<div class="">
						<!-- /.card-header -->
						
						<div class="card-body">
							<div class="col-xs-12">
								<div class="card">
									<div class="card-header">
										<div class="col-xs-6">
											<h3 class="card-title">Submitting Entities <small>( Pending validation of CEIS )</small></h3>
										</div>
										
										<input type="hidden" name="FICode" value="<?php echo $f['fld_code']; ?>">
										<input type="hidden" name="UserCode" value="<?php echo $f['fld_assignedto']; ?>">
									</div>
									<!-- /.card-header -->
									<div class="card-body table-reponsive"><!-- table-responsive  -->
										<table id="seis" class="table table-striped"> <!--  table-hover -->
											<thead>
												<tr style="background-color: #f9f9f9;">
													<!-- <th width="5" style="vertical-align: middle;"><center>#</center></th> -->
													<th width="10" style="vertical-align: middle;" class="no-sort"><center>Provider Code</center></th>
													<th width="27" style="vertical-align: middle;" class="no-sort"><center>Financial Institution</center></th>
													<th width="2" style="text-align:left;" class="no-sort"></th>
													<th width="10" style="text-align:left;" class="no-sort">Date Inserted</th>
													<th width="8" style="text-align:right;" class="no-sort"><?php echo date("M Y", strtotime($dates[0]));?></th>
													<th width="8" style="text-align:right;" class="no-sort"><?php echo date("M Y", strtotime($dates[1]));?></th>
													<th width="8" style="text-align:right;" class="no-sort"><?php echo date("M Y", strtotime($dates[2]));?></th>
													<th width="8" style="text-align:right;" class="no-sort"><?php echo date("M Y", strtotime($dates[3]));?></th>
													<th width="8" style="text-align:right;" class="no-sort"><?php echo date("M Y", strtotime($dates[4]));?></th>
													<th width="8" style="text-align:right;" class="no-sort"><?php echo date("M Y", strtotime($dates[5]));?></th>
													<th width="8" style="text-align:right;" class="no-sort"><?php echo date("M Y", strtotime($dates[6]));?></th>
												</tr>
											</thead>
											<tbody>
										<?php
											
											
											$ctr = 1;
											foreach ($notyetapproved as $key => $provcode) {
												$sqlpae = $dbh1->query("SELECT * FROM `tbpae` WHERE fld_provcode = '".$provcode."'");
												$rpae = $sqlpae->fetch_array();

												

												$rsrf = $dbh1->query("SELECT * FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$rpae['fld_provcode']."'");
												$rowrsrf = $rsrf->fetch_array();
												#SEIS NOC Credentials
												// if ($rowrsrf ['fld_noc_ts'] and $rowrsrf ['fld_noc_pass_status'] == 1) {
													$sql_ent=$dbh4->query("SELECT AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$rpae['fld_provcode']."'");
													$r_ent = $sql_ent->fetch_array();
													$name = $r_ent['name'];
												// }else{
													// $name = '';
												// }
												
												
												#Select New 7days
									        	$badgenew = "";
									        	if (date("Y-m-d", strtotime('-7 days')) < $rpae['fld_date_listed']) {
										        	$badgenew = ' <span class="badge badge-success" style="background-color: #048412;">New</span>';
										        }
											?>
												<tr>
													<!-- <td><center><?php echo $ctr++;?></center></td> -->
													<td><center><?php echo $rpae['fld_provcode'].$badgenew;?></center></td>
													<td><?php echo $name;?></td>
													<td><?php echo date("Y-m-d", strtotime($rpae['fld_date_listed']));?></td>
													<td><?php echo date("M j, Y", strtotime($rpae['fld_date_listed']));?></td>
													<td align="right"><?php echo number_format($se[$rpae['fld_provcode']][$dates[0]]);?></td>
													<td align="right"><?php echo number_format($se[$rpae['fld_provcode']][$dates[1]]);?></td>
													<td align="right"><?php echo number_format($se[$rpae['fld_provcode']][$dates[2]]);?></td>
													<td align="right"><?php echo number_format($se[$rpae['fld_provcode']][$dates[3]]);?></td>
													<td align="right"><?php echo number_format($se[$rpae['fld_provcode']][$dates[4]]);?></td>
													<td align="right"><?php echo number_format($se[$rpae['fld_provcode']][$dates[5]]);?></td>
													<td align="right"><?php echo number_format($se[$rpae['fld_provcode']][$dates[6]]);?></td>
												</tr>
											<?php
										        

											}

											

											
										?>
										</tbody>
										</table>
									</div>
									</div>
									<!-- /.card-body -->
								</div>
								<!-- /.card -->
							</div>
							
            </div>

            <div class="row">
				<div class="col-md-12">
					<div class="">
						
						<!-- /.card-header -->
						<div class="card-body ">
							<div class="col-xs-12">
								<div class="card">
									<div class="card-header">
										<div class="col-xs-6">
											<h3 class="card-title">Submitting Entities <small>( Approved )</small></h3>
										</div>
										
										<input type="hidden" name="FICode" value="<?php echo $f['fld_code']; ?>">
										<input type="hidden" name="UserCode" value="<?php echo $f['fld_assignedto']; ?>">
									</div>
									<!-- /.card-header -->
									<div class="card-body no-padding"><!-- table-responsive  -->
										<table id="seis2" class="table table-striped"> <!--  table-hover -->
											<thead>
												<tr style="background-color: #f9f9f9;">
													<th width="5" style="vertical-align: middle;"><center>#</center></th>
													<th width="10" style="vertical-align: middle;"><center>Provider Code</center></th>
													<th width="27" style="vertical-align: middle;"><center>Financial Institution</center></th>
													<th width="10" style="text-align:left;" class="no-sort">Date Inserted</th>
													<th width="8" style="text-align:right;"><?php echo date("M Y", strtotime($dates[0]));?></th>
													<th width="8" style="text-align:right;"><?php echo date("M Y", strtotime($dates[1]));?></th>
													<th width="8" style="text-align:right;"><?php echo date("M Y", strtotime($dates[2]));?></th>
													<th width="8" style="text-align:right;"><?php echo date("M Y", strtotime($dates[3]));?></th>
													<th width="8" style="text-align:right;"><?php echo date("M Y", strtotime($dates[4]));?></th>
													<th width="8" style="text-align:right;"><?php echo date("M Y", strtotime($dates[5]));?></th>
													<th width="8" style="text-align:right;"><?php echo date("M Y", strtotime($dates[6]));?></th>
												</tr>
											</thead>
											<tbody>
										<?php
											
											
											$ctr = 1;
											foreach ($approvednotcomplete as $key => $provcode) {
												$sqlpae = $dbh1->query("SELECT * FROM `tbpae` WHERE fld_provcode = '".$provcode."'");
												$rpae = $sqlpae->fetch_array();

												$sql_ent=$dbh4->query("SELECT AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$rpae['fld_provcode']."'");
												$r_ent = $sql_ent->fetch_array();
												$name = $r_ent['name'];


												#Select New 7days
									        	$badgenew = "";
									        	if (date("Y-m-d", strtotime('-7 days')) < $rpae['fld_date_listed']) {
										        	$badgenew = ' <span class="badge badge-success" style="background-color: #048412;">New</span>';
										        }
												
												?>
												<tr>
													<td><center><?php echo $ctr++;?></center></td>
													<td><center><?php echo $rpae['fld_provcode'].$badgenew;?></center></td>
													<td><?php echo $name;?></td>
													<td><?php echo date("M j, Y", strtotime($rpae['fld_date_listed']));?></td>
													<td align="right"><?php echo number_format($se[$rpae['fld_provcode']][$dates[0]]);?></td>
													<td align="right"><?php echo number_format($se[$rpae['fld_provcode']][$dates[1]]);?></td>
													<td align="right"><?php echo number_format($se[$rpae['fld_provcode']][$dates[2]]);?></td>
													<td align="right"><?php echo number_format($se[$rpae['fld_provcode']][$dates[3]]);?></td>
													<td align="right"><?php echo number_format($se[$rpae['fld_provcode']][$dates[4]]);?></td>
													<td align="right"><?php echo number_format($se[$rpae['fld_provcode']][$dates[5]]);?></td>
													<td align="right"><?php echo number_format($se[$rpae['fld_provcode']][$dates[6]]);?></td>
												</tr>

													<?php
											}
										

											
										?>
										</tbody>
										</table>
									</div>
									<!-- /.card-body -->
								</div>
								<!-- /.card -->
							</div>
           			 </div>



            <div class="row pb-10">
				<div class="col-md-12">
					<div class="">
				
						<!-- /.card-header -->
						<div class="card-body ">
							<div class="col-xs-12">
								<div class="card">
									<div class="card-header">
										<div class="col-xs-6">
											<h3 class="card-title">Accessing Entities <small>( With credentials )</small></h3>
										</div>
										
										<input type="hidden" name="FICode" value="<?php echo $f['fld_code']; ?>">
										<input type="hidden" name="UserCode" value="<?php echo $f['fld_assignedto']; ?>">
									</div>
									<!-- /.card-header -->
									<div class="card-body no-padding"><!-- table-responsive  -->
										<table id="seis3" class="table table-striped"> <!--  table-hover -->
											<thead>
												<tr style="background-color: #f9f9f9;">
													<th width="5" style="vertical-align: middle;"><center>#</center></th>
													<th width="10" style="vertical-align: middle;"><center>Provider Code</center></th>
													<th width="37" style="vertical-align: middle;"><center>Financial Institution</center></th>
													<th width="8" style="text-align:right;"><?php echo date("M Y", strtotime($dates[0]));?></th>
													<th width="8" style="text-align:right;"><?php echo date("M Y", strtotime($dates[1]));?></th>
													<th width="8" style="text-align:right;"><?php echo date("M Y", strtotime($dates[2]));?></th>
													<th width="8" style="text-align:right;"><?php echo date("M Y", strtotime($dates[3]));?></th>
													<th width="8" style="text-align:right;"><?php echo date("M Y", strtotime($dates[4]));?></th>
													<th width="8" style="text-align:right;"><?php echo date("M Y", strtotime($dates[5]));?></th>
													<th width="8" style="text-align:right;"><?php echo date("M Y", strtotime($dates[6]));?></th>
												</tr>
											</thead>
											<tbody>
										<?php


											$ctr = 1;
											foreach ($approvedcomplete as $key => $provcode) {
												$sqlpae = $dbh1->query("SELECT * FROM `tbpae` WHERE fld_provcode = '".$provcode."'");
												$rpae = $sqlpae->fetch_array();

												$sql_ent=$dbh4->query("SELECT AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$rpae['fld_provcode']."'");
												$r_ent = $sql_ent->fetch_array();
												$name = $r_ent['name'];
												
												?>
												<tr>
													<td><center><?php echo $ctr++;?></center></td>
													<td><center><?php echo $rpae['fld_provcode'];?></center></td>
													<td><?php echo $name;?></td>
													<td align="right"><?php echo number_format($se[$rpae['fld_provcode']][$dates[0]]);?></td>
													<td align="right"><?php echo number_format($se[$rpae['fld_provcode']][$dates[1]]);?></td>
													<td align="right"><?php echo number_format($se[$rpae['fld_provcode']][$dates[2]]);?></td>
													<td align="right"><?php echo number_format($se[$rpae['fld_provcode']][$dates[3]]);?></td>
													<td align="right"><?php echo number_format($se[$rpae['fld_provcode']][$dates[4]]);?></td>
													<td align="right"><?php echo number_format($se[$rpae['fld_provcode']][$dates[5]]);?></td>
													<td align="right"><?php echo number_format($se[$rpae['fld_provcode']][$dates[6]]);?></td>
												</tr>

													<?php
											}

											
										?>
									</tbody>
										</table>
									</div>
									<!-- /.card-body -->
							
								<!-- /.card -->
							</div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
			</form>
    </section>
    <!-- /.content -->

  <!-- /.content-wrapper -->

