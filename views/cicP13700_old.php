<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

if (in_array($_SESSION['user_id'], $tech_team)) {
  $type = 1;
} elseif (in_array($_SESSION['user_id'], $legal_team)) {
  $type = 2;
}


function searchArrayByKeyword($array, $keyword) { 
  $results = array(); 
  foreach ($array as $item) { 
    foreach ($item as$key => $value) { 
      if (stripos($value, $keyword) !== false) { // Case-insensitive search 
        $results[] = $item; 
        break; // Break inner loop once a match is found 
      } 
    } 
  } return $results; 
}



$selectedtype[$_POST['sel_type']] = " selected";

$submission_type = array("REGULAR SUBMISSION", "CORRECTION FILE", "DISPUTE", "HISORICAL DATA", "EXTENDED REGULAR SUBMISSION", "LATE SUBMISSION");

$date1 = date('Y-01-01');
$date2 = date('Y-m-d');

$ts1 = strtotime($date1);
$ts2 = strtotime($date2);

$year1 = date('Y', $ts1);
$year2 = date('Y', $ts2);

$month1 = date('m', $ts1);
$month2 = date('m', $ts2);

$diff = (($year2 - $year1) * 12) + ($month2 - $month1);

if ($_POST['sendLocEmail']) {
  $provcode = $_POST['sendLocEmail'];
  include("mailer/sendloc.php");
}


if ($_SESSION['user_id'] == 76 || $_SESSION['user_id'] == 197) {
  $query = '';
  $queryfreshdesk = '';
} else {
  $query = ' and b.fld_assign = '.$_SESSION['user_id'];
  $queryfreshdesk = " and fld_type = ".$type;
}

$casemanagement_arr = array();


