<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// print_r($_POST);

//Function to validate if input date is valid
function validateDate($date, $format = 'Y-m-d'){
  $d = DateTime::createFromFormat($format, $date);
  return $d && $d->format($format) === $date;
}

//Function to Validate Meeting Link if valid
function validateURL($url) {

  $url = "https://".$url;
  $substring = "meet.google.com";

  if (strpos($url, $substring) !== false) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
  } else {
    return 0;
  }

}



if($_POST['btnAttended']){

  $ids = $_POST['btnAttended'];
  

    $updateSessionDetails = $dbh4->query("UPDATE tbmicrosession SET fld_attended = 1  where fld_id = '".$ids."' ");

}

if($_POST['btnNotAttended']){
  $ids =  $_POST['btnNotAttended'];
  
    $updateSessionDetails = $dbh4->query("UPDATE tbmicrosession SET fld_attended = 2  where fld_id = '".$ids."' ");

}



if($_POST['btnReschedule']){
  $ids =  $_POST['btnReschedule'];

  // print_r($_POST);
  // echo "<Br>";

  // if (validateURL($_POST['rescheduleLink'])) {
  //   echo "The URL is valid.";
  // } else {
  //     echo "The URL is invalid.";
  // }




  if(validateDate($_POST['changeScheduleDate']) && validateURL($_POST['rescheduleLink'])){
  
      // echo "Valid date"; 
      // echo "UPDATE tbmicrosession SET fld_reschedule_date = '".$_POST['changeScheduleDate']."', fld_reschedule_ts = '".date("Y-m-d H:i:s")."', fld_rescheduled_by = '".$_SESSION['user_id']."',  fld_reschedule_date = '".$_POST['changeScheduleDate']."', fld_attended = NULL, fld_meeting_link = '".$_POST['rescheduleLink']."'  where fld_id = '".$ids."' " ;
      // echo "<br>";

      $updateSessionDetails = $dbh4->query("UPDATE tbmicrosession SET fld_reschedule_date = '".$_POST['changeScheduleDate']."', fld_reschedule_ts = '".date("Y-m-d H:i:s")."', fld_rescheduled_by = '".$_SESSION['user_id']."',  fld_reschedule_date = '".$_POST['changeScheduleDate']."', fld_attended = NULL, fld_meeting_link = '".$_POST['rescheduleLink']."'  where fld_id = '".$ids."' ");
      if($updateSessionDetails){
        $msg = "Session has been successfully re-scheduled. ";
        $msgclr = "success";
      }
   
  }else{

    $msg = "Kindly select Re-schedule Date and Meeting Link. ";
    $msgclr = "danger";
  }
 

}


