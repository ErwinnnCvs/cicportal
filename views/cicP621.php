<?php

if ($_POST['sbtSaveAccessLimit']) {
  echo $_POST['access_limit'];
  echo $_POST['ctrlno'];

  if ($dbh4->query("UPDATE tbentities SET fld_access_limit = '".$_POST['access_limit']."' WHERE fld_ctrlno = '".$_POST['ctrlno']."'")) {
    $msg = "Successfully updated";
    $msgclr = "success";
  } else {
    $msg = "Error updating";
    $msgclr = "danger";
  }
}

?>
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <h1></h1>
      </div>
        <a href="main.php?nid=6&sid=0&rid=0" class="float-right">Inquiries</a>
    </div>
    <div class="card-body">
      <?php
        if ($msg) {
      ?>
      <div class="alert alert-<?php echo $msgclr; ?> alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> Information!</h5>
        <?php echo $msg; ?>
      </div>
      <?php
        }
      ?>
      <table class="table table-bordered table-hover table-sm">
        <thead>
          <tr>
            <th><center>#</center></th>
            <th>Provider Code</th>
            <th>Company Name</th>
            <th>Access Limit</th>
            <th><center>Action</center></th>
          </tr>
        </thead>
        <tbody>
          <?php
            $sql=$dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provcode, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, fld_access_limit FROM tbentities WHERE fld_aeis >= 1");
            while($d=$sql->fetch_array()){
              $c++;
          ?>
          <tr>
            <td><center><?php echo $c; ?></center></td>
            <td><?php echo $d['provcode']; ?></td>
            <td><?php echo $d['name']; ?></td>
            <td><?php echo number_format($d['fld_access_limit']); ?></td>
            <td>
              <center>
                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default<?php echo $d['fld_ctrlno']; ?>">
                  Update
                </button>
              </center>
              <div class="modal fade" id="modal-default<?php echo $d['fld_ctrlno']; ?>">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Access Limit</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form method="post">
                    <div class="modal-body">
                      Enter New Access Limit
                      <input type="text" name="access_limit">
                      <input type="hidden" name="ctrlno" value="<?php echo $d['fld_ctrlno']; ?>">
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary" name="sbtSaveAccessLimit" value="1">Save changes</button>
                    </div>
                  </form>
                </div>
                <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
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