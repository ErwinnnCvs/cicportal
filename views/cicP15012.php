<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
//echo getcwd();

//print_r($_SESSION);

$ticket_id = $_GET['ctrlno'];

$getSubject = $dbh4->query("SELECT * FROM tbict_freshdesk WHERE fld_ticket_id = '".$ticket_id."'");
$gtsb = $getSubject->fetch_array();

//echo $ticket_id;

?>



<!-- Main content -->
<section class="content">
  <!-- Default box -->
  <!-- <div class="card">
    <div class="card-header">
      <h3 class="card-title">Title</h3>
    </div>
    <div class="card-body"> -->
<!-- new line -->
	<div class="row">
		<div class="col-md-10">
          <div class="card card-primary card-outline">
            <div class="card-body p-0">
              <div class="mailbox-read-info">
                <h5><?php if (empty($gtsb['fld_subject'])) {echo "(this ticket has no subject title)";} else {echo $gtsb['fld_subject'];}  ?></h5>
              </div>
              <!-- /.mailbox-read-info -->
              <div class="mailbox-read-message">
                <?php echo $gtsb['fld_description']; ?>
              </div>
              <!-- /.mailbox-read-message -->
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
<!-- new line -->

    </div>

<!-- new line -->
    <!-- </div> -->
    <!-- /.card-body -->
  <!-- </div> -->
  <!-- /.card -->

</section>
<!-- /.content -->