?><!-- Main content -->
<section class="content">

    <?php
      if ($msg && $msgclr) {
    ?>
    <div class="alert alert-<?php echo $msgclr?> alert-dismissible fade show mb-2" role="alert">
    <?php echo $msg ?>
    </div>
    <?php
      }
    ?>

  <?php
      if ($_GET['submit'] == 'reject') {
    ?>
    <div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
    Micro-session schedule has been successfully rejected.
    </div>
    <?php
      }
    ?>

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <div class="input-group-prepend">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          Not-attended
        </button>
        <ul class="dropdown-menu">
          <li class="dropdown-item"><a href="main.php?nid=146&sid=0&rid=0">Approved</a></li>
          <li class="dropdown-item"><a href="main.php?nid=146&sid=1&rid=0">Attended</a></li>
          <li class="dropdown-item"><a href="main.php?nid=146&sid=2&rid=0">Not-attended</a></li>
        </ul>
      </div>
    </div>
    <form method="POST">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table text-center" id="microSessionTable" >
            <thead class="text-uppercase">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Submitting Entity</th>
                    <th scope="col">Micro Session Stage</th>
                    <th scope="col">Filed Date</th>
                    <th scope="col">Date Scheduled</th>
                    <th scope="col">Attendees</th>
                    <th scope="col">Meeting Link</th>
                    <th scope="col">Assigned Personnel</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;

                $getApprovedSessions = $dbh4->query("SELECT fld_ctrlno, fld_provcode, fld_schedule_date, fld_reschedule_date, fld_reschedule_ts, fld_rescheduled_by, fld_email, fld_assigned_personnel, fld_process_type, fld_status, fld_schedule_ts, fld_name  AS attendees , fld_id  AS ids, fld_meeting_link, fld_attended from tbmicrosession where fld_status = 1 and fld_attended = 2 order by fld_schedule_date asc");
                while($gps = $getApprovedSessions->fetch_array()){

               
                // $attendees = explode(',', $gsd['attendees']);

                if($gps['fld_attended'] == 1){
                  $validation = "hidden";
                }elseif($gps['fld_attended'] == 2){
                  $validation = "hidden";
                }

                if($gps['fld_process_type'] == 2){
                  $gps['fld_process_type'] = "Stage 2 - Training & Evalution";
                }elseif($gps['fld_process_type'] == 3){
                  $gps['fld_process_type'] = "Stage 3 - Production";
                }

                $scheduleDate = $gps['fld_schedule_date'];

                $gps['fld_schedule_ts'] = date("F d, Y", strtotime($gps['fld_schedule_ts']));
                // $gps['fld_schedule_date'] = date("F d, Y", strtotime($gps['fld_schedule_date']));

                if($gps['fld_reschedule_date'] != NULL){
                  $gps['fld_reschedule_date'] = date("F d, Y", strtotime($gps['fld_reschedule_date']));
                }


                
                if($gps['fld_reschedule_date'] == NULL){
                  $scheduleDate = date("F d, Y", strtotime($gps['fld_schedule_date']));
                }else{
                  $scheduleDate = date("F d, Y", strtotime($gps['fld_reschedule_date']))."*";
                }

                $gps['attendees'] = str_replace("|", "<br>",$gps['attendees']);
                $gps['attendees'] = rtrim($gps['attendees'], ", ");

                $gps['fld_email'] = str_replace("|", "<br> ",$gps['fld_email']);
                $gps['fld_email'] = rtrim($gps['fld_email'], ", ");

                $getCompanyName = $dbh4->query("SELECT AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$gps['fld_provcode']."' ");
                $gcn = $getCompanyName->fetch_array();

                $empNames = $dbh6->query("SELECT * from tbusers where pkUserId = '".$gps['fld_assigned_personnel']."' ");
                $empname = $empNames->fetch_array();
             
                ?>
                <tr>
                <form method="post" action="main.php?nid=138&sid=2&rid=0">
                    <!-- <th scope="row"><?php echo $gps['ids']; ?></th>      -->
                    <th scope="row"><?php echo $counter++; ?></th> 
                    <td><?php echo $gcn['name'] ?></td>
                    <td><?php echo $gps['fld_process_type'];?></td>
                    <td><?php echo $scheduleDate;?></td>
                    <td><?php  if($gps['fld_reschedule_date'] == NULL) {echo $gps['fld_schedule_date'];}else{echo $gps['fld_reschedule_date'];};?></td>
                    <td><?php echo $gps['attendees'];?></td>
                    <td><a href="https://<?php echo $gps['fld_meeting_link'] ?>" class="btn btn-primary"><?php echo $gps['fld_meeting_link'] ?></a></td>
                    <td>
                    <select class="custom-select classifyDisp text-center" name="assignedEmployee" id="assignedEmployee"  onchange="submit()" disabled >
                      <option value="<?php echo $gps['fld_assigned_personnel'];?>" selected="" disabled=""><?php echo $empname['fld_name'] ?></option>
                     </select>
                    </td>
                    <td>
                      <!-- <button class="btn btn-success btn-block" name="btnAttended" value="<?php echo $gps['ids']; ?>" >Attended</button> -->
                      <button class="btn btn-danger btn-block" name="btnNotAttended" value="<?php echo $gps['ids']; ?>"  disabled>Not Attended</button>
                    </td>
                    <td>
                    <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#exampleModalViewDetails<?php echo $gps['ids']; ?>">Reschedule</button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModalViewDetails<?php echo $gps['ids']; ?>">
                                          <input type="hidden" name="validatedId" value="<?php echo $gps['ids']; ?>">
                                          <?php 
                                          ?>
                                            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"><b><?php echo $gcn['name'] ?></b> </h5>
                                                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                    </div>
                                                <div class="d-flex  justify-content-around py-2">
                                                    <div class="modal-body">
                                                            <?php 
                                                            
                                                            $getValidatorName = $dbh5->query("SELECT * from tbcicusers where pkUserId = '".$gi['fld_validated_by']."' ");
                                                            $gvn = $getValidatorName->fetch_array();
                                                            
                                                            ?>
                                                            <table class="table table-bordered table-hover text-center ">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Attendees</th>
                                                                        <th>Email</th>
                                                                        <th>Schedule Date</th>       
                                                                   
    
                                                                    </tr>
                                                                </thead>
                                                                <tbody>                                               
                                                                    <tr>
                                                                        <td><?php echo $gps['attendees'] ?></td>
                                                                        <td><?php echo $gps['fld_email']; ?></td>
                                                                        <td><?php echo $gps['fld_schedule_date']; ?></td>
                                                                    
                                                                    </tr>
                                                                </tbody>
                                                            </table>

                                                    
                                                        <div class = "row">

                                                        <div class="form-group col-6" >
                                                            <label for="validateExtenstionRemarks">Select Re-schedule Date:</label>
                                                            <input type="date" class="form-control"  name="changeScheduleDate"  value="<?php echo $_POST['changeScheduleDate'] ?>" >
                                                        </div>

                                                        <div class="form-group col-6" >
                                                            <label for="validateExtenstionRemarks">Re-schedule Meeting Link:</label>
                                                            <input type="text" class="form-control"  name="rescheduleLink"  value="<?php echo $_POST['rescheduleLink'] ?>" >
                                                        </div>

                                                        </div>

                                                    
                                                        <div class="modal-footer d-flex justify-content-between">
                                                            <button type="submit" name="btnReschedule" value="<?php echo $gps['ids']; ?>" class="btn btn-success">Re-schedule</button>
                                                            <button type="button" class="btn btn-danger"  data-dismiss="modal">Cancel</button>
                                                        </div>

                                                        
                                                    </div>
                                                
                                                    <!-- <div class="embed-responsive embed-responsive-4by3">
                                                            <iframe class="embed-responsive-item" src="http://localhost/github/CE-Portal/rfefiles/<?php echo $gi['fld_file_name']; ?>.pdf" ></iframe>
                                                    </div>   -->
                                                </div>
                                                <!-- div flex end -->
                                            </div>
                                        </div> 
                    </td>
                    </form>
                </tr>
                <?php
                    }
                ?>
             
            </tbody>    
        </table>
        </div>  
    </div>
    <!-- /.card-body -->
  </div>
  </form>
  <!-- /.card -->

</section>
<!-- /.content -->