$get_all_seps_sub=$dbh4->query("SELECT a.fld_ctrlno, a.fld_type, AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) as fld_provcode, AES_DECRYPT(a.fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_name FROM tbentities a LEFT JOIN tbassign b ON AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) = b.fld_provcode WHERE b.fld_active = 1".$query." GROUP BY AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019')))");
while ($gass=$get_all_seps_sub->fetch_array()) {
  $check_loc_logs = $dbh4->query("SELECT fld_created_at FROM tbloclogs WHERE fld_provcode = '".$gass['fld_provcode']."'");
  $cll=$check_loc_logs->fetch_array();
  
  if (empty($cll['fld_created_at'])) {
    $last_sent_logs = 0;
  } else {
    $last_sent_logs = $cll['fld_created_at'];
  }

  $users = array();
  $get_users_in_ceportal = $dbh->query("SELECT pkUserId FROM tbusers WHERE fld_provcode = '".$gass['fld_provcode']."' and is_active = 1 and email <> '' and is_admin <= 3");
  while($guic=$get_users_in_ceportal->fetch_array()){
    array_push($users, $guic['pkUserId']);
  }

  $check_array = count($users);

  if($check_array > 0) {
    $get_last_login = $dbh->query("SELECT * FROM tbloginhistory WHERE fld_userid IN (". implode(',', $users) .") ORDER BY fld_login DESC");
    $gll=$get_last_login->fetch_array();

    if ($gll['fld_login'] and $gll['fld_login'] != '0000-00-00 00:00:00') {
      $lastlogin = date("d M Y h:ia", strtotime($gll['fld_login']));
    } else {
      $lastlogin = "NA";
    }
  } else {
    $lastlogin = "No Users";
  }

  $get_last_filed_regular_transmittal = $dbh4->query("SELECT fld_filename, fld_date_covered FROM tbtransmittal WHERE fld_provcode = '".$gass['fld_provcode']."' AND fld_trans_type = 1 ORDER BY fld_filed_date_ts DESC LIMIT 1");
  $glfrt=$get_last_filed_regular_transmittal->fetch_array();

  $missed_months = 0;

  for ($m=1; $m<=$diff; $m++) {
    $month = date('Y-m', mktime(0,0,0,$m, 1, date('Y')));

    $check_submission_transmittal = $dbh4->query("SELECT fld_id FROM tbtransmittal WHERE fld_provcode = '".$gass['fld_provcode']."' AND fld_trans_type = 1 and fld_date_covered LIKE '".$month."%' GROUP BY DATE_FORMAT(fld_date_covered, '%Y-%m')");
    $cst=$check_submission_transmittal->fetch_array();
    if ($cst['fld_id']) {
    } else {
      $missed_months += 1;
    }
  }


  $get_loaded_subject = $dbh4->query("SELECT * FROM tbsubmissiondata WHERE CODPROVIDERCODE = '".$gass['fld_provcode']."' AND YEAR(DATFILEREFERENCEDATE) = ".date("Y")." ORDER BY DATFILEREFERENCEDATE DESC LIMIT 1");
  $gls=$get_loaded_subject->fetch_array();

  $subject_loaded = $gls['NUMCPSRECORDSINSERTEDNUMBER'] + $gls['NUMCPSRECORDSUPDATEDNUMBER'];

  $contract_loaded = $gls['NUMCPCRECORDSINSERTEDNUMBER'] + $gls['NUMCPCRECORDSUPDATEDNUMBER'];

  $loaded_date = $gls['DATFILEREFERENCEDATE'];

  $division = $missed_months / $diff;
  $missed_months_percentage = $division * 100;

  $count_all_tickets=$dbh->query("SELECT COUNT(*) as cnt FROM tbfreshdesk WHERE fld_provcode = '".$gass['fld_provcode']."' and fld_status <> 4 and fld_status <> 5".$queryfreshdesk);
  $cat=$count_all_tickets->fetch_array();

  $ticketcnt = $cat['cnt'];

  if ($ticketcnt > 0) {
    $get_conversations = $dbh->query("SELECT fld_id, fld_ticket_id, fld_from_email, fld_incoming  FROM tbfreshdesk_conversations WHERE fld_provcode = '".$gass['fld_provcode']."' ORDER BY fld_ins_ts DESC LIMIT 1;");
    $gc=$get_conversations->fetch_array();

    if ($gs['fld_incoming'] == 1) {
    $last_responded = $gc['fld_from_email'];
    }
  }

  if (trim($last_responded)) {
    $system_remarks = 10;
  } else {
    $system_remarks = 0;
  }

  $get_latest_remarks = $dbh4->query("SELECT * FROM tbcmsremarks WHERE fld_provcode = '".$gass['fld_provcode']."' ORDER BY fld_ts DESC LIMIT 1");
  $glr=$get_latest_remarks->fetch_array();

  $cicremarks = $glr['fld_remarks'];


  $casemanagement_arr[] = array("PROVCODE"=>$gass['fld_provcode'], "SENAME"=>$gass['fld_name'], "LASTSENTLOGS"=>$last_sent_logs, "LASTLOGIN"=>$lastlogin, "TICKETS"=>$ticketcnt, "MISSEDMONTHS"=>round($missed_months_percentage)."%", "LASTSUBMITTED"=>($glfrt['fld_date_covered'] ? date("Y-m", strtotime($glfrt['fld_date_covered'])) : ''), "LASTLOADED"=>($loaded_date ? date("Y-m-d", strtotime($loaded_date)) : ''),"SYSTEMREMARKS"=>$system_remarks, "CICREMARKS"=>$cicremarks);


  foreach ($casemanagement_arr as $inskey => $insval) {
    $dbh->query("INSERT INTO tbcasemanagement (fld_provcode, fld_entity, fld_lastlogin, fld_tickets, fld_missed_months, fld_last_submitted, fld_last_loaded, fld_action, fld_cic_remarks) VALUES ('".$insval['PROVCODE']."', '".$insval['SENAME']."', '".$insval['LASTLOGIN']."', '".$insval['TICKETS']."', '".$insval['MISSEDMONTHS']."', '".$insval['LASTSUBMITTED']."', '".$insval['LASTLOADED']."', '".$insval['TICKETS']."', '".$insval['SYSTEMREMARKS']."')");
  }


  if ($_POST['sbtSearch']) {
          

    $results = searchArrayByKeyword($casemanagement_arr, $_POST['txtSearch']);

    $array_to_use = $results;
  } else {
    $array_to_use = $casemanagement_arr;
  }

  $ticketssort = [];

  $systemssort = [];

  foreach ($array_to_use as $scmkey => $scmvalue) {
    $ticketssort[$scmvalue['PROVCODE']] = $scmvalue['TICKETS'];
  }

  foreach ($array_to_use as $scmkey => $scmvalue) {
    $systemssort[$scmvalue['PROVCODE']] = $scmvalue['SYSTEMREMARKS'];
  }

  array_multisort($systemssort, SORT_DESC, $ticketssort, SORT_DESC, $array_to_use);

  $items_per_page = 10;
  $total_items = count($array_to_use);

  $total_pages = ceil($total_items / $items_per_page);
  $current_page = (isset($_GET['page']) ? $_GET['page'] : 1);
  $current_page = max(1, min($current_page, ceil($total_items / $items_per_page)));

  $offset = ($current_page - 1) * $items_per_page;



  $items_for_page = array_slice($array_to_use, $offset, $items_per_page);

}

  
?>

