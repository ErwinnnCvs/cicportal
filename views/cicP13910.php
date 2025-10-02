<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

// echo "REFRESH TICKET";

if ($_POST['btnUpdate']) {
  $ticket = $_GET['ticket'];

  $status = (int)$_POST['sel_status'];

  $priority = (int)$_POST['sel_priority'];

  // echo $priority. " " .$status;
  include("tickets/update_ticket.php");
}

$ticket = $_GET['ticket'];
$api_key = "xVfqn6csPjVKD0QQkR";
$password = "password123123";
$yourdomain = "creditinfoph";

// Return the tickets that are new or opend & assigned to you
// If you want to fetch all tickets remove the filter query param
$url = "https://".$yourdomain.".freshdesk.com/api/v2/tickets/".$ticket;

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

  $ticket_id = $obj['id'];
  $subject = $obj['subject'];
  $description = $obj['description'];
  $description_text = $obj['description_text'];
  $priority = $obj['priority'];
  $status = $obj['status'];
  $created_at = $obj['created_at'];
  $updated_at = $obj['updated_at'];
  $requester_id = $obj['requester_id'];
  $due_by = $obj['due_by'];
  $tags = '';
  foreach ($obj['tags'] as $tag) {
    $tags = $tag."|";
  }

  // echo "UPDATE tbfreshdesk SET fld_status = ".$status.", fld_priority = ".$priority.", fld_updated_at = '".$updated_at."', fld_tags = '".$tags."' WHERE fld_ticket_id = ".$ticket_id;
  $dbh->query("UPDATE tbfreshdesk SET fld_status = ".$status.", fld_priority = ".$priority.", fld_updated_at = '".$updated_at."', fld_tags = '".$tags."' WHERE fld_ticket_id = ".$ticket_id);

  $url = "https://$yourdomain.freshdesk.com/api/v2/tickets/".$ticket_id."/conversations";

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
    $objconvo = json_decode($response, true);


    foreach ($objconvo as $keyconvo) {
      $convo_id = $keyconvo['id'];
      $created_at_convo = $keyconvo['created_at'];
      $updated_at_convo = $keyconvo['updated_at'];
      $from_email_convo = $keyconvo['from_email'];

      foreach ($keyconvo['cc_emails'] as $cc_email) {
        $cc_emails_convo = $cc_email."|";
      }

      $incoming_convo = $keyconvo['incoming'];
      
      foreach ($keyconvo['to_emails'] as $to_email) {
        $to_email_convo = $to_email."|";
      }

      $body = $keyconvo['body'];
      $body_text = $keyconvo['body_text'];

      $check_existing_convo = $dbh->query("SELECT fld_convo_id FROM tbfreshdesk_conversations WHERE fld_convo_id = ".$convo_id);
      $cec=$check_existing_convo->fetch_array();
      if ($cec['fld_convo_id']) {
        
      } else {
        $dbh->query("INSERT INTO tbfreshdesk_conversations (fld_ticket_id, fld_description, fld_description_text, fld_created_at, fld_updated_at, fld_provcode, fld_from_email, fld_to_email, fld_cc_emails, fld_incoming, fld_convo_id) VALUES (".$ticket_id.", '".addslashes($body)."', '".addslashes($body_text)."', '".$created_at_convo."', '".$updated_at_convo."', '".$_POST['getProvCode']."', '".$from_email_convo."', '".$to_email_convo."', '".$cc_emails_convo."', '".$incoming_convo."', '".$convo_id."')");

        foreach($keyconvo['attachments'] as $cattachments){
          $dbh->query("INSERT INTO tbfreshdesk_attachments (fld_ticket_id, fld_attachment_name, fld_attachment_url, fld_convo_id, fld_attachment_size) VALUES (".$ticket_id.", '".$cattachments['name']."', '".$cattachments['attachment_url']."', '".$convo_id."', '".$cattachments['size']."')");
        }
        
      }
    }
  }
}




$get_ticket_details = $dbh->query("SELECT * FROM tbfreshdesk WHERE fld_ticket_id = ".$_GET['ticket']);
$gtd=$get_ticket_details->fetch_array();

$requester_id = $gtd['fld_requester_id']; 
include("tickets/contact.php");

