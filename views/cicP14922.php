<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
//echo getcwd();

//print_r($_SESSION);

$ticket_id = $_GET['ctrlno'];

$statusSel[$_POST['statusType']] = " selected";

$statusOptions = array("1" => "Open", "2" => "In Progress", "3" => "Pending", "4" => "Resolved", "5" => "Closed",);

$currDate = Date('Y-m-d H:i:s');

$getSubject = $dbh4->query("SELECT * FROM tbict_freshdesk WHERE fld_ticket_id = '".$ticket_id."'");
$gtsb = $getSubject->fetch_array();

// $getAttachments = $dbh4->query("SELECT * FROM tbict_attachments WHERE fld_ticket_id = '".$ticket_id."'");
// $gtat = $getAttachments->fetch_array();

$convertDate = strtotime($gtsb['fld_due_date']);
$dueDate = date('l, F d, Y', $convertDate);

$convertDate2 = strtotime($gtsb['fld_created_at']);
$createDate = date('F d, Y', $convertDate2);

if (($gtsb['fld_status'] == 2 || $gtsb['fld_status'] == 3 || $gtsb['fld_status'] == 9) && ($gtsb['fld_due_date'] < $currDate)) {
  $stat = "Overdue";
}
elseif ($gtsb['fld_status'] == 2) {
  $stat = "Open";
}
elseif ($gtsb['fld_status'] == 3) {
  $stat = "Pending";
}
elseif ($gtsb['fld_status'] == 9) {
  $stat = "In Progress";
}

if ($_POST['sendBtn']) {
  if (!empty($_POST['compose-textarea'])) {
    if ($_FILES['uploadFile']['size'] != 0) {

      $fileName = $_FILES['uploadFile']['name'];
      $fileTmpName = $_FILES['uploadFile']['tmp_name'];
      $fileType = $_FILES['uploadFile']['type'];

      include 'freshdesk/icmt/reply_ticket_with_attachment.php';
    } 
    elseif ($_FILES['uploadFile']['size'] == 0) {
      include 'freshdesk/icmt/reply_ticket.php';
      //echo "working";
    }
  }
  else {
    $msg = "Error Reply!";
    $msgclr = "danger";
  }
}

if ($_POST['updatebtn']) {
  if (empty($_POST['statusType'])) {
    $msg = "Select a Status!";
    $msgclr = "danger";
  }
  elseif (!empty($_POST['statusType'])) {
    if ($_POST['statusType'] == 1) {
      $statusCode = 2;
    }
    elseif ($_POST['statusType'] == 2) {
      $statusCode = 9;
    }
    elseif ($_POST['statusType'] == 3) {
      $statusCode = 3;
    }
    elseif ($_POST['statusType'] == 4) {
      $statusCode = 4;
    }
    elseif ($_POST['statusType'] == 5) {
      $statusCode = 5;
    }
    
    // this is working 
    //$updateTicket = $dbh4->query("UPDATE tbict_freshdesk SET fld_status = '".$statusCode."', fld_updated_at = '".$currDate."', fld_last_updated_by = '".$_SESSION['user_id']."' WHERE fld_ticket_id = '".$ticket_id."'");

    include 'freshdesk/icmt/update_ticket.php';
    $msg = "Status Updated!";
    $msgclr = "success";
  }
  else {
    $msg = "Error Updating Status!";
    $msgclr = "danger";
  }

}

//echo "UPDATE tbict_freshdesk SET fld_status = '".$statusCode."', fld_updated_at = '".$currDate."', fld_last_updated_by = '".$_SESSION['user_id']."' WHERE fld_ticket_id = '".$ticket_id."'";


$getConversations = $dbh4->query("SELECT * FROM tbict_conversations WHERE fld_ticket_id = '".$ticket_id."' ORDER BY fld_created_at ASC");
$count = mysqli_num_rows($getConversations);

//echo "SELECT * FROM tbict_conversations WHERE fld_ticket_id = '".$ticket_id."' ORDER BY fld_created_at ASC";
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
<?php
        if ($msg) {
      ?>

      <div class="alert alert-<?php echo $msgclr; ?> alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <?php echo $msg; ?>
      </div>

      <?php 
        }
      ?>
	<div class="row">
		<div class="col-md-10">
      <?php 
      
      if ($count == 0) {
      ?>
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
      
      <?php
      }
      else {
        while ($gtcv = $getConversations->fetch_array()) {

          if ($gtcv['fld_incoming'] == 0) {
            $color = "cef6c5";
          }
          elseif ($gtcv['fld_incoming'] == 1) {
            $color = "ffffff";
          }

          //$getAttachments = $dbh4->query("SELECT * FROM tbict_attachments WHERE fld_ticket_id = '".$ticket_id."' AND fld_convo_id = '".$gtcv['fld_freshdesk_id']."'");
          ?>
          <div class="card card-primary card-outline">
            <!-- <div class="card-header">
              <h3 class="card-title">Read Mail</h3>
            </div> -->
            <!-- /.card-header -->
            <div class="card-body p-0">
            <div style="background-color:#<?php echo $color; ?>">
              <div class="mailbox-read-info">
                <h5><?php echo $gtsb['fld_subject']; ?></h5>
                <h6>From: <?php echo $gtcv['fld_from_email']; ?>
                  <span class="mailbox-read-time float-right"><?php echo $gtcv['fld_created_at']; ?></span></h6>
                <h6>To: <?php echo $gtcv['fld_to_emails']; ?></h6>
                <h6>Cc: <?php echo $gtcv['fld_cc_emails']; ?></h6>
              </div>
              <!-- /.mailbox-read-info -->
              <div class="mailbox-read-message">
                <?php 
                    if (empty($gtcv['fld_body'])) {
                      echo $gtsb['fld_description'];
                    }
                    else {
                      echo $gtcv['fld_body'];
                    }
                ?>
              </div>
              <!-- /.mailbox-read-message -->
            </div>
            <!-- /.card-body -->
             <?php 
              $getAttachments = $dbh4->query("SELECT * FROM tbict_attachments WHERE fld_ticket_id = '".$ticket_id."' AND fld_convo_id = '".$gtcv['fld_freshdesk_id']."'");
                if (!$getAttachments) {
                  echo "";
                }
                else {
                  //while ($gtat = $getAttachments->fetch_array()) {
             ?>
            <div class="card-footer" style="background-color:#<?php echo $color; ?>">
              <ul class="mailbox-attachments d-flex align-items-stretch clearfix">
                <?php while ($gtat = $getAttachments->fetch_array()) { ?>
                    <li>
                      <span class="mailbox-attachment-icon bg-white"><i class="far fa-file-word"></i></span>
                      <div class="mailbox-attachment-info">
                        <a href="<?php echo $gtat['fld_attachment_url']; ?>" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i> <?php echo $gtat['fld_name']; ?></a>
                          <span class="mailbox-attachment-size clearfix mt-1">
                            <span>1,245 KB</span>
                            <a href="<?php echo $gtat['fld_attachment_url']; ?>" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                          </span>
                      </div>
                    </li>
                  <?php } ?>  
              </ul>
            </div>
            <?php 
                }
            ?>
            </div>
          </div>
          <!-- /.card -->
          <?php }} ?>
        </div>
