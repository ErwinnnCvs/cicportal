<?php

if (!$_GET['fstatus']) {
  $current_status = "Pending";
  $query = "SELECT fld_ctrlno, AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_name, AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_provcode, fld_ds_update_stats, fld_type, fld_ds_update_ts, fld_re_ts, fld_status FROM tbentities WHERE fld_process_status = 2 and fld_re_validation_status = 0 and fld_re_validation_ts IS NULL and fld_operational = 0 and fld_registration_type = 1 and AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) NOT LIKE 'SAE%'";
} else {
  if ($_GET['fstatus'] == "1") {
    $current_status = "Pending";
   $query = "SELECT fld_ctrlno, AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_name, AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_provcode, fld_ds_update_stats, fld_type, fld_ds_update_ts, fld_re_ts, fld_status FROM tbentities WHERE fld_process_status = 2 and fld_re_validation_status = 0 and fld_re_validation_ts IS NULL and fld_operational = 0 and fld_registration_type = 1 and AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) NOT LIKE 'SAE%'";
  } elseif ($_GET['fstatus'] == "2") {
    $current_status = "Incomplete";
    $query = "SELECT fld_ctrlno, AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_name, AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_provcode, fld_ds_update_stats, fld_type, fld_ds_update_ts, fld_re_validation_incom_ts, fld_status FROM tbentities WHERE fld_process_status = 2 and fld_re_validation_status = 2 and fld_operational = 0 and AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) NOT LIKE 'SAE%' and fld_registration_type = 1";
  } elseif ($_GET['fstatus'] == "3") {
    $current_status = "Completed";
    $query = "SELECT fld_ctrlno, AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_name, AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_provcode, fld_ds_update_stats, fld_type, fld_ds_update_ts, fld_re_validation_ts, fld_status FROM tbentities WHERE fld_process_status = 3 and fld_re_validation_status = 1 and fld_operational = 0 and AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) NOT LIKE 'SAE%' and fld_registration_type = 1;";
  } else {
    $current_status = "Pending";
   $query = "SELECT fld_ctrlno, AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_name, AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_provcode, fld_ds_update_stats, fld_type, fld_ds_update_ts, fld_re_ts, fld_status FROM tbentities WHERE fld_process_status = 2 and fld_re_validation_status = 0 and fld_re_validation_ts IS NULL and fld_operational = 0 and fld_registration_type = 1 and AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) NOT LIKE 'SAE%'";
  }
}


if (!$_POST['filter-provider-code'] and !$_POST['filter-company-name'] and !$_POST['filter-type'] and !$_POST['filter-date']) {
  $where = " WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) NOT LIKE '%SAE%' and fld_operational = 0 ORDER BY fld_upload_ts_ar DESC LIMIT 50";
}

if ($_POST['sbtFilter']) {
  if (!empty($_POST['filter-provider-code'])) {
    $whsql .= " and AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".addslashes($_POST['filter-provider-code'])."'";
  }

  if (!empty($_POST['filter-company-name'])) {
    $whsql .= " and AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) LIKE '%".addslashes($_POST['filter-company-name'])."%'";
  }

  if (!empty($_POST['filter-type'])) {
    if ($_POST['filter-type'] == "All") {
      $whsql .= "";
    } else {
      $whsql .= " and fld_type = '".addslashes($_POST['filter-type'])."'";
    }
  }

  if (!empty($_POST['filter-date'])) {
    $date_filter = explode(" - ", $_POST['filter-date']);

    $start_date = date("Y-m-d", strtotime($date_filter[0]));
    $end_date = date("Y-m-d", strtotime($date_filter[1]));

    $whsql .= " and fld_re_ts BETWEEN '".$start_date."' AND '".$end_date."'";
  }



}
  $query2 = $query." ".$whsql;

$seltyp[$_POST['filter-type']] = " selected";

?>

<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="row">
    <div class="col-10">
      <div class="card">
        <div class="card-header">
          <!-- <h3 class="card-title">List of Financial Institutions</h3> -->
          
          <div class="input-group-prepend">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              <?php echo $current_status; ?> Items
            </button>
            <ul class="dropdown-menu">
              <li class="dropdown-item"><a href="main.php?nid=3&sid=0&rid=0&fstatus=1">Pending</a></li>
              <li class="dropdown-item"><a href="main.php?nid=3&sid=0&rid=0&fstatus=2">Incomplete</a></li>
              <li class="dropdown-item"><a href="main.php?nid=3&sid=0&rid=0&fstatus=3">Completed</a></li>
            </ul>
          </div>
          <!-- /btn-group -->
        </div>
        <div class="card-body table-responsive p-0" style="height: 700px;">
          <table class="table table-head-fixed text-nowrap">
            <thead>
              <tr>
                <th>Provider Code</th>
                <th>Entity Name</th>
                <th>Entity Type</th>
                <th>Datetime</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $get_all_entities = $dbh4->query($query2);
                while ($gae=$get_all_entities->fetch_array()) {
                  if ($_GET['fstatus'] == 1) {
                    $datetime = date("F d, Y h:ia", strtotime($gae['fld_re_ts']));
                  } elseif ($_GET['fstatus'] == 2) {
                    $datetime = date("F d, Y h:ia", strtotime($gae['fld_re_validation_incom_ts']));
                  } elseif ($_GET['fstatus'] == 3) {
                    $datetime = date("F d, Y h:ia", strtotime($gae['fld_re_validation_ts']));
                  } else {
                    $datetime = date("F d, Y h:ia", strtotime($gae['fld_re_ts']));
                  }
              ?>
              <tr>
                <td><?php echo $gae['fld_provcode']; ?></td>
                <td><a href="main.php?nid=3&sid=1&rid=1&ctrlno=<?php echo $gae['fld_ctrlno']; ?>"><?php echo $gae['fld_name']; ?></a></td>
                <td><?php echo $ent2[$gae['fld_type']]; ?></td>
                <td><?php echo $datetime; ?></td>
              </tr>
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
            <a href="main.php?nid=3&sid=0&rid=0" class="btn btn-default btn-block">Clear</a>
          </form>
        </div>
      </div>
    </div>
  </div>

</section>
<!-- /.content -->