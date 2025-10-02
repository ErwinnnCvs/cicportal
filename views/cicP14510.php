<?php


// echo "SELECT fld_ctrlno, fld_provcode, fld_schedule_date, fld_process_type, fld_status, fld_schedule_ts, GROUP_CONCAT(fld_name SEPARATOR ', ') AS attendees , GROUP_CONCAT(fld_id SEPARATOR '|') AS ids from tbmicrosession where fld_schedule_date = '".$_GET['date']."' and fld_ctrlno = '".$_GET['id']."' group by fld_schedule_date order by fld_schedule_ts desc ";
$_GET['ctrl'] = base64_decode($_GET['ctrl']);
$_GET['st'] = base64_decode($_GET['st']);
$_GET['ids'] = base64_decode($_GET['ids']);
$_GET['date'] = base64_decode($_GET['date']);
$_GET['pt'] = base64_decode($_GET['pt']);


echo "SELECT fld_ctrlno, fld_provcode, fld_schedule_date, fld_process_type, fld_status, fld_schedule_ts, GROUP_CONCAT(fld_name SEPARATOR ', ') AS attendees from tbmicrosession where fld_schedule_date = '".$_GET['date']."' and fld_ctrlno = '".$_GET['ctrl']."' ".$query." group by fld_schedule_date order by fld_schedule_ts desc";



// echo $_GET['pt'];

if($_GET['st'] == 1){
    $query = " and fld_status = 1";
}elseif($_GET['st'] == 2){
    $query = " and fld_status = 2";
}elseif($_GET['st'] == 0){
    $query = " and fld_status = 0";
}

if($_GET['pt'] == 2){
    $query .= " and fld_process_type = 2";
}elseif($_GET['pt'] == 3){
    $query .= " and fld_process_type = 3";
}


$getSessionDetails = $dbh4->query("SELECT fld_ctrlno, fld_provcode, fld_schedule_date, fld_process_type, fld_status, fld_schedule_ts, fld_name  AS attendees from tbmicrosession where fld_schedule_date = '".$_GET['date']."' and fld_ctrlno = '".$_GET['ctrl']."' ".$query." order by fld_schedule_ts desc");
$gsd = $getSessionDetails->fetch_array();

$attendees = explode('|', $gsd['attendees']);

$ids = $_GET['ids'];



$gsd['fld_schedule_ts'] = date("F d, Y", strtotime($gsd['fld_schedule_ts']));
$gsd['fld_schedule_date'] = date("F d, Y", strtotime($gsd['fld_schedule_date']));

if($gsd['fld_process_type'] == 2){
    $gsd['fld_process_type'] = "Stage 2 - Training & Evalution";
}elseif($gsd['fld_process_type'] == 3){
    $gsd['fld_process_type'] = "Stage 3 - Production";
}

// print_r($_POST);
// echo "<br>";


if($_POST['btnApprove']){

    if(empty(trim($_POST['link']))){
        $msg = "Kindly enter a valid meeting link for Micro Session.";
        $msgclr = "danger";
    }else{

        // echo "UPDATE tbmicrosession SET fld_meeting_link = '".$_POST['link']."', fld_assigned_personnel = '".$_POST['personnel']."', fld_approval_remarks = '".$_POST['remarks']."', fld_approved_by = '".$_SESSION['user_id']."', fld_status = 1, fld_approval_ts = '".date("Y-m-d H:i:s")."' where fld_id = '".$ids."' and fld_ctrlno = '".$_GET['ctrl']."' ";

        $updateSessionDetails = $dbh4->query("UPDATE tbmicrosession SET fld_meeting_link = '".$_POST['link']."', fld_assigned_personnel = '".$_POST['personnel']."', fld_approval_remarks = '".$_POST['remarks']."', fld_approved_by = '".$_SESSION['user_id']."', fld_status = 1, fld_approval_ts = '".date("Y-m-d H:i:s")."' where fld_id = '".$ids."' and fld_ctrlno = '".$_GET['ctrl']."' ");

        // foreach($ids as $id){

    
        // }
    }



    if($updateSessionDetails){
        echo "<script type='text/javascript'>
        window.location.href = 'main.php?nid=145&sid=0&rid=0&submit=success';
        </script>";
        unset($_POST);
    }



}