if (!$_POST['sel_status']) {
  $_POST['sel_status'] = $gtd['fld_status'];
}

$selstatus[$_POST['sel_status']] = ' selected';


if (!$_POST['sel_priority']) {
  $_POST['sel_priority'] = $gtd['fld_priority'];
}

$selpriority[$_POST['sel_priority']] = ' selected';


if ($_POST['replyTicket']) {
  $ticket = $_GET['ticket'];
  $body = $_POST['compose-textarea'];

  $get_signature_forreply = $dbh->query("SELECT fld_agent_id FROM tbfreshdesk_agents WHERE fld_userid = ".$_SESSION['user_id']);
  $gsfr=$get_signature_forreply->fetch_array();
  
  $user_id = $gsfr['fld_agent_id'];
  $from_email = $requester_email;

  include("tickets/reply.php");


}

?>

<!-- Main content -->
<section class="content">


<div class="container-fluid">
  <?php
    if ($msg) {
  ?>
  <div class="alert alert-<?php echo $msgclr; ?> alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h5><i class="icon fas fa-check"></i> Alert!</h5>
    <?php echo $msg; ?>
  </div>
  <?php
    }
  ?>
  <div class="row">
      <div class="col-md-10" style="overflow-y: scroll; height: 870px;"> 
        <!-- Default box -->
        <div class="card">
          <form method="POST">
          <input type="hidden" name="getProvCode" id="getProvCode" value="<?php echo $gtd['fld_provcode']; ?>">
          <div class="card-header">
            <h3 class="card-title"><?php echo "<b>".$_GET['ticket']."</b> - ".$gtd['fld_subject']; ?></h3>
            <div class="card-tools">
            
              
            
            <button type="submit" value="1" name="refreshticket" class="btn btn-tool" title="Contacts">
            <i class="fas fa-undo-alt"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
            </button>
            </div>
          </div>
          </form>
          <div class="card-body p-0">
          <div class="mailbox-read-info">
          <h6>From: 
          <?php
            echo $requester_name." (".$requester_email.")";
          ?>
          <span class="mailbox-read-time float-right"><?php echo date("d M. Y h:i A", strtotime($gtd['fld_created_at'])); ?></span></h6>
          </div>

          <div class="mailbox-read-message">
              <?php
                echo htmlspecialchars_decode($gtd['fld_description']);
              ?>
          </div>

          <?php
          $get_main_attachments = $dbh->query("SELECT * FROM tbfreshdesk_attachments WHERE fld_ticket_id = ".$_GET['ticket']." AND fld_convo_id IS NULL");

          if (mysqli_num_rows($get_main_attachments) > 0) {
          ?>
          <div class="card-footer bg-white">
            <ul class="mailbox-attachments d-flex align-items-stretch clearfix">
          <?php
              
              while ($gma=$get_main_attachments->fetch_array()) {
          ?>
              <li>
              <span class="mailbox-attachment-icon"><i class="far fa-file-pdf"></i></span>
              <div class="mailbox-attachment-info">
              <a href="<?php echo $gma['fld_attachment_url']; ?>" target="_blank" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i> <?php echo $gma['fld_attachment_name']; ?></a>
              <span class="mailbox-attachment-size clearfix mt-1">
              <span><?php echo round(($gma['fld_attachment_size'] / 100)); ?> KB</span>
              <a href="<?php echo $gma['fld_attachment_url']; ?>" target="_blank" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
              </span>
              </div>
              </li>
             
          <?php
            }
          ?>
            </ul>
          </div>
          <?php
            }
          ?>

          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <?php

          $get_all_conversations = $dbh->query("SELECT * FROM tbfreshdesk_conversations WHERE fld_ticket_id = ".$_GET['ticket']);
          $num_rows = mysqli_num_rows($get_all_conversations);

          if ($num_rows > 0) {

            while ($gac=$get_all_conversations->fetch_array()) {
              if ($gac['fld_incoming'] == 0) {
                $clr = '#e8edea';
              } else {
                $clr = '#ffffff';
              }


        ?>
        <hr>  
        <!-- Default box -->
        <div class="card" style="background-color: <?php echo $clr; ?>">
          <div class="card-header">
            <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
            </button>
            </div>
          </div>
          <div class="card-body p-0">
          <div class="mailbox-read-info">
          <h6>From: <?php echo $gac['fld_from_email']; ?>
          <br>  
          To: <?php echo $gac['fld_to_email']; ?>
          <br>  
          Cc: <?php echo $gac['fld_cc_email']; ?>
          <span class="mailbox-read-time float-right"><?php echo date("d M. Y h:i A", strtotime($gac['fld_created_at'])); ?></span></h6>
          </div>


          

          <div class="mailbox-read-message">
              <?php
                $replace_blockquote = str_replace('<div class="freshdesk_quote"><blockquote class="freshdesk_quote">', '<div id="accordion"><div class="card card-primary"><div class="card-header"><h6 class="card-title w-100"><a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseOne'.$gac["fld_id"].'" aria-expanded="false">Show quoted text</a></h6></div><div id="collapseOne'.$gac['fld_id'].'" class="collapse" data-parent="#accordion" style=""><div class="card-body"><div class="freshdesk_quote"><blockquote class="freshdesk_quote">', htmlspecialchars_decode($gac['fld_description']));

                $replace_endblockquote = str_replace('</blockquote></div>', '</blockquote></div></div></div></div></div>', $replace_blockquote);


                echo htmlspecialchars_decode($gac['fld_description']);
                // echo htmlspecialchars_decode($replace_endblockquote);
              ?>
          </div>

          <?php
          if (mysqli_num_rows($get_main_attachments) > 0) {
          ?>
          <div class="card-footer bg-grey">
            <ul class="mailbox-attachments d-flex align-items-stretch clearfix">
              <?php
                $get_sub_attachments = $dbh->query("SELECT * FROM tbfreshdesk_attachments WHERE fld_ticket_id = ".$_GET['ticket']." AND fld_convo_id = ".$gac['fld_convo_id']);
                while ($gsa=$get_sub_attachments->fetch_array()) {
              ?>
              <li>
              <span class="mailbox-attachment-icon"><i class="far fa-file-pdf"></i></span>
              <div class="mailbox-attachment-info">
              <a href="<?php echo $gsa['fld_attachment_url']; ?>" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i> <?php echo $gsa['fld_attachment_name']; ?></a>
              <span class="mailbox-attachment-size clearfix mt-1">
              <span><?php echo round(($gsa['fld_attachment_size'] / 100)); ?> KB</span>
              <a href="<?php echo $gsa['fld_attachment_url']; ?>" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
              </span>
              </div>
              </li>
              <?php
                }
              ?>
             
            </ul>
          </div>
          <?php
            }
          ?>

          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
         
        <?php
            }
          }
        ?>

        <div class="card" id="reply-card">
          <div class="card-body p-0">

          <div class="mailbox-controls with-border text-center">
          <div class="btn-group">
          <button type="button" class="btn btn-default btn-sm" id="replyBtn" data-container="body" title="Reply">
          <i class="fas fa-reply"></i> Reply
          </button>
          <button type="button" class="btn btn-default btn-sm" data-container="body" title="Forward">
          <i class="fas fa-share"></i> Forward
          </button>
          </div>

          <!-- <button type="button" class="btn btn-default btn-sm" title="Print">
          <i class="fas fa-print"></i>
          </button> -->
          </div>

          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <form method="POST">
        
          <div class="card" id="compose-card" hidden>
            <div class="card-body p-0">
              <div class="form-group">
                <textarea id="compose-textarea" name="compose-textarea" class="form-control" style="height: 300px">
                  <?php
                    $get_signature = $dbh->query("SELECT fld_signature FROM tbfreshdesk_agents WHERE fld_userid = ".$_SESSION['user_id']);
                    $gs=$get_signature->fetch_array();

                    echo "Hi ".$requester_name.",<br><br><br>";

                    echo "Kindly keep the ticket number below for your reference: <br>";
                    echo "<b>[".$_GET['ticket']."]</b>";
                    echo htmlspecialchars_decode($gs['fld_signature']);
                  ?>
                  
                </textarea>
            </div>
            </div>  
          </div>
          <div class="card-footer" id="compose-send-card" hidden>
            <div>
              <!-- <button type="button" class="btn btn-default"><i class="fas fa-pencil-alt"></i> Draft</button> -->
              <button type="submit" name="replyTicket" class="btn btn-primary btn-block" value="<?php echo $_GET['ticket']; ?>"><i class="far fa-envelope"></i> Send</button>
            </div>
            <!-- <button type="reset" class="btn btn-default"><i class="fas fa-times"></i> Discard</button> -->
          </div>
        </form>

      </div>

      <div class="col-md-2">
        <?php

          $earlier = new DateTime($gtd['fld_created_at']);

          if ($cmstatus[$gtd['fld_status']] == "Closed" or $cmstatus[$gtd['fld_status']] == "Resolved") {
            $now = new DateTime($gtd['fld_updated_at']);
            $abs_diff = $now->diff($earlier)->format("%a");
            
            if ($gtd['fld_updated_at'] > $gtd['fld_due_by']) {
                $remarkscr = " LATE";
            } else {
                $remarkscr = "";
            } 
          } else {
            $now = new DateTime(date("Y-m-d"));
            $abs_diff = $now->diff($earlier)->format("%a");
            $dayslapsed = $abs_diff + 1;
          }
        ?>
        <div class="card">
          <!-- <div class="card-header">
          <h3 class="card-title"><?php echo $cmstatus[$gtd['fld_status']]. " ".$remarkscr; ?></h3>
          </div> -->
          <div class="card-body">
            <form method="POST">
            <h5>
              <span class=""><?php echo $cmstatus[$gtd['fld_status']]. " ".$remarkscr; ?></span>
            </h5>
            <h6>
            <?php
              $earlier = new DateTime($gtd['fld_created_at']);

                if ($cmstatus[$gtd['fld_status']] == "Closed" or $cmstatus[$gtd['fld_status']] == "Resolved") {
                  echo "by ".date("M d, Y h:i A", strtotime($gtd['fld_updated_at']));
                } else {
                  echo "since ".$dayslapsed." day(s) ago";
                  echo "<br><br>";
                  echo "<b>RESOLUTION DUE</b>:<br>";
                  echo "by ".date("D, M d, Y h:i A", strtotime($gtd['fld_due_by']));
                }
              ?>
            </h6>
            <hr>
            <div class="form-group">
                  <label>Tags</label>
                  <select class="select2bs4" multiple="multiple" data-placeholder=""
                          style="width: 100%;">
                    <?php 

                    $split_tags = explode("|", $gtd['fld_tags']);

                    foreach ($split_tags as $tag) {

                      if ($tag) {
                    ?>
                    <option selected><?php echo $tag; ?></option>
                    <?php
                      }
                    }
                    ?>
                  </select>
                </div>
            <div class="form-group">
              <label>Status</label>
              <div class="select2-purple">
                <select class="select2" name="sel_status" id="sel_status" data-placeholder="Select a State" data-dropdown-css-class="select2-purple" style="width: 100%;">
                  <?php
                    foreach ($cmstatus as $skey => $svalue) {
                  ?>
                  <option value="<?php echo $skey; ?>"<?php echo $selstatus[$skey]; ?>><?php echo $svalue; ?></option>
                  <?php
                    }
                  ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label>Priority</label>
              <div class="select2-purple">
                <select class="select2" name="sel_priority" id="sel_priority" data-placeholder="Select a State" data-dropdown-css-class="select2-purple" style="width: 100%;">
                  <?php
                    foreach ($cmpriority as $pkey => $pvalue) {
                  ?>
                  <option value="<?php echo $pkey; ?>"<?php echo $selpriority[$pkey]; ?>><?php echo $pvalue; ?></option>
                  <?php
                    }
                  ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label>Group</label>
              <div class="select2-purple">
                <select class="select2" name="sel_group" id="sel_group" data-placeholder="Select Group" data-dropdown-css-class="select2-purple" style="width: 100%;">
                  
                </select>
              </div>
            </div>

            <div> 
                <button type="submit" class="btn btn-primary btn-block" name="btnUpdate" id="btnUpdate" value="1" disabled>Update</button>
            </div>

            </form>

          </div>

        </div>

      </div>

      <div class="col-md-2">
      </div>
  </div>

</div>

</section>
<!-- /.content -->