<!-- new line -->

		<div class="col-md-2">
      <div class="card">
        <!-- <div class="card-header">
          <h3 class="card-title">FILTERS</h3>
        </div> -->
        	<div class="card-body">
          	<form method="post">
            	<label><?php echo $stat; ?></label>
							  <p>since <?php echo $createDate; ?></p>
						  <label>Resolution Due</label>
							  <p>by <?php echo $dueDate; ?></p>
            		<!-- <br>
            		<label>Name</label>
            			<input type="text" name="filter-company-name" class="form-control" placeholder="Any" value="<?php //echo $_POST['filter-company-name']; ?>">
            			<br> -->
                  <!-- <div class="form-group">
                    <label>Tags:</label>
                      <select class="select2" multiple="multiple" data-placeholder="Any" style="width: 100%;">
                        <option>Opt 1</option>
                        <option>Opt 2</option>
                        <option>Opt 3</option>
                      </select>
                  </div> -->
            			<div class="form-group">
              			<label>Status</label>
              			<select class="form-control" name="statusType">
                      <option selected="" disabled="">--SELECT OPTION---</option>
                				<?php
                  				foreach ($statusOptions as $key => $value) {
                    				echo "<option value='".$key."'".$statusSel[$key].">".$value."</option>";
                  				}
                				?>
              			</select>  
            			</div>
        			    <!-- <div class="form-group">
                		<div class="input-group">
                  		<button type="button" class="btn btn-default float-right" id="daterange-btn">
                    		<i class="far fa-calendar-alt"></i> Date range picker
                    		<i class="fas fa-caret-down"></i>
                  		</button>
                		</div>
                		<div id="reportrange">
                  		<input type="text" name="filter-date" id="filter-date" class="form-control" value="<?php //echo $_POST['filter-date']; ?>">
                		</div>
            			</div> -->
            			<button type="submit" class="btn btn-primary btn-block" value="1" name="updatebtn">Update</button>
          	</form>
        	</div>
      </div>
    </div>

<!-- new line -->
  <div class="col-md-10">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title">Compose New Message</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <form method="post" enctype="multipart/form-data">
        <!-- <div class="form-group">
          <input class="form-control" placeholder="To:">
        </div>
        <div class="form-group">
          <input class="form-control" placeholder="Subject:">
        </div> -->
          <textarea id="compose-textarea" name="compose-textarea" class="form-control" style="height: 300px; display: none;"> 
            <!-- insert text here -->
          </textarea>
            <!-- <div class="btn btn-default btn-file">
              <i class="fas fa-paperclip"></i> Attachment
              <input type="file" name="attachment">
            </div> -->
            <div class="col-md-4">
              <label for="exampleInputFile">File input</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" name="uploadFile" id="uploadFile">
                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                  </div>
                  <!-- <div class="input-group-append">
                    <span class="input-group-text">Upload</span>
                  </div> -->
                </div>
            </div>
            <!-- <br> -->
            <!-- <div class="col-md-4"> -->
              <!-- <label for="exampleInputFile">File input</label> -->
                <!-- <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="uploadFile2">
                    <label class="custom-file-label" for="uploadFile2">Choose file</label> -->
                  <!-- </div> -->
                  <!-- <div class="input-group-append">
                    <span class="input-group-text">Upload</span>
                  </div> -->
                <!-- </div> -->
            <!-- </div> -->
            <br>
            <!-- <div class="col-md-4"> -->
              <!-- <label for="exampleInputFile">File input</label> -->
                <!-- <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="uploadFile3">
                    <label class="custom-file-label" for="uploadFile3">Choose file</label>
                  </div> -->
                  <!-- <div class="input-group-append">
                    <span class="input-group-text">Upload</span>
                  </div> -->
                <!-- </div> -->
                <p class="help-block">Max. 20MB</p>
            <!-- </div> -->
            <div class="float-right">
              <!-- <button type="button" class="btn btn-default"><i class="fas fa-pencil-alt"></i> Draft</button> -->
              <button type="submit" value="1" name="sendBtn" class="btn btn-primary"><i class="far fa-envelope"></i> Send</button>
            </div>
        </form>
      </div>
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