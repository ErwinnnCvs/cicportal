<?php

ini_set('memory_limit','85M');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Manila');

$api_key = "xVfqn6csPjVKD0QQkR";
$password = "password123123";
$yourdomain = "creditinfoph";

$url = 'https://creditinfoph.freshdesk.com/api/v2/tickets/'.$_GET['ticket'];
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$password");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$server_output = curl_exec($ch);
$info = curl_getinfo($ch);
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($server_output, 0, $header_size);
$response = substr($server_output, $header_size);
if($info['http_code'] == 200) {

  $obj = json_decode($response, true);
  // print_r($obj);

  // print_r($obj);

} else {
  if($info['http_code'] == 404) {
  echo "Error, Please check the end point \n";
  } else {
  echo "Error, HTTP Status Code : " . $info['http_code'] . "\n";
  echo "Headers are ".$headers;
  echo "Response are ".$response;
  }
}
curl_close($ch);

$get_ticket_details = $dbh->query("SELECT * FROM tbprodtickets WHERE fld_id = ".$_GET['ticket']);
$gtd=$get_ticket_details->fetch_array();

$get_count_of_all_tickets = $dbh->query("SELECT COUNT(*) as cnt FROM tbprodtickets WHERE YEAR(fld_created_time) >= 2024;");
$gcoat=$get_count_of_all_tickets->fetch_array();

?>
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <section class="content">
<div class="container-fluid">
<div class="row">
<div class="col-md-3">
<a href="main.php?nid=130&sid=0&rid=0" class="btn btn-primary btn-block mb-3">Back to Tickets</a>
<div class="card">
<div class="card-header">
<h3 class="card-title">Folders</h3>
<div class="card-tools">
<button type="button" class="btn btn-tool" data-card-widget="collapse">
<i class="fas fa-minus"></i>
</button>
</div>
</div>
<div class="card-body p-0">
<ul class="nav nav-pills flex-column">
<li class="nav-item active">
<a href="main.php?nid=130&sid=0&rid=0" class="nav-link">
<i class="fas fa-inbox"></i> Tickets
<span class="badge bg-primary float-right"><?php echo number_format($gcoat['cnt']); ?></span>
</a>
</li>
<!-- <li class="nav-item">
<a href="#" class="nav-link">
<i class="far fa-envelope"></i> Sent
</a>
</li>
<li class="nav-item">
<a href="#" class="nav-link">
<i class="far fa-file-alt"></i> Drafts
</a>
</li>
<li class="nav-item">
<a href="#" class="nav-link">
<i class="fas fa-filter"></i> Junk
<span class="badge bg-warning float-right">65</span>
</a>
</li>
<li class="nav-item">
<a href="#" class="nav-link">
<i class="far fa-trash-alt"></i> Trash
</a>
</li> -->
</ul>
</div>

</div>

<!-- <div class="card">
<div class="card-header">
<h3 class="card-title">Labels</h3>
<div class="card-tools">
<button type="button" class="btn btn-tool" data-card-widget="collapse">
<i class="fas fa-minus"></i>
</button>
</div>
</div>

<div class="card-body p-0">
<ul class="nav nav-pills flex-column">
<li class="nav-item">
<a class="nav-link" href="#"><i class="far fa-circle text-danger"></i> Important</a>
</li>
<li class="nav-item">
<a class="nav-link" href="#"><i class="far fa-circle text-warning"></i> Promotions</a>
</li>
<li class="nav-item">
<a class="nav-link" href="#"><i class="far fa-circle text-primary"></i> Social</a>
</li>
</ul>
</div>

</div> -->

</div>

<div class="col-md-9">
<div class="card card-primary card-outline">
<div class="card-header">
<h3 class="card-title">Read Ticket</h3>
<div class="card-tools">
<a href="#" class="btn btn-tool" title="Previous"><i class="fas fa-chevron-left"></i></a>
<a href="#" class="btn btn-tool" title="Next"><i class="fas fa-chevron-right"></i></a>
</div>
</div>

<div class="card-body p-0">
<div class="mailbox-read-info">
<h5><?php echo $obj['subject']; ?></h5>
<h6>From: <?php echo $obj['reply_cc_emails'][0]; ?>
<span class="mailbox-read-time float-right"><?php echo date("d M. Y H:i A", strtotime($obj['created_at'])); ?></span></h6>
</div>

<div class="mailbox-controls with-border text-center">
<!-- <div class="btn-group">
<button type="button" class="btn btn-default btn-sm" data-container="body" title="Delete">
<i class="far fa-trash-alt"></i>
</button>
<button type="button" class="btn btn-default btn-sm" data-container="body" title="Reply">
<i class="fas fa-reply"></i>
</button>
<button type="button" class="btn btn-default btn-sm" data-container="body" title="Forward">
<i class="fas fa-share"></i>
</button>
</div>

<button type="button" class="btn btn-default btn-sm" title="Print">
<i class="fas fa-print"></i>
</button> -->
</div>

<div class="mailbox-read-message">
<?php echo $obj['description']; ?>
</div>

</div>

<!-- <div class="card-footer bg-white">
<ul class="mailbox-attachments d-flex align-items-stretch clearfix">
<li>
<span class="mailbox-attachment-icon"><i class="far fa-file-pdf"></i></span>
<div class="mailbox-attachment-info">
<a href="#" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i> Sep2014-report.pdf</a>
<span class="mailbox-attachment-size clearfix mt-1">
<span>1,245 KB</span>
<a href="#" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
</span>
</div>
</li>
<li>
<span class="mailbox-attachment-icon"><i class="far fa-file-word"></i></span>
<div class="mailbox-attachment-info">
<a href="#" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i> App Description.docx</a>
<span class="mailbox-attachment-size clearfix mt-1">
<span>1,245 KB</span>
<a href="#" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
</span>
</div>
</li>
<li>
<span class="mailbox-attachment-icon has-img"><img src="../../dist/img/photo1.png" alt="Attachment"></span>
<div class="mailbox-attachment-info">
<a href="#" class="mailbox-attachment-name"><i class="fas fa-camera"></i> photo1.png</a>
<span class="mailbox-attachment-size clearfix mt-1">
<span>2.67 MB</span>
<a href="#" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
</span>
</div>
</li>
<li>
<span class="mailbox-attachment-icon has-img"><img src="../../dist/img/photo2.png" alt="Attachment"></span>
<div class="mailbox-attachment-info">
<a href="#" class="mailbox-attachment-name"><i class="fas fa-camera"></i> photo2.png</a>
<span class="mailbox-attachment-size clearfix mt-1">
<span>1.9 MB</span>
<a href="#" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
</span>
</div>
</li>
</ul>
</div> -->

<div class="card-footer">
<!-- <div class="float-right">
<button type="button" class="btn btn-default"><i class="fas fa-reply"></i> Reply</button>
<button type="button" class="btn btn-default"><i class="fas fa-share"></i> Forward</button>
</div>
<button type="button" class="btn btn-default"><i class="far fa-trash-alt"></i> Delete</button>
<button type="button" class="btn btn-default"><i class="fas fa-print"></i> Print</button> -->
</div>

</div>

</div>

</div>

</div>
</section>

</section>
<!-- /.content -->