<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <div class="input-group-prepend">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              Delisted Submitting Entities
            </button>
            <ul class="dropdown-menu">
              <li class="dropdown-item"><a href="main.php?nid=15&sid=0&rid=0">Delist</a></li>
              <li class="dropdown-item"><a href="main.php?nid=15&sid=1&rid=1">Delisted</a></li>
              <!-- <li class="dropdown-item"><a href="main.php?nid=3&sid=0&rid=0&fstatus=3">Completed</a></li> -->
            </ul>
          </div>
    </div>
    <div class="card-body">
      <?php
        if ($msg) {
      ?>
      <div class="alert alert-<?php echo $msgclr; ?> alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Information!</h5>
        <?php echo $msg; ?>
      </div>
      <?php
        }
      ?>
      <table class="table table-bordered" id="delisted_se">
        <thead>
          <tr>
            <th>Provider Code</th>
            <th>Company Name</th>
            <th>Date Delisted</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $get_all_fi = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provcode, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, AES_DECRYPT(fld_ip, md5(CONCAT(fld_ctrlno, 'RA3019'))) as ip, fld_operational FROM tbentities WHERE fld_operational = 1");
            while ($gaf=$get_all_fi->fetch_array()) {
          ?>
          <tr>
            <td><?php echo $gaf['provcode']; ?></td>
            <td><?php echo $gaf['name']; ?></td>
            <td>
              
            </td>
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

</section>
<!-- /.content -->
