<?php
if ($_POST['sbtSave']) {
  $check_assoc = $dbh4->query("SELECT * FROM tbseassociations WHERE fld_code = '".htmlspecialchars($_POST['code'])."' OR fld_name = '".htmlspecialchars($_POST['name'])."'");
  $ca=$check_assoc->fetch_array();

  if (!$ca['fld_id']) {
    if ($dbh4->query("INSERT INTO tbseassociations (fld_name, fld_code, fld_descriptions) VALUES ('".htmlspecialchars($_POST['name'])."', '".htmlspecialchars($_POST['code'])."', '".htmlspecialchars($_POST['description'])."')")) {
      $msg = "Successfully saved";
    } else {
      $error = "Error encountered. Contact your developer";
    }
  } else {
    $error = "Duplicate Entry";
  }
  
}
?>

<!-- Main content -->
<section class="content">

  <div class="row">
    <div class="col-md-6">
      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Details</h3>
        </div>
        <div class="card-body">
          <?php
            if ($msg) {
          ?>
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i> Information!</h5>
            <?php
              echo $msg;
            ?>
          </div>
          <?php
            }

            if ($error) {
          ?>
          <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h5><i class="icon fas fa-ban"></i> Alert!</h5>
          <?php echo $error; ?>
          </div>
          <?php
            }
          ?>
          <form method="POST">
            <div class="form-group">
              <label>Name</label>
              <input type="text" name="name" class="form-control">
            </div>

            <div class="form-group">
              <label>Code</label>
              <input type="text" name="code" class="form-control">
            </div>

            <div class="form-group">
              <label>Description</label>
              <textarea class="form-control" name="description"></textarea>
            </div>

            <button type="submit" name="sbtSave" value="1" class="btn btn-success btn-block">Save</button>
          </form>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
  </div>

</section>
<!-- /.content -->