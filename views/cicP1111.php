<?php

?>
<!-- Main content -->
<section class="content">

  <div class="row">
    <div class="col-10">
      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <div class="input-group-prepend">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              Generated Items
            </button>
            <ul class="dropdown-menu">
              <li class="dropdown-item"><a href="main.php?nid=11&sid=0&rid=0">Pending</a></li>
              <li class="dropdown-item"><a href="main.php?nid=11&sid=1&rid=1">Generated</a></li>
              <!-- <li class="dropdown-item"><a href="main.php?nid=3&sid=0&rid=0&fstatus=3">Completed</a></li> -->
            </ul>
          </div>
        </div>
        <div class="card-body table-responsive p-0" style="height: 700px;">
          <table class="table table-hover table-striped">
            <thead>
              <tr>
                <th><center>#</center></th>
                <th>Mnemonic</th>
                <th>Company Name</th>
                <th>Special Accessing Entity</th>
                <th>Date Time</th>
                <th><center>Action</center></th>
              </tr>
            </thead>
            <tbody>
                <?php
                  $get_entity_for_generation = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provcode, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, fld_dqua_sae_selected, fld_dqua_cred_ts, fld_mnemonics FROM tbentities WHERE fld_dqua_cred_status = 1");
                  while ($gefg=$get_entity_for_generation->fetch_array()) {
                    $c++;
                ?>
                <form method="post">
                <tr>
                  <input type="hidden" name="controlno" value="<?php echo $gefg['fld_ctrlno']; ?>">
                  <td><center><?php echo $c; ?></center></td>
                  <td><?php echo $gefg['fld_mnemonics']; ?></td>
                  <td><?php echo $gefg['name']; ?></td>
                  <td><?php echo $sae_label[$gefg['fld_dqua_sae_selected']]; ?></td>
                  <td><?php echo date("F d, Y H:ia", strtotime($gefg['fld_dqua_cred_ts'])); ?></td>
                  <td>
                    <center>
                      <!-- <button class="btn btn-primary" name="sbtGenerateDQUAAccess" value="1">Download</button> -->
                      <a href="pdf/dqua/credentials/DQUA_<?php echo $gefg['provcode'] ?>.csv" class="btn btn-primary">Download</a>
                    </center>
                  </td>
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
    </div>
    <div class="col-2">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">FILTERS</h3>
        </div>
        <div class="card-body">
          <form method="post">
            <label>Provider Code</label>
            <input type="text" name="filter-provider-code" class="form-control" placeholder="Any" value="<?php echo $_POST['filter-provider-code']; ?>">
            <br>
            <label>Name</label>
            <input type="text" name="filter-company-name" class="form-control" placeholder="Any" value="<?php echo $_POST['filter-company-name']; ?>">
            <br>
            <div class="form-group">
              <label>Type</label>
              <select class="form-control" name="filter-type">
                <option>All</option>
                <?php
                  foreach ($SE as $key => $value) {
                    echo "<option value='".$key."'".$seltyp[$key].">".$value."</option>";
                  }
                ?>
              </select>  
            </div>
            <div class="form-group">
              <label>Upload Date</label>
                
                <div class="input-group">
                  <button type="button" class="btn btn-default float-right" id="daterange-btn">
                    <i class="far fa-calendar-alt"></i> Date range picker
                    <i class="fas fa-caret-down"></i>
                  </button>
                </div>
                <div id="reportrange">
                  <input type="text" name="filter-date" id="filter-date" class="form-control" value="<?php echo $_POST['filter-date']; ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block" value="1" name="sbtFilter">Apply</button>
            <a href="main.php?nid=2&sid=0&rid=0" class="btn btn-default btn-block">Clear</a>
          </form>
        </div>
      </div>
      <!-- <div class="card">
        <div class="card-header">
          <h3 class="card-title">Upload Entity</h3>
        </div>
        <div class="card-body">
          <button type="submit" class="btn btn-secondary btn-block" value="1" name="sbtFilter">Upload</button>
        </div>
      </div> -->
    </div>
  </div>
</section>
<!-- /.content -->