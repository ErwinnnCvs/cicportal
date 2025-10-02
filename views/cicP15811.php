<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
          
      <div class="input-group-prepend">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          Summary View
        </button>
        <ul class="dropdown-menu">
          <li class="dropdown-item"><a href="main.php?nid=158&sid=0&rid=0">Chart View</a></li>
          <li class="dropdown-item"><a href="main.php?nid=158&sid=1&rid=1">Summary View</a></li>
          <li class="dropdown-item"><a href="main.php?nid=158&sid=1&rid=2">Detailed View</a></li>
        </ul>
      </div>
    </div>
    <div class="card-body">
      
      <table class="table table-border">
        <thead>
          <tr>
            <th>Error Code</th>
            <th>Error Message</th>
            <th>Count</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $get_all_pto_logs = $dbh4->query("SELECT fld_startdate, fld_error_code, fld_error_message, count(fld_error_code) as cnt from tbcisauditloginquiries GROUP BY fld_error_code, fld_error_message ORDER BY count(fld_error_code) DESC;");
            while ($gapl=$get_all_pto_logs->fetch_array()) {
          ?>
          <tr>
            <td><?php echo $gapl['fld_error_code']; ?></td>
            <td><?php echo $gapl['fld_error_message']; ?></td>
            <td><?php echo $gapl['cnt']; ?></td>
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