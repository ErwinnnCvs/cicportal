
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">
        <div class="input-group-prepend">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            Prod Confirmed
          </button>
          <ul class="dropdown-menu">
            <li class="dropdown-item"><a href="main.php?nid=37&sid=0&rid=0">Testing</a></li>
            <li class="dropdown-item"><a href="main.php?nid=37&sid=1&rid=1">Validation</a></li>
            <li class="dropdown-item"><a href="main.php?nid=37&sid=1&rid=2">Prod Confirmation</a></li>
            <li class="dropdown-item"><a href="main.php?nid=37&sid=1&rid=3">Prod Confirmed</a></li>
          </ul>
        </div>
      </h3>
    </div>
    <div class="card-body">
      <?php
        if($msg){
      ?>
      <div class="alert alert-<?php echo $msgclr; ?> alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-info"></i> Information.</h5>
        <?php echo $msg; ?>
      </div>
      <?php
        }
      ?>
      <table class="table table-bordered" id="secompleted">
        <thead>
          <tr>
            <th>Provider Code</th>
            <th>Company Name</th>
            <th>Remarks</th>
            <!-- <th>Production Confirmation</th> -->
          </tr>
        </thead>
        <tbody>
            
                <?php
                    $get_all_registered_entities = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provcode, fld_se_testing_by, fld_se_testing_ts, fld_se_validation_by, fld_se_validation_ts FROM tbentities WHERE fld_process_status > 1 and AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) <> '' and fld_se_testing_status = 3");
                    while($gare=$get_all_registered_entities->fetch_array()){
                ?>
                    <form method="post">
                    <tr>
                        <input type="hidden" name="ctrlno" value="<?php echo $gare['fld_ctrlno']; ?>">
                        <td><?php echo $gare['provcode']; ?></td>
                        <td><?php echo $gare['name']; ?></td>
                        <td>
                            <b>Testing Completed by:</b>
                            <br>
                            <?php 
                                echo $gare['fld_se_testing_by']."<br>".date("F d, Y h:ia", strtotime($gare['fld_se_testing_ts']));
                            ?>
                            <br>
                            <b>Validation Completed by:</b>
                            <br>
                            <?php 
                                echo $gare['fld_se_validation_by']."<br>".date("F d, Y h:ia", strtotime($gare['fld_se_validation_ts']));
                            ?>
                        </td>
                        <!-- <td>
                          <center>
                          <button type="submit" value="1" name="sbtProdConfirmation" class="btn btn-success btn-block">Confirm</button>
                          </center>
                          
                        </td> -->
                    </tr>
                    </form>
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