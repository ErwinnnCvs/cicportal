<?php 

$provCode = base64_decode($_GET['enc']);
$dateMonth= base64_decode($_GET['dm']);
$submissionType = base64_decode($_GET['st']);
    $get_entity_details = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_provcode, 
    AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_name ,
    AES_DECRYPT(fld_lname_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_lname_ar, 
    AES_DECRYPT(fld_fname_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_fname_ar, 
    AES_DECRYPT(fld_mname_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_mname_ar, 
    AES_DECRYPT(fld_email_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_email_ar 
    FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019')))= '".$provCode."'");
    $ged = $get_entity_details->fetch_array();

?>



<div class="card-body">	        
		<div class="card card-info">
			<div class="card-header with-border"> 
			<h3 class="card-title">Submitting Entity Details</h3>
			</div>		
            <form method="post" >
        
        <div class="row p-3">

            <div class="col-md-6 col-lg-4">
                <div class="form-group">
                    <label for="input-mname" class="col-form-label">Provider Code</label>
                    <p><?php echo $provCode; ?></p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="form-group">
                    <label for="input-mname" class="col-form-label">Entity Name</label>
                    <p><?php echo $ged['fld_name'] ?></p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="form-group">
                    <label for="input-mname" class="col-form-label">Covered Date</label>
                    <p><?php echo date("F Y ", strtotime($dateMonth)) ?></p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="form-group">
                    <label for="input-mname" class="col-form-label">Authorized Representative</label>
                    <p><?php echo $ged['fld_fname_ar']." ".$ged['fld_mname_ar']." ".$ged['fld_lname_ar'] ?></p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="form-group">
                    <label for="input-mname" class="col-form-label">Email</label>
                    <p><?php echo $ged['fld_email_ar'] ?></p>
                </div>
            </div>
         
        </div>
    </div>
</div>


<div class="card-body">	        
		<div class="card card-info">
			<div class="card-header with-border"> 
			<h3 class="card-title">Submission Monitoring Details </h3>
			</div>		
        
				
                    <!-- action="main.php?nid=120&sid=0&rid=0" -->
                    <form method="post" >
        
						<div class="tab-content pt-2 pl-2 pr-2">
                         
							<div class="tab-pane active" id="tab_1">
								<table id="example1" class="table table-bordered text-center">
									<thead>
										<tr>
											<th>#</th>  
											<th>File Name</th>
                                            <th>Subjects</th>
                                            <th>Contracts </th>                  								
                                            <th>Date Covered</th>	
                                            <th>Filed Date</th>							
                        
									
										</tr>
									</thead>
									<tbody>
										<?php
                                        $counter = 1;


                                         // echo "SELECT * FROM tbtransmittal WHERE fld_provcode = '".$_GET['provCode']."' AND  fld_date_covered LIKE '%".$_GET['dateMonth']."%' ";
                                        // $sql2=$dbh->query("SELECT * FROM tbprodtickets WHERE fld_provcode = '".$_GET['provCode']."' AND fld_subject LIKE '%[CIC PROD]%' AND fld_created_time LIKE '".$_GET['dateMonth']."%' ");
                                        $sql2=$dbh4->query("SELECT * FROM tbtransmittal WHERE fld_provcode = '".$provCode."' AND fld_date_covered LIKE '%".$dateMonth."%' and fld_trans_type = '".$submissionType."'");

                                        while($r2=$sql2->fetch_array()){    
                                        
    
                                        
                                        
                                        if($r2['fld_filed_date_ts'] == "" ){
                                            $r2['fld_filed_date_ts'] = "N/A";
                                        }else{
                                            $r2['fld_filed_date_ts'] = date("F d, Y",strtotime($r2['fld_filed_date_ts']));
                                        }

                                        if($r2['fld_date_covered'] == "" ){
                                            $r2['fld_date_covered'] = "N/A";
                                        }else{
                                            $r2['fld_date_covered'] = date("F Y",strtotime($r2['fld_date_covered']));
                                        }
                                        
                                        //Date and Number Format
                                        $r2['fld_total_subjects'] = number_format($r2['fld_total_subjects']);
                                        $r2['fld_total_contracts'] = number_format($r2['fld_total_contracts']);
                                        
                                        
										?>
										<tr>
											
												<td><?php echo $counter; ?></td>
												<td><?php echo $r2['fld_filename'];?></td>
                                                <td><?php echo $r2['fld_total_subjects']; ?></td>
                                                <td><?php echo $r2['fld_total_contracts']; ?></td>
                                                <td><?php echo $r2['fld_date_covered'] ;?></td>	
                                                <td><?php echo $r2['fld_filed_date_ts']; ?></td>
                                  
							
												<?php $counter++; ?>
										
										</tr>
                                        
										<?php
										           }    
                                    
										?>
									</tbody>
								</table>
                                </form>
							</div>
						</div>
                   
				</div>
    <!-- /.content -->
	</div>

