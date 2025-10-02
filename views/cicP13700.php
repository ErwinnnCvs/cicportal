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
  $query = ' WHERE fld_assign = '.$_SESSION['user_id'];
  $queryfreshdesk = " and fld_type = ".$type;
}


  
?>

<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    
    <div class="card-body">
      <div>
      </div>
      <table id="casemanagement" class="table table-bordered table-striped dataTable dtr-inline" aria-describedby="example1_info">
      <thead>
          <tr>
            <th tabindex="0" rowspan="1" colspan="1">Provider Code</th>
            <th tabindex="0" rowspan="1" colspan="1">Submitting Entity</th>
            <th tabindex="0" rowspan="1" colspan="1" width="15%"><center>Last Login (CE Portal)</center></th>
            <th tabindex="0" rowspan="1" colspan="1"><center>Tickets</center></th>
            <th tabindex="0" rowspan="1" colspan="1"><center>Missed Months</center></th>
            <th tabindex="0" rowspan="1" colspan="1"><center>Last Submitted</center></th>
            <th tabindex="0" rowspan="1" colspan="1"><center>Last Loaded</center></th>
            <th tabindex="0" rowspan="1" colspan="1" width="10%"><center>Action</center></th>
            <th tabindex="0" rowspan="1" colspan="1"><center>CIC Remarks</center></th>
          </tr>
      </thead>
      <tbody>
      <?php
        $get_casemanagement_rows = $dbh->query("SELECT * FROM tbcasemanagement2 ".$query." ORDER BY fld_tickets DESC, fld_missed_months DESC, fld_last_submitted DESC, fld_last_loaded DESC;");
        while ($gcr=$get_casemanagement_rows->fetch_array()) {
      ?>
      <tr>  
        <td><?php echo $gcr['fld_provcode']; ?></td>
        <td>
          
          <a href="main.php?nid=137&sid=1&rid=1&provcode=<?php echo $gcr['fld_provcode']; ?>">
          <?php echo $gcr['fld_entity']; ?>
          </a>    
        </td>
        <td><?php echo $gcr['fld_lastlogin']; ?></td>
        <td><?php echo $gcr['fld_tickets']; ?></td>
        <td><?php echo $gcr['fld_missed_months']; ?></td>
        <td><?php echo $gcr['fld_last_submitted']; ?></td>
        <td><?php echo $gcr['fld_last_loaded']; ?></td>
        <td>
          <center>
            <?php
              if ($gcr['fld_tickets'] > 0) {
            ?>
              <button class="btn btn-warning" disabled>PENDING TICKETS</button>
            <?php
              } elseif ($gcr['fld_missed_months'] > 0) {
            ?>
              <button class="btn btn-primary" disabled>SEND LOC</button>
            <?php
              }
            ?>
          </center>
        </td>
        <td>
          <!-- <?php echo $gcr['fld_cic_remarks']; ?>      -->
        </td>
      </tr>

      <?php
        }
      ?>
      
      </tbody>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->