<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <div class="input-group-prepend">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          Rejected Items
        </button>
        <ul class="dropdown-menu">
          <li class="dropdown-item"><a href="main.php?nid=147&sid=0&rid=0">Pending</a></li>
          <li class="dropdown-item"><a href="main.php?nid=147&sid=1&rid=1">Endorsed</a></li>
          <li class="dropdown-item"><a href="main.php?nid=147&sid=2&rid=0">Rejected</a></li>
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
                    <th scope="col">Requested By</th>
                    <th scope="col">Submission Type</th>
                    <th scope="col">Date Requested</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
											$counter = 1;
											$key = "RA3019";

                                        
                                            $sql = $dbh4->query("SELECT * FROM tbextensionicmt WHERE fld_ext_status = 4  order by fld_id desc");


											
											while ($gi= $sql->fetch_array()) {
                                                                                            
                                          
                                                $gi['fld_ext_date'] = date("M d, Y", strtotime($gi['fld_ext_date']));
                                                $gi['fld_inserted_date'] = date("M d, Y", strtotime($gi['fld_inserted_date']));
                                                $minDate =  date("Y-m-d", strtotime($gi['fld_ext_date']));
                                                
                                                if($gi['fld_process_type'] == 1){
                                                    $gi['fld_process_type'] = "Registration";
                                                }elseif($gi['fld_process_type'] == 2){
                                                    $gi['fld_process_type'] = "Training & Evaluation";
                                                }elseif($gi['fld_process_type'] == 3){
                                                    $gi['fld_process_type'] = "Production";
                                                }


                                                $get_entity_details = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_provcode, 
                                                AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_name ,
                                                AES_DECRYPT(fld_lname_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_lname_ar, 
                                                AES_DECRYPT(fld_fname_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_fname_ar, 
                                                AES_DECRYPT(fld_mname_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_mname_ar, 
                                                AES_DECRYPT(fld_email_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_email_ar 
                                                FROM tbentities WHERE fld_ctrlno = '".$gi['fld_ctrlno'] ."'");
                                                $ged = $get_entity_details->fetch_array();
								
										?>
                <tr>
                    <input type="hidden" name="id_request" id="id_request" value="<?php echo $gi['fld_id']; ?>">
                    <th scope="row"><?php echo $counter++; ?></th>
                    <td><?php echo date("F d, Y", strtotime($gi['fld_ext_date'])); ?></td>
                    <td>
                        <?php 
                            echo $ged['fld_name']."<br>";
                        ?>
                    </td>
                    <td><?php echo $gi['fld_process_type']; ?></td>
                    <td><?php echo date("F d, Y", strtotime($gi['fld_inserted_date'])); ?></td>
                    <td>
                        <a href="main.php?nid=147&sid=1&rid=0&id=<?php echo $gi['fld_id']; ?>" class="btn btn-primary">View Request</a>
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