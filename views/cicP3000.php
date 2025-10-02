<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">List of New Registered Entities</h3>
    </div>
    <div class="card-body">
      <table class="table table-bordered" id="newregistered_entities">
        <thead>
          <tr>
            <th>#</th>
            <th>Provider Code</th>
            <th>Company Name</th>
            <th>Type</th>
            <th>DateTime</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $get_all_registered_entities = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provcode, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, fld_type, fld_re_approval_ts FROM tbentities WHERE fld_re_approval_ts IS NOT NULL ORDER BY fld_re_approval_ts DESC");
            
            while ($gaee=$get_all_registered_entities->fetch_array()) {
              $c++;
          ?>
          <tr>
            <td><?php echo $c; ?></td>
            <td><?php echo $gaee['provcode']; ?></td>
            <td><?php echo $gaee['name']; ?></td>
            <td><?php echo $gaee['fld_type']; ?></td>
            <td><?php echo date("F d, Y h:ia", strtotime($gaee['fld_re_approval_ts'])); ?></td>
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