<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0 );
error_reporting(E_ALL);

// print_r($_POST);

//Function to validate if input date is valid
function validateDate($date, $format = 'Y-m-d'){
  $d = DateTime::createFromFormat($format, $date);
  return $d && $d->format($format) === $date;
}

if($_POST['btnAttended']){

  $ids = $_POST['btnAttended'];
    $updateSessionDetails = $dbh4->query("UPDATE tbmicrosession SET fld_attended = 1  where fld_id = '".$ids."' ");
}

if($_POST['btnNotAttended']){
  $ids = $_POST['btnNotAttended'];
  
    $updateSessionDetails = $dbh4->query("UPDATE tbmicrosession SET fld_attended = 2  where fld_id = '".$ids."' ");
}


if(isset($_POST['assignedEmployee'])){

    $updateSessionDetails = $dbh4->query("UPDATE tbmicrosession SET fld_assigned_personnel = '".$_POST['assignedEmployee']."' where fld_id = '". $_POST['ids']."'  ");

}

if($_POST['btnReschedule']){
  $ids =  $_POST['btnReschedule'];

  // print_r($_POST);
  // echo "<Br>";

  if(validateDate($_POST['changeScheduleDate']) && $_POST['rescheduleLink'] != ""){
  
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


  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <div class="input-group-prepend">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
        Approved
        </button>
        <ul class="dropdown-menu">
          <li class="dropdown-item"><a href="main.php?nid=146&sid=0&rid=0">Approved</a></li>
          <li class="dropdown-item"><a href="main.php?nid=146&sid=1&rid=0">Attended</a></li>
          <li class="dropdown-item"><a href="main.php?nid=146&sid=2&rid=0">Not-attended</a></li>
        </ul>
      </div>
    </div>

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
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;

                $getApprovedSessions = $dbh4->query("SELECT fld_ctrlno, fld_provcode, fld_schedule_date, fld_reschedule_date, fld_email, fld_process_type, fld_assigned_personnel, fld_status, fld_schedule_ts, fld_name  attendees , fld_id AS ids, fld_meeting_link, fld_attended from tbmicrosession where fld_status = 1 and fld_attended IS NULL order by fld_schedule_date asc");
                while($gps = $getApprovedSessions->fetch_array()){

                  // echo "<pre>";
                  // print_r($gps);
                  // echo "<br>";


                  
                  $getAssignedPersonnel = $dbh->query("SELECT * from tbictpersonnel where fld_ctrlno = '".$gps['fld_ctrlno']."';");
                  $gap = $getAssignedPersonnel->fetch_array();



                  $getEmployeeName = $dbh5->query("SELECT * from tbcicusers where pkUserId = '".$gap['fld_userid']."' ");
                  $gen = $getEmployeeName->fetch_array();


                  $assigned[$gps['fld_assigned_personnel']] = " selected";

                  // $selected = isset($_POST['assignedEmployee']) ? $_POST['assignedEmployee'] : $gps['fld_assigned_personnel'];


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

                // $scheduleDate = $gps['fld_schedule_date'];

                $gps['fld_schedule_ts'] = date("F d, Y", strtotime($gps['fld_schedule_ts']));

                if($gps['fld_reschedule_date'] == NULL){
                  $scheduleDate = date("F d, Y", strtotime($gps['fld_schedule_date']));
                }else{
                  $scheduleDate = date("F d, Y", strtotime($gps['fld_reschedule_date']))."*";
                }



                $gps['attendees'] = str_replace("|", ", ",$gps['attendees']);
                $gps['attendees'] = rtrim($gps['attendees'], ", ");

                $gps['fld_email'] = str_replace("|", "<br> ",$gps['fld_email']);
                $gps['fld_email'] = rtrim($gps['fld_email'], ", ");


                $getCompanyName = $dbh4->query("SELECT AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$gps['fld_provcode']."' ");
                $gcn = $getCompanyName->fetch_array();



             
                ?>
                <tr>
                <form method="post">
                    <!-- <th scope="row"><?php echo $gps['ids']; ?></th>      -->
                    <th scope="row"><?php echo $counter++; ?></th> 
                    <td><?php echo $gcn['name'] ?></td>
                    <td><?php echo $gps['fld_process_type'];?></td>
                    <td><?php echo $gps['fld_schedule_ts'];?></td>
                    <td><?php echo $scheduleDate;?></td>
                    <td><?php echo $gps['attendees'];?></td>
                    <td><a href="https://<?php echo $gps['fld_meeting_link'] ?>" class="btn btn-primary"><?php echo $gps['fld_meeting_link'] ?></a></td>
                    <td>
                    <input type="text" name="ids" value="<?php echo $gps['ids']; ?>" hidden>
                    <select class="custom-select classifyDisp text-center" name="assignedEmployee" id="assignedEmployee" onchange="submit()" >
           
                      <?php

                    $listPersonnel = $dbh->query("SELECT fld_userid from tbictpersonnel group by fld_userid");
                    while( $list = $listPersonnel->fetch_array()){ 
                      echo "SELECT * from tbcicusers where pkUserId = '".$list['fld_userid']."'";
                      $empName = $dbh6->query("SELECT * from tbusers where pkUserId = '".$list['fld_userid']."' ");
                      $empname = $empName->fetch_array();

                      // $select = ($list['fld_userid'] == $selected) ? 'selected': '';
        
                      ?>
                      <option value="<?php echo $list['fld_userid'] ?>" <?php if($list['fld_userid'] == $gps['fld_assigned_personnel']){ echo "selected";}  ?> ><?php echo $empname['fld_name'];  ?></option> 
                    
                    
                    <?php }
                   
                      ?>

                     </select>

                    </td>
                    <td>
                      <button class="btn btn-success btn-block" name="btnAttended" value="<?php echo $gps['ids']; ?>" >Attended</button>
                      <button class="btn btn-danger btn-block" name="btnNotAttended" value="<?php echo $gps['ids']; ?>"  >Not Attended</button>  
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

  <!-- /.card -->

</section>
<!-- /.content -->



                                 <!-- <option value="x" selected=""  disabled=""> </option> -->
                      <!-- <option value="123" <?php if($assigned[123]){ echo "selected";}?>>Philip Gerald Fulgueras</option>
                      <option value="172" <?php if($assigned[172]){ echo "selected";}?>>Maria Patricia Laygo</option>
                      <option value="75"  <?php if($assigned[75]){ echo "selected";}?>>Regine Caraan</option> 
                      <option value="32"  <?php if($assigned[32]){ echo "selected";}?>>Jovis Jell Batongbakal</option> 
                      <option value="149" <?php if($assigned[149]){ echo "selected";}?>>Rayson Rivera</option>    
                      <option value="197" <?php if($assigned[197]){ echo "selected";}?>>Horace Nel Morales</option>
                      <option value="177" <?php if($assigned[177]){ echo "selected";}?>>Jose Julio Manay</option>   
                      <option value="131" <?php if($assigned[131]){ echo "selected";}?>>Victoria Ualat</option>   
                      <option value="164" <?php if($assigned[164]){ echo "selected";}?>>Nicole Soriano</option>    -->

                      <!-- <option value="75"  <?php if($assigned == 75){ echo "selected";}?>>Regine Caraan</option> 
                      <option value="32"  <?php if($assigned == 32){ echo "selected";}?>>Jovis Jell Batongbakal</option> 
                      <option value="123" <?php if($assigned == 123){ echo "selected";}?>>Philip Gerald Fulgueras</option>
                      <option value="172" <?php if($assigned == 172){ echo "selected";}?>>Maria Patricia Laygo</option>
                      <option value="149" <?php if($assigned == 149){ echo "selected";}?>>Rayson Rivera</option>    
                      <option value="197" <?php if($assigned == 197){ echo "selected";}?>>Horace Nel Morales</option>
                      <option value="177" <?php if($assigned == 177){ echo "selected";}?>>Jose Julio Manay</option>   
                      <option value="131" <?php if($assigned == 131){ echo "selected";}?>>Victoria Ualat</option>   
                      <option value="164" <?php if($assigned == 164){ echo "selected";}?>>Nicole Soriano</option>    -->