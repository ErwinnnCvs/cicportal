<?php
  function getCountSubmitting($se_type, $conn){

    $sql = $conn->query("SELECT fld_provcode FROM tbprodtickets WHERE SUBSTRING(fld_provcode, 1, 2) = '".$se_type."' GROUP BY fld_provcode");
    $result = mysqli_num_rows($sql);
    return $result;
  }

  function getNotSubmitting($se_type, $conn1, $conn2){
      
      $count = 0;
      $sql = $conn1->query("SELECT AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno))) as provcode FROM tbentities WHERE fld_type = '".$se_type."'");
      while($s=$sql->fetch_array()){
          $check_submission = $conn2->query("SELECT fld_provcode FROM tbentities WHERE fld_provcode = '".$s['provcode']."' GROUP BY fld_provcode");
          $cs=$check_submission->fetch_array();

          if(!$cs['fld_provcode']){
              $count += 1;
          } else {
              $count = 0;
          }
      }

      return $count;
  }
  

?>
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
    <div class="input-group-prepend">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                Prod Breakdown
            </button>
            <ul class="dropdown-menu">
                <li class="dropdown-item"><a href="main.php?nid=36&sid=0&rid=0">SE Participation</a></li>
                <li class="dropdown-item"><a href="main.php?nid=36&sid=1&rid=1">Summary</a></li>
                <li class="dropdown-item"><a href="main.php?nid=36&sid=1&rid=2">Prod Breakdown</a></li>
            </ul>
        </div>
    </div>
    <div class="card-body">
      <table class="table table-bordered">
          <thead>
              <tr>
                  <th></th>
                  <th style="text-align: right;">Credit Card Issuers</th>
                  <th style="text-align: right;">Universal & Commercial Banks</th>
                  <th style="text-align: right;">Thrift Banks</th>
                  <th style="text-align: right;">Rural Banks</th>
                  <th style="text-align: right;">Cooperatives</th>
                  <th style="text-align: right;">Cooperative Banks</th>
                  <th style="text-align: right;">Savings and Loans Assoc</th>
                  <th style="text-align: right;">Lending</th>
                  <th style="text-align: right;">Financing/Leasing</th>
                  <th style="text-align: right;">Micro-Finance Institutions</th>
                  <th style="text-align: right;">GOCCs</th>
                  <th style="text-align: right;">Insurance Comp</th>
                  <th style="text-align: right;">Trust Entity</th>
                  <th style="text-align: right;">Utilities/Services/Others</th>
                  <th style="text-align: right;">Total</th>
              </tr>
          </thead>
          <tbody>
              <tr>
                  <th>Submitting in Production</th>
                  <td style="text-align: right;"><?php echo getCountSubmitting('CC', $dbh1); ?></td>
                  <td style="text-align: right;"><?php echo getCountSubmitting('UB', $dbh1); ?></td>
                  <td style="text-align: right;"><?php echo getCountSubmitting('TB', $dbh1); ?></td>
                  <td style="text-align: right;"><?php echo getCountSubmitting('RB', $dbh1); ?></td>
                  <td style="text-align: right;"><?php echo getCountSubmitting('CO', $dbh1); ?></td>
                  <td style="text-align: right;"><?php echo getCountSubmitting('CB', $dbh1); ?></td>
                  <td style="text-align: right;"><?php echo getCountSubmitting('SLA', $dbh1); ?></td>
                  <td style="text-align: right;"><?php echo getCountSubmitting('PF', $dbh1); ?></td>
                  <td style="text-align: right;"><?php echo getCountSubmitting('LS', $dbh1); ?></td>
                  <td style="text-align: right;"><?php echo getCountSubmitting('MFI', $dbh1); ?></td>
                  <td style="text-align: right;"><?php echo getCountSubmitting('GF', $dbh1); ?></td>
                  <td style="text-align: right;"><?php echo getCountSubmitting('IS', $dbh1); ?></td>
                  <td style="text-align: right;"><?php echo getCountSubmitting('TE', $dbh1); ?></td>
                  <td style="text-align: right;"><?php echo getCountSubmitting('OT', $dbh1); ?></td>
                  <td style="text-align: right;"></td>
              </tr>
              <tr>
                  <th>Not Yet Submitting</th>
                  <td style="text-align: right;"><?php echo getNotSubmitting('CC', $dbh4, $dbh1); ?></td>
                  <td style="text-align: right;"><?php echo getNotSubmitting('UB', $dbh4, $dbh1); ?></td>
                  <td style="text-align: right;"><?php echo getNotSubmitting('TB', $dbh4, $dbh1); ?></td>
                  <td style="text-align: right;"><?php echo getNotSubmitting('RB', $dbh4, $dbh1); ?></td>
                  <td style="text-align: right;"><?php echo getNotSubmitting('CC', $dbh4, $dbh1); ?></td>
                  <td style="text-align: right;"><?php echo getNotSubmitting('CC', $dbh4, $dbh1); ?></td>
                  <td style="text-align: right;"><?php echo getNotSubmitting('CC', $dbh4, $dbh1); ?></td>
                  <td style="text-align: right;"><?php echo getNotSubmitting('CC', $dbh4, $dbh1); ?></td>
                  <td style="text-align: right;"><?php echo getNotSubmitting('CC', $dbh4, $dbh1); ?></td>
                  <td style="text-align: right;"><?php echo getNotSubmitting('CC', $dbh4, $dbh1); ?></td>
                  <td style="text-align: right;"><?php echo getNotSubmitting('CC', $dbh4, $dbh1); ?></td>
                  <td style="text-align: right;"><?php echo getNotSubmitting('CC', $dbh4, $dbh1); ?></td>
                  <td style="text-align: right;"><?php echo getNotSubmitting('CC', $dbh4, $dbh1); ?></td>
                  <td style="text-align: right;"><?php echo getNotSubmitting('CC', $dbh4, $dbh1); ?></td>
                  <td style="text-align: right;"></td>
              </tr>
          </tbody>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->