if($_POST['btnReject']){

    // foreach($ids as $id){
        
        // echo "UPDATE tbmicrosession SET fld_meeting_link = '".$_POST['link']."', fld_assigned_personnel = '".$_POST['personnel']."', fld_approval_remarks = '".$_POST['remarks']."', fld_approved_by = '".$_SESSION['user_id']."', fld_status = 2, fld_approval_ts = '".date("Y-m-d H:i:s")."' where fld_id = '".$id."' and fld_ctrlno = '".$_GET['ctrl']."' ";
        // echo "<br>";

        $updateSessionDetails = $dbh4->query("UPDATE tbmicrosession SET fld_meeting_link = '".$_POST['link']."', fld_assigned_personnel = '".$_POST['personnel']."', fld_approval_remarks = '".$_POST['remarks']."', fld_approved_by = '".$_SESSION['user_id']."', fld_status = 2, fld_approval_ts = '".date("Y-m-d H:i:s")."' where fld_id = '".$ids."' and fld_ctrlno = '".$_GET['ctrl']."' ");

    // }

    if($updateSessionDetails){
        echo "<script type='text/javascript'>
        window.location.href = 'main.php?nid=145&sid=0&rid=0&submit=reject';
        </script>";
        unset($_POST);
    }



}





?><!-- Main content -->
<section class="content">

<?php
      if ($msg && $msgclr) {
    ?>
    <div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
        <?php echo $msg ?>
    </div>
    <?php
      }
    ?>

  <!-- Default box -->
        <div class="card">
            <div class="card-header bg-info">
            <div class="card-title">Micro Session Details</div>
            </div>
            <div class="card-body">
                <form method="post" action="">
                <div class="row">
                    <div class="col-lg-6">  

                        <div class="col-lg-12">  
                         
                            <label class="col-form-label">Micro Session Stage: <?php echo $gsd['fld_process_type']; ?> </label>
                         
                        </div>

                        <div class="col-lg-12">  
                            <label class="col-form-label">Scheduled Date: <?php echo $gsd['fld_schedule_date']?></label>
                        
                        </div>

                        <div class="col-lg-12">              
                            <label class="col-form-label">Attendees:</label>
                            <?php

                          
                            $getAssignedPersonnel = $dbh->query("SELECT * from tbictpersonnel where fld_ctrlno = '".$_GET['ctrl']."';");
                            $gap = $getAssignedPersonnel->fetch_array();

                            $getEmployeeName = $dbh7->query("SELECT * from tbusers where pkUserId = '".$gap['fld_userid']."' ");
                            $gen = $getEmployeeName->fetch_array();
                    

                            foreach($attendees as $att){
                                echo '<p>'.$att.'</p>';
                            }                
                            ?>
                            
                        </div>
            
                    </div>

                    <div class="col-lg-6">
                        <div class="col-lg-12">  
                            <label class="col-form-label">Add Meeting Link , Sample Format (meet.google.com/aaa-bbbb-ccc)</label>
                            <small></small>
                             <input class="form-control" type="text" name="link" placeholder="" value="<?php echo $attendeeCtr ?>" >                           
                        </div>

                        <div class="col-lg-12">  
                            <label class="col-form-label">Add Assigned Personnel</label>
                            <input class="form-control" type="text" name="personnel" placeholder="" value="<?php echo $gen['pkUserId']?>" hidden >                           

                             <p><?php echo $gen['fld_name']; ?></p>                           
                        </div>

                        <div class="col-lg-12">  
                            <label class="col-form-label">Add Remarks</label>
                             <textarea class="form-control" type="text" name="remarks" placeholder="" value="<?php echo $attendeeCtr ?>" > </textarea>                   
                        </div>

                        <div class="col-lg-12 mt-4   ">
                                <button class="btn btn-success " type="submit" name="btnApprove" value="1">Approve</button>                          
                                <button class="btn btn-danger" type="submit" name="btnReject" value="1">Reject</button>                         
                        </div>


                    </div>
                </div>
            </div>
            </form>
            <!-- /.card-body -->
        </div>
  <!-- /.card -->
  
</section>
<!-- /.content -->