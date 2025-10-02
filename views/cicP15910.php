<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript">
$(document).ready(function(){

    $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {

        localStorage.setItem('activeTab', $(e.target).attr('href'));

    });

    var activeTab = localStorage.getItem('activeTab');

    if(activeTab){

        $('#myTab a[href="' + activeTab + '"]').tab('show');

    }

});

</script>

<style>
.loader {
  position: fixed;
  left: 50%;
  top: 40%;
  z-index: 9999;
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid blue;
  border-bottom: 16px solid blue;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>

<?php
	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);
	error_reporting(E_ALL);


	if (isset($_POST['sbtYes'])) {

		if (empty($ged['fld_provcode'])) {
          $get_last_provider_code = $dbh1->query("SELECT * FROM tbprovider_codes ORDER BY fld_id DESC");
          $glpc=$get_last_provider_code->fetch_array();

          $add_provider_code = $glpc['fld_code'] + 10;

          if ($ged['fld_type'] == "PLI") {
            $type = "PF";
          }else {
            $type = $ged['fld_type'];
          }
          $new_provider_code = "NAE".$add_provider_code;
          $code = $controlno."RA3019";

          function generateRandomString($length) {
              $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
              $charactersLength = strlen($characters);
              $randomString = '';
              for ($i = 0; $i < $length; $i++) {
                  $randomString .= $characters[rand(0, $charactersLength - 1)];
              }
              return $randomString;
          }


          $join = preg_split("/[\s,_-]+/", $ged['fld_name']);
          $words = preg_replace('/[^a-zA-Z0-9]/', "", $join);
          $acronym = "";

          foreach ($words as $w) {
            $acronym .= $w[0];
          }
          if (strlen($acronym) > 4) {
            $new_mnemonic = strtoupper(substr($acronym, 0, 4));
          } elseif (strlen($acronym) < 4) {
            $number_to_add = 4 - strlen($acronym);
            $randomChar = generateRandomString($number_to_add);
            $new_mnemonic = strtoupper($acronym).$randomChar;
          } else {
            $new_mnemonic = strtoupper($acronym);
          }
        }



		$controlNo = $_POST['aeisctrlno'];
		$timestamp = date("Y-m-d H:i:s");
		if($update = $dbh4->query("UPDATE tbentities SET fld_provcode = AES_ENCRYPT('".$new_provider_code."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_status = 2, fld_re_validation_by = '".$_SESSION['name']."', fld_re_validation_ts = '".$timestamp."', fld_mnemonics = '".$new_mnemonic."' WHERE fld_ctrlno = '".$controlNo."';")) {
			
			
            $msg = "COMPLETED";

		} else {
			$msg = "ERROR";
		}
	}

	if ($_POST['sbtNo']) {
		$controlNo = $_POST['aeisctrlno'];
		$timestamp = date("Y-m-d H:i:s");
		if($update = $dbh4->query("UPDATE tbentities SET fld_status = 1, fld_re_validation_by = '".$_SESSION['name']."', fld_re_validation_ts = '".$timestamp."' WHERE fld_ctrlno = '".$controlNo."';")) {
			
			
            $msg = "COMPLETED";

		} else {
			$msg = "ERROR";
		}
	}
	
	$controlNo = $_POST['aeisctrlno'];
	// echo $controlNo;
	if (!$controlNo) {
		print_r($_SESSION);
		include("404.php");
	} else {
		

		$code = $controlNo.'RA3019';
    $access_type = $dbh4->query("SELECT fld_access_type FROM tbentities WHERE fld_ctrlno = '".$controlNo."'");
    $rowat = $access_type->fetch_array();
	

    if ($rowat['fld_access_type'] == 1 || $rowat['fld_access_type'] == 0) {
		$oprtrs = $dbh4->query("SELECT fld_oid, fld_ctrlno, AES_DECRYPT(fld_fname, MD5('".$code."')) AS fld_fname, AES_DECRYPT(fld_mname, MD5('".$code."')) AS fld_mname, AES_DECRYPT(fld_lname, MD5('".$code."')) AS fld_lname, AES_DECRYPT(fld_branchcode, MD5('".$code."')) AS fld_branchcode, AES_DECRYPT(fld_designation, MD5('".$code."')) AS fld_designation, AES_DECRYPT(fld_contactno, MD5('".$code."')) AS fld_contactno, AES_DECRYPT(fld_email, MD5('".$code."')) AS fld_email, fld_batch, fld_web, fld_status FROM tboperators WHERE fld_ctrlno = '".$controlNo."' and fld_web = 1");

	    // $reasonSelect["0"] = "Select Reasons";
	    $reasonSelect["0"] = "Incomplete documentary requirements";
	    $reasonSelect["1"] = "Discrepancy in the content's of the documents submitted";
	    $reasonSelect["2"] = "Others";
?>


		<!-- Main content -->
		<section class="content">
			<div class="row">
				<div class="col-md-12">
					<div class="loader"></div>
					<div class="card">
						<!-- <form method="post"> -->
						<div class="card-header">
							<h3 class="card-title p-2">Control # : <?php echo $_POST['aeisctrlno']; ?></h3>
							<div class="text-right">
									<?php

									echo "<input type='hidden' name='aeisctrlno' value='".$_POST['aeisctrlno']."'>";

									$selSQL = $dbh4->query("SELECT fld_status, fld_nae_docs_upload FROM tbentities WHERE fld_ctrlno = '".$_POST['aeisctrlno']."'");
									$ps = $selSQL->fetch_array();
									// if ($ps['fld_aeis_process'] == 0 && $_SESSION['usertype'] == 6 || $ps['fld_aeis_process'] == 2 && $_SESSION['usertype'] == 6 || $ps['fld_aeis_process'] == 0 && $_SESSION['usertype'] == 0 || $ps['fld_aeis_process'] == 2 && $_SESSION['usertype'] == 0) {
									if (($ps['fld_status'] == 0 && $_SESSION['usertype'] == 6 || ($_SESSION['usertype'] == 22 && $_SESSION['user_id'] == 107)) || ($ps['fld_status'] == 2 && $_SESSION['usertype'] == 6 || ($_SESSION['usertype'] == 22 && $_SESSION['user_id'] == 107)) || ($ps['fld_status'] == 0 && $_SESSION['usertype'] == 0) || ($ps['fld_status'] == 0 && $_SESSION['usertype'] == 0)) {
										if (empty($ps['fld_nae_docs_upload']) and $ps['fld_status'] == 1) {
											echo "<button class='btn btn-warning'>Incomplete Documents.</button><br>Remarks:";
											if (empty($gtof['fld_nae_docs_upload'])) {
												echo "<br>Missing NAEIS Contract";
											}
										} else {
											echo "<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modal-default'>Validate</button>";
										}
									} elseif ($ps['fld_status'] == 2) {
									
										echo "<input type='submit' class='btn btn-success' name='sbtViewRemarks' id='sbtViewRemarks' value='Completed'>";
									
									}

									?>

							</div>
						</div>

						<?php
							if (isset($msg)) {
						?>

						<div class="card-body">
							<div class="row">
								<div class="col-md-10">
								</div>
								<div class="col-md-10">
									<div class="card-body">
										<div class="alert alert-success">Control No: <?php echo $controlNo; ?> validation saved</div>
									</div>
								</div>
								<div class="col-md-10">
								</div>
							</div>
						</div>
						<?php
							}
						?>

						<form method="POST">
						 <div class="modal fade" id="modal-default">
					        <div class="modal-dialog">
					          <div class="modal-content">
					            <div class="modal-header">
					              <h4 class="modal-title">Validation</h4>
					              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					                <span aria-hidden="true">×</span>
					              </button>
					            </div>
					            <div class="modal-body">
					              Are documents complete and correct?
					              <input type="hidden" name="aeisctrlno" value="<?php echo $controlNo; ?>">
					            </div>
					            <div class="modal-footer justify-content-between">
					              <button type="submit" value="1" name="sbtNo" class="btn btn-default" data-dismiss="modal">No</button>
					              <button type="submit" value="1" name="sbtYes" class="btn btn-primary">Yes</button>
					            </div>
					          </div>
					          <!-- /.modal-content -->
					        </div>
					        <!-- /.modal-dialog -->
					      </div>
						</form>


						<div class="modal fade" id="validationsViewaeis" style="display: none;">
						  <div class="modal-dialog modal-lg">
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						          <span aria-hidden="true">×</span></button>
						        <h4 class="modal-title">Validation Details1</h4>
						      </div>
						      <div class="modal-body">
						      	<div class="nav-tabs-custom">
									<ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
										<li><a class="nav-link active" href="#svtab_1" data-toggle="tab">Documents</a></li>
										<li><a class="nav-link"  href="#svtab_2" data-toggle="tab">Call</a></li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="svtab_1">
											<table class="table table-bordered">
												<thead>
													<tr>
														<th><center>Date Time</center></th>
														<th><center>Reasons</center></th>
														<th><center>Remarks</center></th>
														<th><center>Validated by</center></th>
													</tr>
												</thead>
												<tbody id="viewtableDocsaeis">

												</tbody>
											</table>
										</div>
										<div class="tab-pane" id="svtab_2">
											<div class="table-responsive">
											<table class="table table-bordered table-striped">
												<thead>
													<tr>
														<!-- <th>Date</th> -->
														<th><center>Phone</center></th>
														<th><center>Name</center></th>
														<th><center>Start of Call</center></th>
														<th><center>End of Call</center></th>
														<th><center>Remarks</center></th>
														<th><center>Called by</center></th>
													</tr>
												</thead>
												<tbody id="viewcallBodyaeis">
												</tbody>
											</table>
											</div>
										</div>
									</div>
								</div>
						      </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
						      </div>
						    </div>
						    <!-- /.modal-content -->
						  </div>
						  <!-- /.modal-dialog -->
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-6">
									<div class="nav-tabs-custom">
									<ul class="nav nav-tabs" id="myTab custom-tabs-four-tab" role="tablist">
											<li><a class="nav-link active" href="#tab_1" data-toggle="tab">NAEIS</a></li>
										</ul>
										<div class="tab-content">
											<div class="tab-pane active" id="tab_1">
												<?php
													$controlNo = $_POST['aeisctrlno'];
													$code = $controlNo.'RA3019';
													$sql=$dbh4->query("SELECT fld_ctrlno,AES_DECRYPT(fld_tinno,MD5('".$code."')) AS fld_tinno, AES_DECRYPT(fld_compregno,MD5('".$code."')) AS fld_compregno, fld_compregtype, AES_DECRYPT(fld_name,MD5('".$code."')) AS fld_name, fld_type, AES_DECRYPT(fld_landline,MD5('".$code."')) AS fld_landline, fld_zip,
													AES_DECRYPT(fld_lname_ar,MD5('".$code."')) AS fld_lname_ar,
													AES_DECRYPT(fld_fname_ar,MD5('".$code."')) AS fld_fname_ar,
													AES_DECRYPT(fld_mname_ar,MD5('".$code."')) AS fld_mname_ar,
													AES_DECRYPT(fld_provcode,MD5('".$code."')) AS fld_provcode,
													AES_DECRYPT(fld_addr_number, MD5('".$code."')) as fld_addr_number,
													AES_DECRYPT(fld_addr_street, MD5('".$code."')) as fld_addr_street,
													AES_DECRYPT(fld_addr_subdv, MD5('".$code."')) as fld_addr_subdv,
													AES_DECRYPT(fld_addr_brgy, MD5('".$code."')) as fld_addr_brgy,
													AES_DECRYPT(fld_addr_city, MD5('".$code."')) as fld_addr_city,
													AES_DECRYPT(fld_addr_province, MD5('".$code."')) as fld_addr_province,
													AES_DECRYPT(fld_addr_region, MD5('".$code."')) as fld_addr_region,
													AES_DECRYPT(fld_extname_ar,MD5('".$code."')) AS fld_extname_ar,
													AES_DECRYPT(fld_position_ar,MD5('".$code."')) AS fld_position_ar,
													AES_DECRYPT(fld_contactno_ar,MD5('".$code."')) AS fld_contactno_ar,
													AES_DECRYPT(fld_landline_ar,MD5('".$code."')) AS fld_landline_ar,
													AES_DECRYPT(fld_email_ar,MD5('".$code."')) AS fld_email_ar,
													AES_DECRYPT(fld_name_c1,MD5('".$code."')) AS fld_name_c1,
													AES_DECRYPT(fld_position_c1,MD5('".$code."')) AS fld_position_c1,
													AES_DECRYPT(fld_contactno_c1,MD5('".$code."')) AS fld_contactno_c1,
													AES_DECRYPT(fld_landline_c1,MD5('".$code."')) AS fld_landline_c1,
													AES_DECRYPT(fld_email_c1,MD5('".$code."')) AS fld_email_c1,
													AES_DECRYPT(fld_name_c2,MD5('".$code."')) AS fld_name_c2,
													AES_DECRYPT(fld_position_c2,MD5('".$code."')) AS fld_position_c2,
													AES_DECRYPT(fld_contactno_c2,MD5('".$code."')) AS fld_contactno_c2,
													AES_DECRYPT(fld_landline_c2,MD5('".$code."')) AS fld_landline_c2,
													AES_DECRYPT(fld_email_c2,MD5('".$code."')) AS fld_email_c2,
													AES_DECRYPT(fld_head_fname,MD5('".$code."')) AS fld_head_fname,
													AES_DECRYPT(fld_head_mname, MD5('".$code."')) AS fld_head_mname,
													AES_DECRYPT(fld_head_lname, MD5('".$code."')) AS fld_head_lname,
													AES_DECRYPT(fld_head_extname, MD5('".$code."')) AS fld_head_extname,
													AES_DECRYPT(fld_head_position,MD5('".$code."')) AS fld_head_position,
													AES_DECRYPT(fld_head_email,MD5('".$code."')) AS fld_head_email,
													AES_DECRYPT(fld_ip,MD5('".$code."')) AS fld_ip,
													AES_DECRYPT(fld_disp_email,MD5('".$code."')) AS fld_disp_email,
													AES_DECRYPT(fld_disp_contact_name,MD5('".$code."')) AS fld_disp_contact_name,
													AES_DECRYPT(fld_disp_contact_email,MD5('".$code."')) AS fld_disp_contact_email,
													AES_DECRYPT(fld_bill_email,MD5('".$code."')) AS fld_bill_email,
													AES_DECRYPT(fld_bill_contact_fname,MD5('".$code."')) AS fld_bill_contact_fname,
													AES_DECRYPT(fld_bill_contact_mname,MD5('".$code."')) AS fld_bill_contact_mname,
													AES_DECRYPT(fld_bill_contact_lname,MD5('".$code."')) AS fld_bill_contact_lname,
													AES_DECRYPT(fld_bill_contact_sname,MD5('".$code."')) AS fld_bill_contact_sname,
													AES_DECRYPT(fld_bill_landline,MD5('".$code."')) AS fld_bill_landline,
													AES_DECRYPT(fld_bill_landline_code,MD5('".$code."')) AS fld_bill_landline_code,
													AES_DECRYPT(fld_bill_landline_local,MD5('".$code."')) AS fld_bill_landline_local,
													AES_DECRYPT(fld_bill_mobile,MD5('".$code."')) AS fld_bill_mobile,
													AES_DECRYPT(fld_bill_contact_email,MD5('".$code."')) AS fld_bill_contact_email,
													AES_DECRYPT(fld_bill_status,MD5('".$code."')) AS fld_bill_status,
													fld_numacct_date,
													fld_numacct_indv,
													fld_numacct_comp,
													fld_numacct_inst,
													fld_numacct_noninst,
													fld_numacct_cc,
													fld_numacct_util,
													fld_sae_upload
													FROM tbentities WHERE fld_ctrlno = '".$controlNo."'");

													$row = $sql->fetch_assoc();
												?>
												<div class="row">
													<div class="col-md-8">
														<label>CIC Control Number</label>
														<p><?php echo $row['fld_ctrlno']; ?></p>
													</div>
													<!-- <div class="vl"></div> -->
													<div class="col-md-4">
														<label>Provider Code</label>

														<p><?php echo $row['fld_provcode']; ?></p>
													</div>
												</div>
												<hr>
												<div class="row">
													<div class="col-md-8">
														<label>Non-Accessing Entity Name</label>
														<p><?php echo $row['fld_name']; ?></p>
													</div>
												</div>
											
												<hr>
												
												<div class="page-header">
													PRIMARY CONTACT PERSON
												</div>
												<div class="row">
													<div class="col-md-8">
														<label>Name</label>
														<p><?php echo $row['fld_name_c1']; ?></p>
													</div>
													<div class="col-md-4">
														<label>Direct Line</label>
														<p><?php echo $row['fld_landline_c1']; ?></p>
													</div>
												</div>
												<div class="row">
													<div class="col-md-4">
														<label>Position</label>
														<p><?php echo $row['fld_position_c1']; ?></p>
													</div>
													<div class="col-md-4">
														<label>Email</label>
														<p><?php echo $row['fld_email_c1']; ?></p>
													</div>
													<div class="col-md-4">
														<label>Mobile Number</label>
														<p><?php echo $row['fld_contactno_c1']; ?></p>
													</div>
												</div>
												<hr>
												<div class="page-header">
													AUTHORIZED PERSON
												</div>
												<div class="row">
													<div class="col-md-8">
														<label>Name</label>
														<p><?php echo $row['fld_fname_ar']. " " .$row['fld_lname_ar']; ?></p>
													</div>
													<div class="col-md-4">
														<label>Direct Line</label>
														<p><?php echo $row['fld_landline_ar']; ?></p>
													</div>
												</div>
												<div class="row">
													<div class="col-md-4">
														<label>Position</label>
														<p><?php echo $row['fld_position_ar']; ?></p>
													</div>
													<div class="col-md-4">
														<label>Email</label>
														<p><?php echo $row['fld_email_ar']; ?></p>
													</div>
													<div class="col-md-4">
														<label>Mobile Number</label>
														<p><?php echo $row['fld_contactno_ar']; ?></p>
													</div>
												</div>
											</div>
											
										</div>
									</div>

								</div>
								<div class="col-md-6">
									<h3>NAEIS Documents</h3>
									<iframe src="<?php echo "https://www.creditinfo.gov.ph/saeportal/uploads/".$row['fld_sae_upload']."_".$row['fld_ctrlno']."_TUP.pdf"; ?>" width="100%" height="700px"></iframe>

									<iframe src="<?php echo "https://www.creditinfo.gov.ph/saeportal/uploads/".$row['fld_sae_upload']."_".$row['fld_ctrlno']."_data_architecture_doc.pdf"; ?>" width="100%" height="700px"></iframe>
									
									<iframe src="<?php echo "https://www.creditinfo.gov.ph/saeportal/uploads/".$row['fld_sae_upload']."_".$row['fld_ctrlno']."_msa_agreement_doc.pdf"; ?>" width="100%" height="700px"></iframe>

									<iframe src="<?php echo "https://www.creditinfo.gov.ph/saeportal/uploads/".$row['fld_sae_upload']."_".$row['fld_ctrlno']."_product_details_doc.pdf"; ?>" width="100%" height="700px"></iframe>

									<iframe src="<?php echo "https://www.creditinfo.gov.ph/saeportal/uploads/".$row['fld_sae_upload']."_".$row['fld_ctrlno']."_is_policy_doc.pdf"; ?>" width="100%" height="700px"></iframe>

									<iframe src="<?php echo "https://www.creditinfo.gov.ph/saeportal/uploads/".$row['fld_sae_upload']."_".$row['fld_ctrlno']."_npc_doc.pdf"; ?>" width="100%" height="700px"></iframe>

									<iframe src="<?php echo "https://www.creditinfo.gov.ph/saeportal/uploads/".$row['fld_sae_upload']."_".$row['fld_ctrlno']."_consent_doc.pdf"; ?>" width="100%" height="700px"></iframe>
									
									<br>


								</div>
							</div>
						</div>

		</section>
    <!-- /.content -->
  </div>
  <?php
      } elseif($rowat['fld_access_type'] == 2) {
        // $reasonSelect["0"] = "Select Reasons";
  	    $reasonSelect["0"] = "Incomplete documentary requirements";
  	    $reasonSelect["1"] = "Discrepancy in the content's of the documents submitted";
  	    $reasonSelect["2"] = "Others";

        if (isset($_POST['sbtProcess'])) {
    			$timestamp = date("Y-m-d H:i:s");
    			if($update = $dbh1->query("UPDATE tbentities SET fld_aeis_process = 1, fld_aeis_ds_completed_by = '".$_SESSION['name']."', fld_aeis_ds_completed_ts = '".$timestamp."' WHERE fld_ctrlno = '".$controlNo."';")) {
    				$dbh4->query("UPDATE tbentities SET fld_aeis_com_status = 3 WHERE fld_ctrlno = '".$controlNo."'");
          }
        }
  ?>


  <?php
      } elseif ($rowat['fld_access_type'] == 3) {
      	$oprtrs = $dbh4->query("SELECT fld_oid, fld_ctrlno, AES_DECRYPT(fld_fname, MD5('".$code."')) AS fld_fname, AES_DECRYPT(fld_mname, MD5('".$code."')) AS fld_mname, AES_DECRYPT(fld_lname, MD5('".$code."')) AS fld_lname, AES_DECRYPT(fld_branchcode, MD5('".$code."')) AS fld_branchcode, AES_DECRYPT(fld_designation, MD5('".$code."')) AS fld_designation, AES_DECRYPT(fld_contactno, MD5('".$code."')) AS fld_contactno, AES_DECRYPT(fld_email, MD5('".$code."')) AS fld_email, fld_batch, fld_web, fld_status FROM tboperators WHERE fld_ctrlno = '".$controlNo."' and fld_web = 1");
      	
      	if (isset($_POST['sbtProcess'])) {
			$timestamp = date("Y-m-d H:i:s");
			if($update = $dbh1->query("UPDATE tbentities SET fld_aeis_process = 1, fld_aeis_ds_completed_by = '".$_SESSION['name']."', fld_aeis_ds_completed_ts = '".$timestamp."' WHERE fld_ctrlno = '".$controlNo."';")) {
				$dbh4->query("UPDATE tbentities SET fld_aeis_com_status = 3 WHERE fld_ctrlno = '".$controlNo."'");
				while ($op = $oprtrs->fetch_array()) {

					$dbh1->query("INSERT INTO tboperators (fld_oid, fld_ctrlno, fld_fname, fld_mname, fld_lname, fld_branchcode, fld_designation, fld_contactno, fld_email, fld_batch, fld_web, fld_status) VALUES ('".$op['fld_oid']."', '".$controlNo."', AES_ENCRYPT('".$op['fld_fname']."', MD5('".$code."')), AES_ENCRYPT('".$op['fld_mname']."', MD5('".$code."')), AES_ENCRYPT('".$op['fld_lname']."', MD5('".$code."')), AES_ENCRYPT('".$op['fld_branchcode']."', MD5('".$code."')), AES_ENCRYPT('".$op['fld_designation']."', MD5('".$code."')), AES_ENCRYPT('".$op['fld_contactno']."', MD5('".$code."')), AES_ENCRYPT('".$op['fld_email']."', MD5('".$code."')), '".$op['fld_batch']."', '".$op['fld_web']."', '".$op['fld_status']."')");
				}
				$sql3 = $dbh1->query("SELECT AES_DECRYPT(fld_name, MD5('".$code."')) AS fld_name, fld_type, fld_ds_eval, fld_provcode FROM tbentities WHERE fld_ctrlno = '".$controlNo."'");

                $row2 = $sql3->fetch_array();

                $check_mnemonic = $dbh4->query("SELECT fld_mnemonics FROM tbentities WHERE fld_ctrlno = '".$controlNo."'");
                $cm = $check_mnemonic->fetch_array();

                $entity= $row2['fld_name'];

                $se_type = $row2['fld_type'];


                $sql4 = $dbh1->query("SELECT fld_oid, AES_DECRYPT(fld_fname, MD5('".$code."')) AS fld_fname,  AES_DECRYPT(fld_mname, MD5('".$code."')) AS fld_mname, AES_DECRYPT(fld_lname, MD5('".$code."')) AS fld_lname FROM tboperators WHERE fld_ctrlno = '".$controlNo."' and fld_web = 1");

                while ($row3=$sql4->fetch_array()) {

                    $first = $row3['fld_fname'];
                    $middle = $row3['fld_mname'];
                    $last = $row3['fld_lname'];

                    $id = $row3['fld_oid'];

                    $f = substr($first, 0, 1);
                    if ($middle == NULL) {
                        $m = "N";
                    } else {
                        $m = substr($middle, 0, 1);
                    }

                    $l = substr($last, 0, 1);


                    $initials = $f.$m.$l;

                    #FOR USERNAME
                    $username = $cm['fld_mnemonics']."2".$initials;

                    $dbh1->query("UPDATE tboperators SET fld_username = '".$username."' WHERE fld_ctrlno = '".$controlNo."' and fld_web = 1 and fld_oid = '".$id."'");
                }
                $key = $controlNo."RA3019";

                $dbh1->query("UPDATE tbentities SET fld_aeis_noc_status = 0 WHERE fld_ctrlno = '".$controlNo."'");

                $sql4 = $dbh1->query("SELECT fld_ctrlno, fld_noc_mnemonic, AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_provcode, AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_name FROM tbentities WHERE fld_ctrlno = '".$controlNo."'");

                while ($row2 = $sql4->fetch_array()) {
                    $op = $dbh1->query("SELECT AES_DECRYPT(fld_fname, md5('".$key."')) AS fld_fname, AES_DECRYPT(fld_mname, md5('".$key."')) AS fld_mname, AES_DECRYPT(fld_lname, md5('".$key."')) fld_lname, AES_DECRYPT(fld_email, md5('".$key."')) fld_email, fld_username FROM tboperators WHERE fld_ctrlno = '".$row2['fld_ctrlno']."' and fld_web = 1");

                    while ($rowop = $op->fetch_array()) {
                        $sql5 = $dbh1->query("INSERT INTO tbnocusers (fld_ctrlno, fld_noc_mnemonic, fld_provcode, fld_username, fld_fname, fld_mname, fld_lname, fld_email, fld_name, fld_status, fld_web) VALUES ('".$row2['fld_ctrlno']."', '".$row2['fld_noc_mnemonic']."', '".$row2['fld_provcode']."', '".$rowop['fld_username']."', '".$rowop['fld_fname']."', '".$rowop['fld_mname']."', '".$rowop['fld_lname']."', '".$rowop['fld_email']."', '".$row2['fld_name']."', 0, 1)");
                    }
                }
                $msg = "COMPLETED";

			} else {
				$msg = "ERROR";
			}
		}
        // $reasonSelect["0"] = "Select Reasons";
  	    $reasonSelect["0"] = "Incomplete documentary requirements";
  	    $reasonSelect["1"] = "Discrepancy in the content's of the documents submitted";
  	    $reasonSelect["2"] = "Others";
  ?>



  <?php
      }
  	}
  ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script type="text/javascript">
$(window).load(function() {
    $(".loader").fadeOut("fast");
});
</script>

<script>
	// Restricts input for each element in the set of matched elements to the given inputFilter.
	(function($) {
	  $.fn.inputFilter = function(inputFilter) {
	    return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
	      if (inputFilter(this.value)) {
	        this.oldValue = this.value;
	        this.oldSelectionStart = this.selectionStart;
	        this.oldSelectionEnd = this.selectionEnd;
	      } else if (this.hasOwnProperty("oldValue")) {
	        this.value = this.oldValue;
	        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
	      }
	    });
	  };
	}(jQuery));

	$("#aeisphonenumber").inputFilter(function(value) {
  	return /^\d*$/.test(value); });
</script>
