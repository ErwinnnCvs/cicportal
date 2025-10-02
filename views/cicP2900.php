<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

	if ($_SESSION['name'] == "Karl Jorden") {
		include("404.php");
	} else {
		
	
?>
   

		<!-- Main content -->
		<section class="content">
					<form action="main.php?nid=29&sid=0&rid=0" method="post">
						<div class="row">
							<div class="col-md-12">
								<div class="card card-primary">
									<!-- /.card-header -->
									<div class="card-body">
										<!-- <div class="row">
											<div class="col-lg-2">
												<div class="form-group">
												</div>
											</div>
			                <div class="col-lg-3">
								<div class="form-group">
									<label>Select Financial Institution Type</label>
									<select class="form-control" name="selectFI" onchange="submit()">
									<?php
										$sql1=$dbh->query("SELECT substring(fld_code, 1, 2) AS cd FROM `tbfininst` GROUP BY substring(fld_code, 1, 2)");

										while($r=$sql1->fetch_array()){
											echo "<option value='".$r['cd']."'".$fsel[$r['cd']].">".$r['cd']." - ".$SE[$r['cd']]."</option>";
										}
									?>
									</select>
								</div>
							</div>
							<div class="col-lg-3">
								<div class="form-group">
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label>Search</label> <small>( Enter either Provider Code or Part of the Company Name )</small>
									<div class="input-group">
										<input type="text" name="txtSearch" class="form-control" placeholder="Search...">
											<span class="input-group-btn">
												<button type="submit" name="sbtSearch" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
											</button>
										</span>
									</div>
								</div>
							</div>
						</div> -->
					<?php
						$ayr = ((date("Y") - 2015) + 1) * 300;
					?>
						<div class="col-xs-12">
							<div class="box">
								<div class="box-header">
									<input type="hidden" name="FICode" value="<?php echo $f['fld_code']; ?>">
									<input type="hidden" name="UserCode" value="<?php echo $f['fld_assignedto']; ?>">
								</div>
								<!-- /.box-header -->
								<div class="box-body no-padding"><!-- table-responsive  -->
									<table class="table table-striped" id="compliance_monitoring_tool"> <!--  table-hover -->
										<thead>
											<tr>
												<th width="5%"><center>#</center></th>
												<th width="10%"><center>Provider Code</center></th>
												<th width="31%"><center>Financial Institution</center></th>
												<th width="9%"><center>Submission<br/>(Periodicity)</center></th>
												<th width="9%"><center>Submission<br/>(Completeness)<br/>(File)</center></th>
												<th width="9%"><center>Submission<br/>(Completeness)<br/>(Data)</center></th>
												<th width="9%"><center>Submission<br/>(Accuracy / Error Rate)</center></th>
												<th width="9%"><center>Submission<br/>Summary</center></th>
											</tr>
										</thead>
									<?php
										if($_POST['txtSearch']){
											// $srch = " AND (fld_code LIKE '%".$_POST['txtSearch']."%' OR fld_name LIKE '%".$_POST['txtSearch']."%')";
											$srch = " AND (AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) LIKE '%".$_POST['txtSearch']."%' OR AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) LIKE '%".$_POST['txtSearch']."%')";
				#											}elseif($_POST['selectFI']){
				#												$srch = " fld_code LIKE '%".$_POST['selectFI']."%'";
				#											}else{
				#												$srch = " fld_code = '000000'";
										}
										$rctr = key($_POST['navbutton']);
										// $sql1=$dbh->query("SELECT * FROM tbfininst WHERE fld_code LIKE '%".$_POST['selectFI']."%'".$srch." LIMIT ".key($_POST['navbutton']).", 20");
										$sql1=$dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_provcode, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_name FROM tbentities  WHERE (fld_registration_type = 0 OR fld_registration_type = 4 OR (fld_registration_type = 1 AND fld_noc_pass_status = 1))");
										while($r=$sql1->fetch_array()){
											$rctr++;
											if (date("w") <= 3) {
							                  $week_date = date("Y-m-d", strtotime("-1 week"));
							                }else{
							                  $week_date = date("Y-m-d");
							                }
							                $week_year = date("Y", strtotime($week_date));
							                $week = date("W", strtotime($week_date));

							        

											$folder = "https://www.creditinfo.gov.ph/cicportal/compliance".$week_year.str_pad( ($week), 2, "0", STR_PAD_LEFT)."/".MD5($r['fld_ctrlno'].$week_year.str_pad( ($week), 2, "0", STR_PAD_LEFT))."/";


											echo "<tr><td><center>".$rctr."</center></td><td><center>".$r['fld_provcode']."</center></td><td>".$r['fld_name'].$products."</td>"#<center>".$r['fld_status']."</center>  ".$crit[1][$r['fld_provcode']]."
												// ."<td><button type='button' class='btn btn-block btn-".$btn1."' data-toggle='modal' data-target='#myAccreditation".$r['fld_provcode']."'><i class='fa fa-".$chk1."'></i> ".$btnval1."</button></td>"
												// ."<td><button type='button' class='btn btn-block btn-".$btn2."' data-toggle='modal' data-target='#mySecurity".$r['fld_provcode']."'><i class='fa fa-".$chk2."'></i> ".$btnval2
												."</button></td>";
				#												if($testingstage){
				#													echo "<td colspan='4'><center>".$testingstage."</center></td>";
				#												}else{
												echo "<td><button type='button' class='btn btn-block' style='color: #FF6347;'><a href='".$folder."fileperiodicity.pdf' target='_blank' style='color: inherit;'>View</a></button></td>"
													."<td><button type='button' class='btn btn-block' style='color: #FFA500;'><a href='".$folder."filecompleteness_file.pdf' target='_blank' style='color: inherit;'>View</a></button></td>"
													."<td><button type='button' class='btn btn-block' style='color: #F1E90FF;'><a href='".$folder."filecompleteness.pdf' target='_blank' style='color: inherit;'>View</a></button></td>"
													."<td><button type='button' class='btn btn-block' style='color: #3CB371;'><a href='".$folder."filesubmitted.pdf' target='_blank' style='color: inherit;'>View</a></button></td>"
													."<td><button type='button' class='btn btn-block' style='color: #6A5ACD;'><a href='".$folder."submissionsummary.pdf' target='_blank' style='color: inherit;'>View</a> </button></td>";
				#												}
											echo "</tr>";
										}
									?>
									</table>
								</div>
								<!-- /.box-body -->
							</div>
							<!-- /.box -->
						</div>
			            </div>
			            <!-- /.box-body -->
			          </div>
			          <!-- /.box -->
			        </div>
			        <!-- /.col -->
			      </div>
			      <!-- /.row -->
						</form>
		</section>
<?php
	}
#	}else{
#		include("404.php");
#	}
?>