<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <form method="POST">
    <div class="card-body">
      <div class="row">
        <div class="col-lg-4">
          <div class="form-group">
<!--                    <form action="#" method="get" class="sidebar-form">    -->
            <label>Search</label> <small>( Enter either Provider Code or Part of the Company Name )</small>
            <div class="input-group">
              <input type="text" name="txtSearch" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                  <button type="submit" name="sbtSearch" id="search-btn" value="1" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
                <a href="main.php?nid=137&sid=0&rid=0&page=1" name="sbtClearSearch" id="search-btn" value="1" class="btn btn-success btn-flat">Clear Search
                </a>
              </span>
            </div>
<!--                    </form>    -->
          </div>
        </div>
      </div>
      <div>
      </div>
      <table id="casemanagement" class="table table-bordered table-striped dataTable dtr-inline" aria-describedby="example1_info">
      <thead>
          <tr>
            <th tabindex="0" rowspan="1" colspan="1">Provider Code</th>
            <th tabindex="0" rowspan="1" colspan="1">Submitting Entity</th>
            <th tabindex="0" rowspan="1" colspan="1" width="15%"><center>Last Login (CE Portal)</center></th>
            <!-- <th tabindex="0" rowspan="1" colspan="1"><center>Priority</center></th> -->
            <th tabindex="0" rowspan="1" colspan="1"><center>Tickets</center></th>
            <th tabindex="0" rowspan="1" colspan="1"><center>Missed Months</center></th>
            <th tabindex="0" rowspan="1" colspan="1"><center>Last Submitted</center></th>
            <th tabindex="0" rowspan="1" colspan="1"><center>Last Loaded</center></th>
            <th tabindex="0" rowspan="1" colspan="1" width="10%"><center>Action</center></th>
            <th tabindex="0" rowspan="1" colspan="1"><center>System Remarks</center></th>
            <th tabindex="0" rowspan="1" colspan="1"><center>CIC Remarks</center></th>
          </tr>
      </thead>
      <tbody>
      <?php


        foreach ($items_for_page as $cmkey => $cmvalue) {

      ?>

      <tr class="odd">
        <td>
          <?php
            echo $cmvalue['PROVCODE'];
          ?>
        </td>
        <td class="sorting_1 dtr-control" tabindex="0" >
          <a href="main.php?nid=137&sid=1&rid=1&provcode=<?php echo $cmvalue['PROVCODE']; ?>">
          <?php
            echo $cmvalue['SENAME']
          ?>
          </a>
        </td>
        <td class="">
          <?php
            echo $cmvalue['LASTLOGIN'];
          ?>
        </td>
        <!-- <td><center></center></td> -->
        <td>
          <center>  
            <?php
              echo $cmvalue['TICKETS'];
            ?>
          </center>
        </td>

        <td>
          <center>
            <?php
              echo $cmvalue['MISSEDMONTHS'];
            ?>
          </center>
        </td>
        <td>
          <center>
            <?php
              echo $cmvalue['LASTSUBMITTED'];
            ?>
          </center>
        </td>
        <td>
          <center>
            <?php
              echo $cmvalue['LASTLOADED'];
            ?>
          </center>
        </td>
        <td>
            
          <center>
            <?php

              if ($cmvalue['TICKETS'] > 0) {
                if ($cmvalue['TICKETS'] > 1) {
                  $wrsl = 'S';
                } else {
                  $wrsl = '';
                }
                echo "<button class='btn btn-warning btn-sm btn-block' disabled>PENDING TICKET".$wrsl."</button>";
              } else {
                if ($cmvalue['MISSEDMONTHS'] > 0) {
                  if ($cmvalue['LASTSENTLOGS'] != 0) {
                    if (date("Y-m", strtotime($cmvalue['LASTSENTLOGS'])) == date("Y-m")) {
                      $attrib = "hidden";
                    } else {
                      $attrib = "disabled";
                    }
                  } else {
                    $attrib = "disabled";
                  }
              ?>
              <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-default<?php echo $gass['fld_provcode']; ?>" <?php echo $attrib; ?>>
                <i class="fa fa-share"></i> Send LOC
              </button>

              <div class="modal fade" id="modal-default<?php echo $gass['fld_provcode']; ?>">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Confirmation..</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <h5>Are you sure you want to send a Letter of Compliance to <b><?php echo $gass['fld_name']; ?></b> for their Missed Months in Transmittal?</h5>
                    </div>

                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                      <button type="submit" value="<?php echo $gass['fld_provcode']; ?>" name="sendLocEmail" class="btn btn-primary">Yes</button>
                    </div>
                  </div>
                </div>
              </div>
              <?php
                  if ($cmvalue['LASTSENTLOGS'] != 0) {
              ?>
              <?php
                  } else {
              ?>
              <?php
                  }
                } else {
                  $lftdate1 = date('Y-m-01', strtotime($cmvalue['LASTSUBMITTED']));
                  $lftdate2 = date('Y-m-d');

                  $lftts1 = strtotime($lftdate1);
                  $lftts2 = strtotime($lftdate2);

                  $lftyear1 = date('Y', $lftts1);
                  $lftyear2 = date('Y', $lftts2);

                  $lftmonth1 = date('m', $lftts1);
                  $lftmonth2 = date('m', $lftts2);

                  $lftdiff = (($lftyear2 - $lftyear1) * 12) + ($lftmonth2 - $lftmonth1);
                  if ($lftdiff > 1) {
                    echo "<button class='btn btn-warning btn-sm'>FF Transmittal</button>";
                  }
                }
              }
              ?>
            
          </center>
          
        </td>
        <td>
          <center>
            <?php
              if ($cmvalue['SYSTEMREMARKS'] == 10) {
                echo "CUSTOMER RESPONDED";
              } else {
                echo "";
              }
            ?>
          </center>
        </td>
        <td>
          <center>
              <?php
                echo $cmvalue['CICREMARKS'];
              ?>
          </center>
        </td>
      </tr>
      
      <?php
        }


      ?>
      
      </tbody>
      </table>
      <br>
      <div style="width: 1400px; overflow-x: auto; overflow-y: hidden;">
         <ul class="pagination">
            <?php

              for ($i=1; $i < $total_pages; $i++) { 
                $active = ($i == $current_page) ? 'active' : '';
                echo '<li class="page-item ' . $active . '"><a class="page-link" href="main.php?nid=137&sid=0&rid=0&page=' . $i . '">' . $i . '</a></li>';
              }
            ?>
          </ul>
        </div>
    </div>
    <!-- /.card-body -->
    </form>
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->