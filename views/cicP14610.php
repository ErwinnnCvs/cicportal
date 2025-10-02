<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// print_r($_POST);

if($_POST['btnAttended']){

  $ids = $_POST['btnAttended'];


    $updateSessionDetails = $dbh4->query("UPDATE tbmicrosession SET fld_attended = 1  where fld_id = '".$ids."' ");

}

if($_POST['btnNotAttended']){
  $ids = $_POST['btnNotAttended'];

    $updateSessionDetails = $dbh4->query("UPDATE tbmicrosession SET fld_attended = 2  where fld_id = '".$ids."' ");


}


if($_POST['btnReschedule']){
  print_r($_POST);
}


?><!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <div class="input-group-prepend">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          Attended
        </button>
        <ul class="dropdown-menu">
          <!-- <li class="dropdown-item"><a href="main.php?nid=137&sid=0&rid=0">Pending</a></li> -->
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
       
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;

                // echo "SELECT fld_ctrlno, fld_provcode, fld_schedule_date, fld_process_type, fld_status, fld_schedule_ts, fld_name AS attendees , fld_id as ids, fld_meeting_link, fld_attended, fld_assigned_personnel from tbmicrosession where fld_status = 1 and fld_attended = 1  order by fld_schedule_date asc";
                $getApprovedSessions = $dbh4->query("SELECT fld_ctrlno, fld_provcode, fld_schedule_date, fld_process_type, fld_status, fld_email, fld_schedule_ts, fld_name AS attendees , fld_id as ids, fld_meeting_link, fld_attended, fld_assigned_personnel from tbmicrosession where fld_status = 1 and fld_attended = 1  order by fld_schedule_date asc");
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
                $gps['fld_schedule_date'] = date("F d, Y", strtotime($gps['fld_schedule_date']));

                $gps['attendees'] = str_replace("|", ", ",$gps['attendees']);
                $gps['attendees'] = rtrim($gps['attendees'], ", ");

                $gps['fld_email'] = str_replace("|", ", ",$gps['fld_email']);
                $gps['fld_email'] = rtrim($gps['fld_email'], ", ");

                $getCompanyName = $dbh4->query("SELECT AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$gps['fld_provcode']."' ");
                $gcn = $getCompanyName->fetch_array();


                $empNames = $dbh6->query("SELECT * from tbusers where pkUserId = '".$gps['fld_assigned_personnel']."' ");
                $empname = $empNames->fetch_array();
             
                ?>
                <tr>
                <form method="post" action="main.php?nid=138&sid=1&rid=0">
                    <th scope="row"><?php echo $gps['ids']; ?></th>     
                    <!-- <th scope="row"><?php echo $counter++; ?></th>  -->
                    <td><?php echo $gcn['name'] ?></td>
                    <td><?php echo $gps['fld_process_type'];?></td>
                    <td><?php echo $gps['fld_schedule_ts'];?></td>
                    <td><?php echo $gps['fld_schedule_date'];?></td>
                    <td><?php echo $gps['attendees'];?></td>
                    <td><a href="https://<?php echo $gps['fld_meeting_link'] ?>" class="btn btn-primary"><?php echo $gps['fld_meeting_link'] ?></a></td>
                    <td>
                    <select class="custom-select classifyDisp text-center" name="assignedEmployee" id="assignedEmployee"  onchange="submit()" disabled >
                      <option value="<?php echo $gps['fld_assigned_personnel'];?>" selected="" disabled=""><?php echo $empname['fld_name'] ?></option>
            
                     </select>
                    </td>
                    <td>
                      <button class="btn btn-success btn-block" name="btnAttended" value="<?php echo $gps['ids']; ?>" disabled>Attended</button>
                      <!-- <button class="btn btn-danger btn-block" name="btnNotAttended" value="<?php echo $gps['ids']; ?>"  >Not Attended</button> -->
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