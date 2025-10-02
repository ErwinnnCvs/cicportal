<?php
  
  if ($_POST['sbtReturn']) {
    $remarks = "<hr><small>".date('Y-m-d H:i:s')."</small><br><b style='text-color: red;'>".$_SESSION['name']."</b><br>".$_POST['subject_remarks']."<br>";
    $get_previous_remarks = $dbh2->query("SELECT fld_subjcode_remarks FROM contract WHERE fld_id = '".$_POST['fld_id']."'");
    $gpr=$get_previous_remarks->fetch_array();

    $new_remarks = addslashes($remarks.$gpr['fld_subjcode_remarks']);
    if ($dbh2->query("UPDATE contract SET fld_subjcode_remarks = '".$new_remarks."', fld_subjcode_by = '".$_SESSION['name']."', fld_disp_status = 0 and fld_dispute_verification_status = 0 WHERE fld_id = '".$_POST['fld_id']."'")) {
    }
  }
?>

<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <div class="input-group-prepend">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          Completed
        </button>
        <ul class="dropdown-menu">
          <li class="dropdown-item"><a href="main.php?nid=27&sid=0&rid=0">Pending</a></li>
          <li class="dropdown-item"><a href="main.php?nid=27&sid=1&rid=1">Resolve</a></li>
          <li class="dropdown-item"><a href="main.php?nid=27&sid=1&rid=2">Completed</a></li>
        </ul>
      </div>
    </div>
    <div class="card-body">
      <table class="table table-bordered" id="dispute_completed">
        <thead>
          <tr>
            <th width="15"><center>#</center></th>
            <th width="80">Date Filed</th>
            <th width="80">TRN</th>
            <th width="300">Name</th>
            <th width="100">Birth Date</th>
            <th width="200">Financial Institution</th>
            <th width="200">Remarks</th>
            <th width="150"><center>Action</center></th>
          </tr>
        </thead>
        <tbody>
          <?php
            $get_filed_disputes = $dbh2->query("SELECT fld_id, fld_TRN, fld_prov, fld_name, fld_status, fld_contractType, fld_complaint, fld_description, AES_DECRYPT(fld_subjcode, CONCAT(fld_id,'G3n13')) AS subjcode, fld_subjcode_remarks, fld_subjcode_ts FROM contract WHERE (fld_id > 51 and fld_id <= 9434) and fld_dispute_verification_status >= 1 and fld_disp_status = 2 or fld_dispute_verification_status = 0 and fld_disp_status = 1 ORDER BY fld_id DESC");
            while ($gfd = $get_filed_disputes->fetch_array()) {
              $c++;
              $subject = $dbh2->query("SELECT fld_TRN, AES_DECRYPT(fld_Fname, CONCAT(fld_Birthday,'G3n13')) AS firstname, fld_Birthday, AES_DECRYPT(fld_Mname, CONCAT(fld_Birthday,'G3n13')) AS middlename, AES_DECRYPT(fld_Lname, CONCAT(fld_Birthday,'G3n13')) AS lastname, AES_DECRYPT(fld_Contact, CONCAT(fld_Birthday,'G3n13')) AS contact, fld_DateFilled, changes, AES_DECRYPT(fld_SSS, CONCAT(fld_Birthday,'G3n13')) AS SSS, AES_DECRYPT(fld_GSIS, CONCAT(fld_Birthday,'G3n13')) AS GSIS, AES_DECRYPT(fld_TIN, CONCAT(fld_Birthday,'G3n13')) AS TIN, AES_DECRYPT(fld_UMID, CONCAT(fld_Birthday,'G3n13')) AS UMID, AES_DECRYPT(fld_DL, CONCAT(fld_Birthday,'G3n13')) AS DL, AES_DECRYPT(fld_subjcode, CONCAT(fld_Birthday,'G3n13')) AS subjcode FROM subject WHERE fld_TRN = '".$gfd['fld_TRN']."' ORDER BY subjcode ASC");
              $s=$subject->fetch_array();

              if ($gfd['fld_status'] == 4) {
                $name = $gfd['fld_name'];
              } else {
                $get_company_name = $dbh4->query("SELECT AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$gfd['fld_prov']."'");
                $gcn=$get_company_name->fetch_array();
                $name = $gcn['name'];
              }

          ?>
          <tr>           
            <td><center><?php echo $c; ?></center></td>
            <td><?php echo $s['fld_DateFilled']; ?></td>
            <td><?php echo $gfd['fld_TRN']; ?></td>
            <td><?php echo $s['firstname']. " " .$s['middlename']. " " .$s['lastname']; ?></td>
            <td><?php echo $s['fld_Birthday']; ?></td>
            <!-- <td><?php echo $s['contact']; ?></td> -->
            <td><?php echo $name; ?></td>
            <td>
              <?php
              echo $gfd['fld_subjcode_remarks'];
               // $remarks_block = explode("|", $gfd['fld_subjcode_remarks']);
               // // var_dump($remarks_block);
               // foreach ($remarks_block as $key) {
               //    // $remarks_pipe = explode("|", $key);
               //    echo $key;
                  
               //  } 
              ?>
            </td>
            <td>
              <center>
                <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#modal_enter_subjcode<?php echo $gfd['fld_id']; ?>">Return</button>
              </center>
              <div class="modal fade" id="modal_enter_subjcode<?php echo $gfd['fld_id']; ?>" style="display: none;">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title"><?php echo $gfd['fld_TRN']. " - ".$name ; ?></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    </div>
                    <form method="post">
                    <div class="modal-body">
                      <input type="hidden" name="fld_id" value="<?php echo $gfd['fld_id']; ?>">
                   <!--    <input type="hidden" name="subj_TRN" value="<?php echo $gfd['fld_TRN']; ?>">
                      <p>Provider Subject Number: <b><?php echo $gfd['fld_provsubj_number']; ?></b></p>
                      <p>Provider Contract Number: <b><?php echo $gfd['fld_provcontr_number']; ?></b></p>
                      <p>Dispute Details: <b><?php echo $gfd['fld_dispute_details']; ?></b></p>
                      <p>Filename(s): <b><?php echo $gfd['fld_filename']; ?></b></p>
                      <div class="form-group">
                        <label for="subject_code">Subject Code</label>
                        <input type="text" class="form-control" id="subject_code" name="subject_code" placeholder="Enter Subject Code" required>
                      </div>
 -->
                      <div class="form-group">
                        <label>Remarks</label>
                        <textarea class="form-control" rows="5" name="subject_remarks" placeholder="Enter details"></textarea>
                      </div>

                      
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                      <button type="submit" value="1" name="sbtReturn" class="btn btn-danger pull-left">Save</button>
                    </div>
                    </form>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
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