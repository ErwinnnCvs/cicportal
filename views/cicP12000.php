<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">All Entities <small>(data from Prod Utilities and Registration Entity)</small></h3>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-striped table-hovered" id="prod_utilities">
        <thead>
          <tr>
            <th><center>#</center></th>
            <th><center>Control No.</center></th>
            <th><center>Provider Code</center></th>
            <th><center>Company Name</center></th>
            <th><center>Authorized Representative</center></th>
            <th><center>Authorized Email</center></th>
            <th><center>Actions</center></th>
          </tr>
        </thead>
        <tbody>
          <?php
            $counter = 0;
            $sql = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provider_code, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as company, AES_DECRYPT(fld_fname_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) as first_name, AES_DECRYPT(fld_mname_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) as middle_name, AES_DECRYPT(fld_lname_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) as last_name, AES_DECRYPT(fld_extname_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) as extension_name, AES_DECRYPT(fld_email_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) as email FROM tbentities");
            while ($row=$sql->fetch_array()) {
              $counter++;
          ?>

          <tr>
            <form method="post" action="main.php?nid=31&sid=1&rid=1">
            <td><center><?php echo $counter; ?></center></td>
            <td><center><?php echo $row['fld_ctrlno'];; ?></center></td>
            <td><center><?php echo $row['provider_code']; ?><input type="hidden" name="controlno" value="<?php echo $row['fld_ctrlno']; ?>"></center></td>
            <td><center><?php echo $row['company']; ?></center></td>
            <td><center><?php echo utf8_decode($row['first_name']. " " .$row['middle_name']. " " .$row['last_name']. " " .$row['extension_name']); ?></center></td>
            <td><center><?php echo $row['email']; ?></center></td>
            <td><center><button class="btn btn-default btn-sm" type="submit" value="1"><i class="fa fa-eye"></i></button></center></td>
            </form>
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