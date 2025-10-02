<?php


clearstatcache();

if (!$_POST['yearmonth']) {
    $yrsel[date("Y-m-d", strtotime(date('Y-m-d', strtotime('last day of last month'))))] = ' selected';
}
$yrsel[$_POST['yearmonth']] = ' selected';

$file = $_GET['file'];
$remoteFile = "http://10.250.100.165/cicportal/PHPMailer/".$file;

$get_se_details = $dbh4->query("SELECT fld_accountno, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, AES_DECRYPT(fld_bill_contact_fname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_fname, AES_DECRYPT(fld_bill_contact_lname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_lname, AES_DECRYPT(fld_bill_email, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_email, fld_accountno FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$_GET['provcode']."' ");
$gsd=$get_se_details->fetch_array();

$pass = substr($gsd['fld_accountno'], -4).strtoupper(str_replace(' ', '', str_replace('-', '', str_replace('ñ', 'N', strtoupper($gsd['bill_contact_lname'])))));

$url_download = "http://10.250.100.165/cicportal/PHPMailer/soc_download.php?provcode=".$_GET['provcode']."&statementdate=".$_GET['statementdate']."&first=".$_GET['first']."&last=".$_GET['last'];
?>
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title"><?php echo $gsd['fld_accountno']. " " .$gsd['name']. "; <b>PASS: " .$pass; ?></b></h3>
      <div class="card-tools">
            <div class="btn-group">
              <button type="button" class="btn btn-tool" data-toggle="modal" data-target="#modal-default">
                <i class="fa fa-paper-plane"></i>
                Send to BCPP
              </button>

              <div class="modal fade" id="modal-default" aria-hidden="true" style="display: none;">
				<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header">
				<h4 class="modal-title">Mail</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span>
				</button>
				</div>
				<div class="modal-body">
				<p>Email will be send to:</p>
				<table class="table table-bordered">
					<tbody>
						<tr>
							<td>COMPANY:</td>
							<td><?php echo $gsd['name']; ?></td>
						</tr>
						<tr>
							<td>NAME:</td>
							<td><?php echo $gsd['bill_contact_fname']. " " .$gsd['bill_contact_lname']; ?></td>
						</tr>
						<tr>
							<td>EMAIL:</td>
							<td><?php echo $gsd['bill_email']; ?></td>
						</tr>
					</tbody>
					
				</table>
				<p>If the SOC is correct and there is no discrepancies, you may now click 'Confirm' button to resend the SOC to the SE's BCPP.</p>
				</div>
				<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<a href="http://10.250.100.165/cicportal/PHPMailer/soc_mailer_send.php?provcode=<?php echo $_GET['provcode']; ?>&last=<?php echo $_GET['last']; ?>" target="_blank" class="btn btn-primary">Confirm</a>
				</div>
				</div>

				</div>

				</div>
            </div>
      </div>
    </div>
    <div class="card-body">
        <?php

        // Remote file url
        
		// Open file
		$handle = @fopen($remoteFile, 'r');

		// Check if file exists
		if(!$handle){
        ?>
        <h1>FILE NOT FOUND</h1>
        Please regenerate.
        <a href="<?php echo $url_download; ?>" target="_blank" class="btn btn-primary"><i class="fa fa-undo"></i></a>
        <?php
        	} else {
        ?>
        <iframe src="<?php echo $remoteFile;?>" style="width:1300px; height:1500px;" frameborder="0"></iframe>
        <?php
        	}
        ?>
        
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->