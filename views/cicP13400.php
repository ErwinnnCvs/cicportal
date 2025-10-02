<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">List</h3>
    </div>
    <div class="card-body">
      <a href="main.php?nid=134&sid=1&rid=0" class="btn btn-success"><i class="fa fa-plus"></i>Add Association</a>
      <br><br>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Code</th>
            <th>Description</th>
            <th><center>Action</center></th>
          </tr>
        </thead>
        <tbody>
          <?php
            $c = 1;
            $get_all_associations = $dbh4->query("SELECT * FROM tbseassociations");
            while ($gaa=$get_all_associations->fetch_array()) {
          ?>
          <tr>
            <td><?php echo $c++; ?></td>
            <td><?php echo $gaa['fld_name']; ?></td>
            <td><?php echo $gaa['fld_code'] ?></td>
            <td><?php echo $gaa['fld_descriptions']; ?></td>
            <td>
              <center>
              <a href="main.php?nid=134&sid=2&rid=0&code=<?php echo $gaa['fld_code']; ?>" class="btn btn-primary btn-block">View</a>
              </center>
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