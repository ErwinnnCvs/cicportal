<?php 

$get_user_details = $dbh->query("SELECT * FROM tbcicusers WHERE pkUserId = '".$_GET['id']."'");
$gud=$get_user_details->fetch_array();

$current_position[$gud['is_admin']] = " selected";

?>

<section class="content">
  <div class="row">
    <div class="col-md-6">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">General</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label for="inputName">Name</label>
            <input type="text" id="inputName" class="form-control" value="<?php echo $gud['fld_name']; ?>">
          </div>
          <div class="form-group">
            <label for="inputStatus">User Type</label>
            <select id="inputStatus" class="form-control custom-select" name="user_type">
              <?php
                $k = 0;
                foreach ($user_position as $key) {
                  echo "<option value='".$k."'".$current_position[$k].">".$key."</option>";
                  $k++;
                }
              ?>
              
            </select>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="<?php echo $gud['email']; ?>">
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <div class="col-md-6">
      <div class="card card-info">
        <div class="card-header">
          <h3 class="card-title">Modules</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body p-0">
          <table class="table">
            <thead>
              <tr>
                <th>Module Name</th>
                <th>Description</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php
                $get_user_modules = $dbh->query("SELECT * FROM tbmenu WHERE fld_sid = 0");
                while ($gum=$get_user_modules->fetch_array()) {
                  $check_users = explode("|", $gum['fld_users']);
                if (in_array($_GET['id'], $check_users)) {
                  if ($gum['fld_published'] == 1) {
                    $bdgclr = "success";
                  } else {
                    $bdgclr = "danger";
                  }
              ?>
              <tr>
                <td><?php echo $gum['fld_title']; ?></td>
                <td><?php echo $gum['fld_description']; ?></td>
                <td class="text-right py-0 align-middle">
                  <div class="btn-group btn-group-sm">
                    <!-- <a href="#" class="btn btn-info"><i class="fas fa-eye"></i></a> -->
                    <a href="#" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                  </div>
                </td>
              </tr>
              <?php
                  }
                }
              ?>
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
  </div>
  <!-- <div class="row">
    <div class="col-12">
      <a href="#" class="btn btn-secondary">Cancel</a>
      <input type="submit" value="Save Changes" class="btn btn-success float-right">
    </div>
  </div> -->
</section>