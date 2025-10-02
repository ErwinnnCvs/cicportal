<?php 
date_default_timezone_set("Asia/Manila");

if(isset($_REQUEST))
{
    require_once("../../config.php");
    
    $timestamp = date("Y-m-d H:i:s");

    $date1 = date('Y-01-01');
    $date2 = date('Y-m-d');

    $ts1 = strtotime($date1);
    $ts2 = strtotime($date2);

    $year1 = date('Y', $ts1);
    $year2 = date('Y', $ts2);

    $month1 = date('m', $ts1);
    $month2 = date('m', $ts2);

    $diff = (($year2 - $year1) * 12) + ($month2 - $month1);

    if ($_POST['query'] == 'admin') {
        $query = '';
        $queryfreshdesk = '';
    } else {
        $query = $_POST['query'];
        $queryfreshdesk = " and fld_type = ".$type;
    }

    $get_all_seps_sub=$dbh4->query("SELECT a.fld_ctrlno, a.fld_type, AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) as fld_provcode, AES_DECRYPT(a.fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_name FROM tbentities a LEFT JOIN tbassign b ON AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) = b.fld_provcode WHERE b.fld_active = 1".$query." GROUP BY AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) LIMIT 0, 10");
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

          // if (trim($last_responded)) {
          //   $system_remarks = 10;
          // } else {
          //   $system_remarks = 0;
          // }

          $get_latest_remarks = $dbh4->query("SELECT * FROM tbcmsremarks WHERE fld_provcode = '".$gass['fld_provcode']."' ORDER BY fld_ts DESC LIMIT 1");
          $glr=$get_latest_remarks->fetch_array();

          $cicremarks = $glr['fld_remarks'];

        $provcode = $gass['fld_provcode'];
        $sename = '<a href="main.php?nid=137&sid=1&rid=1&provcode='.$provcode.'">'.$gass['fld_name'].'</a>';

        $casemanagement_arr[] = array('prov' => $provcode,
                            'se' => $sename,
                            'lastlogin' => $lastlogin,
                            'tickets' => $ticketcnt,
                            'missedmonths' => round($missed_months_percentage)."%",
                            'lastsubmitted' => ($glfrt['fld_date_covered'] ? date("Y-m", strtotime($glfrt['fld_date_covered'])) : ''),
                            'lastloaded' => ($loaded_date ? date("Y-m-d", strtotime($loaded_date)) : ''),
                            'cicremarks' => $cicremarks);

    }

    echo json_encode($casemanagement_arr);
}
?>