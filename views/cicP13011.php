<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <div class="input-group-prepend">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            Pending Transmittals
          </button>
          <ul class="dropdown-menu">
          <li class="dropdown-item"><a href="main.php?nid=130&sid=0&rid=0">All Tickets Received</a></li>
            <li class="dropdown-item"><a href="main.php?nid=130&sid=1&rid=1">Pending Transmittals</a></li>
            <li class="dropdown-item"><a href="main.php?nid=130&sid=1&rid=2">Filed Transmittals</a></li>
          </ul>
        </div>
    </div>
    <div class="card-body">
          <br>
          <table class="table table-bordered table-hover" id="pendingTransmittal">
            <thead>
              <tr>
              <th>#</th>
              <th>Provider Code</th>
              <th>Company</th>
              <th>Filename</th>
              <th>Date Arrived</th>
              <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $c = 1;
                $get_pending_transmittals = $dbh->query("SELECT * FROM tbprodtickets WHERE fld_transmittal_status = 0");

                while ($gpt=$get_pending_transmittals->fetch_array()) {

                  $filename = explode(":", trim($gpt['fld_subject']));
                  $file = str_split($filename[1], 36);


                  $provcode = substr($file[1], 0, 8);

                  $get_entity_name = $dbh4->query("SELECT AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM tbentities WHERE AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$provcode."'");
                  $gen=$get_entity_name->fetch_array();

                  if($gen['name']){
                    $name = $gen['name'];
                  } else {
                    $name = "<b style='color: red;'>INVALID PROVIDER CODE</b>";
                  }
              ?>
              <tr>
                <td><?php echo $c; ?></td>
                <td><?php echo $provcode; ?></td>
                <td><?php echo $name; ?></td>
                <td><?php echo $file[1].".TXT"; ?></td>
                <td><span class="tag tag-success"><?php echo $gpt['fld_created_time']; ?></span></td>
                <td>Pending</td>
              </tr>
              <?php
                  $c++;
                }
              ?>
            </tbody>
          </table>
    </div>
    </div>
      
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->