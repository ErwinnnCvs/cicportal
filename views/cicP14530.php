<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
?><!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <div class="input-group-prepend">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          Rejected
        </button>
        <ul class="dropdown-menu">
          <li class="dropdown-item"><a href="main.php?nid=137&sid=0&rid=0">Pending</a></li>
          <li class="dropdown-item"><a href="main.php?nid=138&sid=0&rid=0">Approved</a></li>
          <li class="dropdown-item"><a href="main.php?nid=137&sid=3&rid=0">Rejected</a></li>
        </ul>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table text-center">
            <thead class="text-uppercase">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Submitting Entity</th>
                    <th scope="col">Micro Session Stage</th>
                    <th scope="col">Filed Date</th>
                    <th scope="col">Date Scheduled</th>
                    <th scope="col">Attendees</th>
                    <th scope="col">Remarks </th>
                    <th scope="col">Status</th>
                    <!-- <th scope="col">Action</th> -->
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;

                $getPendingSessions = $dbh4->query("SELECT fld_ctrlno, fld_provcode, fld_schedule_date, fld_process_type, fld_status, fld_schedule_ts, GROUP_CONCAT(fld_name SEPARATOR ', ') AS attendees , GROUP_CONCAT(fld_id SEPARATOR '|') AS ids, fld_approval_remarks from tbmicrosession where fld_status = 2 group by fld_schedule_date, fld_process_type order by fld_schedule_ts desc");
                while($gps = $getPendingSessions->fetch_array()){



                if($gps['fld_process_type'] == 2){
                  $gps['fld_process_type'] = "Stage 2 - Training & Evalution";
                }elseif($gps['fld_process_type'] == 3){
                  $gps['fld_process_type'] = "Stage 3 - Production";
                }

                $scheduleDate = $gps['fld_schedule_date'];

                $gps['fld_schedule_ts'] = date("F d, Y", strtotime($gps['fld_schedule_ts']));
                $gps['fld_schedule_date'] = date("F d, Y", strtotime($gps['fld_schedule_date']));

                $getCompanyName = $dbh4->query("SELECT AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$gps['fld_provcode']."'");
                $gcn = $getCompanyName->fetch_array();
             
                ?>
                <tr>
                    <th scope="row"><?php echo $counter++; ?></th>
                    <td><?php echo $gcn['name'] ?></td>
                    <td><?php echo $gps['fld_process_type'];?></td>
                    <td><?php echo $gps['fld_schedule_ts'];?></td>
                    <td><?php echo $gps['fld_schedule_date'];?></td>
                    <td><?php echo $gps['attendees'];?></td>
                    <td><?php echo $gps['fld_approval_remarks'];?></td>
                    <td>Rejected</td>
                    <!-- <td>
                      <a href="main.php?nid=137&sid=1&rid=0&ids=<?php echo base64_encode($gps['ids']); ?>&date=<?php echo base64_encode($scheduleDate); ?>&st=<?php echo base64_encode($gps['fld_status']); ?>&ctrl=<?php echo base64_encode($gps['fld_ctrlno']); ?>" class="btn btn-primary">View Schedule</a>           
                    </td> -->
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