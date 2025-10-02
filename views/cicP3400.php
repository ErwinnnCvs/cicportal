<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-body">
      <label>Legend:</label><br>
          <button style="background-color: #17c0e6;"> &nbsp;</button> <span>Completed AEIS Process</span>
          <br><br>
      <table class="table table-bordered table-striped" id="list_of_ctnla">
        <thead>
          <tr>
            <th><center>#</center></th>
            <th>Company Name</th>
            <th><center>Individual</center></th>
            <th><center>Company</center></th>
            <th><center>Total Data Subjects</center></th>
            <th><center>Installments</center></th>
            <th><center>Non-Installments</center></th>
            <th><center>Credit Card</center></th>
            <th><center>Total Contracts</center></th>
          </tr>
        </thead>
        <tbody>
          <?php
            $counter = 1;
            $get_ctnla=$dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_name, md5(concat(fld_ctrlno, 'RA3019'))) as name, fld_numacct_indv, fld_numacct_comp, fld_numacct_inst, fld_numacct_noninst , fld_numacct_cc, fld_aeis FROM tbentities");
            while ($gctnla=$get_ctnla->fetch_array()) {
              $total_subjects = $gctnla['fld_numacct_indv']+$gctnla['fld_numacct_comp'];
              $total_contracts = $gctnla['fld_numacct_inst']+$gctnla['fld_numacct_noninst']+$gctnla['fld_numacct_cc'];
              if ($total_subjects > 0) {
                $txtcolor = "black";
                $bgcolor = '#6beb54';
              } else {
                $txtcolor = "black";
                $bgcolor = 'yellow';
              }

              if ($total_contracts > 0) {
                $ctxtcolor = "black";
                $cbgcolor = '#6beb54';
              } else {
                $ctxtcolor = "black";
                $cbgcolor = 'yellow';
              }

              // $get_accessing_entity = $dbh1->query("SELECT fld_aeis_process FROM tbentities WHERE fld_ctrlno = '".$gctnla['fld_ctrlno']."'");
              // $gae=$get_accessing_entity->fetch_array();

              // if ($gae['fld_aeis_process'] == 4) {
              //   $stylecolor = "#17c0e6";
              // } else {
              //   $stylecolor = "white";
              // }

          ?>
          <tr>
            <td><center><?php echo $counter; ?></center></td>
            <td><?php echo $gctnla['name']; ?></td>
            <td><center><?php echo number_format($gctnla['fld_numacct_indv']); ?></center></td>
            <td><center><?php echo number_format($gctnla['fld_numacct_comp']); ?></center></td>
            <td style="background-color: <?php echo $bgcolor; ?>; color: <?php echo $txtcolor; ?>;"><center><?php echo number_format($total_subjects); ?></center></td>
            <td><center><?php echo number_format($gctnla['fld_numacct_inst']); ?></center></td>
            <td><center><?php echo number_format($gctnla['fld_numacct_noninst']); ?></center></td>
            <td><center><?php echo number_format($gctnla['fld_numacct_cc']); ?></center></td>
            <td style="background-color: <?php echo $cbgcolor; ?>; color: <?php echo $ctxtcolor; ?>;"><center><?php echo number_format($gctnla['fld_numacct_inst']+$gctnla['fld_numacct_noninst']+$gctnla['fld_numacct_cc']); ?></center></td>
          </tr>
          <?php
              $counter++;
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