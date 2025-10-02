<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <div class="input-group-prepend">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          Pending Items
        </button>
        <ul class="dropdown-menu">
          <li class="dropdown-item"><a href="main.php?nid=132&sid=0&rid=0">Pending</a></li>
          <li class="dropdown-item"><a href="main.php?nid=132&sid=1&rid=1">Approved</a></li>
        </ul>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table text-center">
            <thead class="text-uppercase">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Requested By</th>
                    <th scope="col">Requested Date</th>
                    <th scope="col">Submission Type</th>
                    <th scope="col">Date Requested</th>
                    <th scope="col">Endorsed By</th>
                    <th scope="col">Date Endorsed</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;
                    $get_all_pending_requests = $dbh4->query("SELECT * FROM tbrequestextension WHERE fld_status = 1 ORDER BY fld_endorsed_ts DESC");
                    while ($gapr=$get_all_pending_requests->fetch_array()) {

                        $get_user_requested = $dbh->query("SELECT * FROM tbusers WHERE pkUserId = ".$gapr['fld_user']);
                        $gur=$get_user_requested->fetch_array();

                        $get_company_name = $dbh4->query("SELECT AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM tbentities WHERE fld_ctrlno = ".$gur['fld_ctrlno']);
                        $gcn=$get_company_name->fetch_array();

                        $user = $gur['fld_name']. " - " .$gur['email'];
                        $company = $gcn['name'];


                        if($gapr['fld_submission_type'] == 1){
                            $submission_type = "REGULAR SUBMISSION";
                        }

                        if($gapr['fld_status'] == 0){
                            $status = "PENDING";
                        }
                ?>
                <tr>
                    <input type="hidden" name="id_request" id="id_request" value="<?php echo $gapr['fld_id']; ?>">
                    <th scope="row"><?php echo $counter++; ?></th>
                    <td><?php echo $company; ?></td>
                    <td><?php echo date("F d, Y", strtotime($gapr['fld_request_date'])); ?></td>
                    <td><?php echo $submission_type; ?></td>
                    <td><?php echo date("F d, Y", strtotime($gapr['fld_date_requested'])); ?></td>
                    <td><?php echo $gapr['fld_endorsed_by']; ?></td>
                    <td><?php echo date("F d, Y h:i A", strtotime($gapr['fld_endorsed_ts'])); ?></td>
                    <td>
                        <a href="main.php?nid=132&sid=1&rid=0&id=<?php echo $gapr['fld_id']; ?>" class="btn btn-primary">View Request</a>
                    </td>
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