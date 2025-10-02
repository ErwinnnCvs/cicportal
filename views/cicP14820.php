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
          Rejected Items
        </button>
        <ul class="dropdown-menu">
          <li class="dropdown-item"><a href="main.php?nid=148&sid=0&rid=0">Pending</a></li>
          <li class="dropdown-item"><a href="main.php?nid=148&sid=1&rid=1">Approved</a></li>
          <li class="dropdown-item"><a href="main.php?nid=148&sid=2&rid=0">Rejected</a></li>
        </ul>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table text-center">
            <thead class="text-uppercase">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Requested Date</th>
                    <th scope="col">Process Type</th>
                    <th scope="col">Date Requested</th>
                    <th scope="col">Endorsed By</th>
                    <th scope="col">Date Endorsed</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;
                    $get_all_pending_requests = $dbh4->query("SELECT * FROM tbextensionicmt WHERE fld_ext_status = 2 ORDER BY fld_validate_ts DESC");
                    while ($gapr=$get_all_pending_requests->fetch_array()) {

                        $getValidatorName = $dbh5->query("SELECT * from tbcicusers where pkUserId = '".$gapr['fld_validate_by']."' ");
                        $gvn = $getValidatorName->fetch_array();

                        // $get_company_name = $dbh4->query("SELECT AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM tbentities WHERE fld_ctrlno = ".$gvn['fld_ctrlno'].";");
                        // $gcn=$get_company_name->fetch_array();

                        $user = $gvn['fld_name'];
                  

                        $gapr['fld_ext_date'] = date("M d, Y", strtotime($gapr['fld_ext_date']));
                        $gapr['fld_inserted_date'] = date("M d, Y", strtotime($gapr['fld_inserted_date']));
                        $minDate =  date("Y-m-d", strtotime($gapr['fld_ext_date']));

                        if($gapr['fld_process_type'] == 1){
                            $gapr['fld_process_type'] = "Registration";
                        }elseif($gapr['fld_process_type'] == 2){
                            $gapr['fld_process_type'] = "Training & Evaluation";
                        }elseif($gapr['fld_process_type'] == 3){
                            $gapr['fld_process_type'] = "Production";
                        }

                
                        if($gapr['fld_ext_status'] == 0){
                            $status = "ENDORSED";
                        }
                ?>
                <tr>
                    <input type="hidden" name="id_request" id="id_request" value="<?php echo $gapr['fld_id']; ?>">
                    <th scope="row"><?php echo $counter++; ?></th>
                    <td><?php echo date("F d, Y", strtotime($gapr['fld_ext_date'])); ?></td>
                    <td><?php echo $gapr['fld_process_type']; ?></td>
                    <td><?php echo date("F d, Y", strtotime($gapr['fld_inserted_date'])); ?></td>
                    <td><?php echo $user; ?></td>
                    <td><?php echo date("F d, Y h:i A", strtotime($gapr['fld_validate_ts'])); ?></td>
                    <td>
                        <a href="main.php?nid=147&sid=1&rid=0&id=<?php echo $gapr['fld_id']; ?>" class="btn btn-primary">View Request</a>
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