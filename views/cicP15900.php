<?php
	if ($_SESSION['usertype'] == 0 || $_SESSION['usertype'] == 6 || $_SESSION['usertype'] == 21 || ($_SESSION['usertype'] == 22 && $_SESSION['user_id'] == 107)) {
	require_once 'classes/Auth.class.php';

?>

 
<!-- Card Body -->
	<div class="card-body">
		<div class="pad margin no-print">
			<div class="alert alert-info alert-dismissible" >
			<h4><i class="fa fa-info"></i> Note:</h4>
			Click the View button to open the details of the selected SE.
			</div>
		</div>

		
		<div class="card card-info p-1">
					<ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
							<li><a class="nav-link active"  href="#tab_1" data-toggle="tab" aria-selected="true">Pending</a></li>
							<li><a class="nav-link" href="#tab_3" data-toggle="tab" aria-selected="false">Completed</a></li>
						</ul>
						<div class="tab-content p-2">
							<div class="tab-pane active" id="tab_1">


								<table id="example1" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>#</th>
											<th>Control No.</th>
											<th>Non-Accessing Entity Name</th>
          						<th>Date & Time</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$counter = 1;
											$key = "RA3019";
											$sql = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_name, fld_type, fld_status, fld_aeisform_ts, fld_webops_ts, fld_aeismoa_ts, fld_aeissec_ts, fld_aesae_ts, fld_access_type, fld_registration_upload FROM tbentities WHERE fld_status = 0 and fld_registration_type = 15");
											while ($row = $sql->fetch_assoc()) {
                        						$date1 = strtotime($row['fld_registration_upload']);
										?>
										<tr>
											<form method="post" action="main.php?nid=159&sid=1&rid=0">
												<td><?php echo $counter; ?></td>
												<td><?php echo $row['fld_ctrlno']; ?><input type="text" name="aeisctrlno" hidden value="<?php echo $row['fld_ctrlno'] ?>"></td>
												<td><?php echo $row['fld_name']; ?></td>
                       							<td><?php echo date('F d, Y - h:ia', $date1); ?></td>
                       							<td style="width: 10%;"><button class="btn btn-warning btn-block" disabled>Pending</button></td>
												<td style="width: 6%;">
													<button class="btn btn-info btn-block" type="submit"><i class="fa fa-eye"></i> </button></center></td>
												</td>
												<?php $counter++; ?>
											</form>
										</tr>
										<?php
											}
										?>
									</tbody>
								</table>
							</div>
							<div class="tab-pane" id="tab_3">


								<table id="example3" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>#</th>
											<th>Control No.</th>
											<th>Non-Accessing Entity Name</th>
						                    <th>Date & Time</th>
						                    <th>Evaluated by</th>
						                    <th>Status</th>
						                    <th>Actions</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$counter = 1;

											$sql = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_name, fld_type, fld_status, fld_re_validation_by, fld_re_validation_ts FROM tbentities WHERE fld_status = 2 and fld_registration_type = 15");
											while ($row = $sql->fetch_assoc()) {
	                  							$date1 = strtotime($row['fld_re_validation_ts']);
											?>
											<tr>
												<form method="post" action="main.php?nid=159&sid=1&rid=0">
													<td><?php echo $counter; ?></td>
													<td><?php echo $row['fld_ctrlno']; ?><input type="text" name="aeisctrlno" hidden value="<?php echo $row['fld_ctrlno'] ?>"></td>
													<td><?php echo $row['fld_name']; ?></td>
							                        <td><?php echo date('F d, Y - h:ia', $date1); ?></td>
							                        <td><?php echo $row['fld_re_validation_by']; ?></td>
							                        <td style="width: 10%;"><button class="btn btn-success btn-block" disabled>Completed</button></td>
													<td style="width: 10%;"><button class="btn btn-info btn-block" type="submit"><i class="fa fa-folder-open"></i> View</button></td>
													<?php $counter++; ?>
												<!-- <td><button class="btn btn-success btn-block" type="submit">Completed</button></td> -->
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
  $(document).ready(function() {
    $('#example1').DataTable( {
        fixedHeader: true
    } );
} );
</script>

<script>
  $(document).ready(function() {
    $('#example2').DataTable( {
        fixedHeader: true
    } );
} );
</script>

<script>
  $(document).ready(function() {
    $('#example3').DataTable( {
        fixedHeader: true
    } );
} );
</script> 
<?php
	} else{
		include("404.php");
	}
?>
