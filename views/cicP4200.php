<?php

if ($_POST['sbtSave']) {
  $date = date("Y-m-d H:i:s", strtotime($_POST['cins_date']));
  $individual = $_POST['individual'];
  $company = $_POST['company'];
  $three = $_POST['three'];
  $four = $_POST['four'];

  $credit_card = $_POST['credit-card'];
  $installment = $_POST['installment'];
  $non_installment = $_POST['non-installment'];
  $services = $_POST['services'];

  if (empty($date)) {
    $errno = 1;
    $msg = "Please input a Date.";
    $msgclr = "danger";
  }

  if (empty($individual) || $individual <= 0) {
    $errno = 1;
    $msg = "Please input a number in Individual greater than 0";
    $msgclr = "danger";
  }

  if (empty($company) || $company <= 0) {
    $errno = 1;
    $msg = "Please input a number in Company greater than 0";
    $msgclr = "danger";
  }

  if (empty($three) || $three <= 0) {
    $errno = 1;
    $msg = "Please input a number in Three greater than 0";
    $msgclr = "danger";
  }

  if (empty($four) || $four <= 0) {
    $errno = 1;
    $msg = "Please input a number in Four greater than 0";
    $msgclr = "danger";
  }

  if (empty($credit_card) || $credit_card <= 0) {
    $errno = 1;
    $msg = "Please input a number in Credit Card greater than 0";
    $msgclr = "danger";
  }

  if (empty($installment) || $installment <= 0) {
    $errno = 1;
    $msg = "Please input a number in Installment greater than 0";
    $msgclr = "danger";
  }

  if (empty($non_installment) || $non_installment <= 0) {
    $errno = 1;
    $msg = "Please input a number in Non-Installment greater than 0";
    $msgclr = "danger";
  }

  if (empty($services) || $services <= 0) {
    $errno = 1;
    $msg = "Please input a number in Services greater than 0";
    $msgclr = "danger";
  }

  if (!$errno) {
    $timestamp = date("Y-m-d H:i:s");
    if ($dbh->query("INSERT INTO tbcins (fld_date, fld_subject_individual, fld_subject_company, fld_subject_three, fld_subject_four, fld_contract_credit_card, fld_contract_installment, fld_contract_non_installment, fld_contract_services) VALUES ('".$date."', '".$individual."', '".$company."', '".$three."', '".$four."', '".$credit_card."', '".$installment."', '".$non_installment."', '".$services."');")) {
      $msg = "Successfuly saved.";
      $msgclr = "success";
    } else {
      $msg = "Error saving data.";
      $msgclr = "danger";
    }
  }
}

?>

<!-- Main content -->
<section class="content">
  <div class="card">
    <div class="card-header d-flex p-0">
      <h3 class="card-title p-3"></h3>
      <ul class="nav nav-pills ml-auto p-2">
        <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Insert Data</a></li>
        <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">List</a></li>
      </ul>
    </div><!-- /.card-header -->
    <div class="card-body">
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <?php
            if ($msg) {
          ?>
          <div class="alert alert-<?php echo $msgclr; ?> alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <?php echo $msg; ?>
          </div>
          <?php
            }
          ?>
          <form method="post">
              <div class="row">
                <div class="col-md-6"> 
                  <div class="form-group">
                    <label>Date and time:</label>
                      <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                          <input type="text" name="cins_date" class="form-control datetimepicker-input" data-target="#reservationdatetime"/>
                          <div class="input-group-append" data-target="#reservationdatetime" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                          </div>
                      </div>
                  </div>
                </div>
              </div>
            <br>  
            <div class="row">
              <div class="col-md-6">

                <!-- Default box -->
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Subjects</h3>
                  </div>
                  <div class="card-body">
                    <div class="form-group">
                      <label for="individual">1 Individual</label>
                      <input type="number" name="individual" class="form-control" id="individual" value="<?php echo $_POST['individual']; ?>" required>
                    </div>

                    <div class="form-group">
                      <label for="company">2 Company</label>
                      <input type="number" name="company" class="form-control" id="company" value="<?php echo $_POST['company']; ?>" required>
                    </div>

                    <div class="form-group">
                      <label for="three">3</label>
                      <input type="number" name="three" class="form-control" id="three" value="<?php echo $_POST['three']; ?>" required>
                    </div>

                    <div class="form-group">
                      <label for="four">4</label>
                      <input type="number" name="four" class="form-control" id="four" value="<?php echo $_POST['four']; ?>" required>
                    </div>


                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>

              <div class="col-md-6">
                <!-- Default box -->
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Contracts</h3>
                  </div>
                  <div class="card-body">
                    <div class="form-group">
                      <label for="credit-card">Credit Card</label>
                      <input type="number" name="credit-card" class="form-control" id="credit-card" value="<?php echo $_POST['credit-card']; ?>" required>
                    </div>

                    <div class="form-group">
                      <label for="installment">Installment</label>
                      <input type="number" name="installment" class="form-control" id="installment" value="<?php echo $_POST['installment']; ?>" required>
                    </div>

                    <div class="form-group">
                      <label for="non-installment">Non-Installment</label>
                      <input type="number" name="non-installment" class="form-control" id="non-installment" value="<?php echo $_POST['non-installment']; ?>" required>
                    </div>

                    <div class="form-group">
                      <label for="services">Services</label>
                      <input type="number" name="services" class="form-control" id="services" value="<?php echo $_POST['services']; ?>" required>
                    </div>


                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>
            </div>
            <a href="main.php?nid=42&sid=0&rid=0" class="btn btn-secondary btn-lg">Clear Fields</a>
            <button type="submit" name="sbtSave" class="btn btn-success btn-lg float-right" value="1">Save Data</button>
          </form>
        </div>
        <!-- /.tab-pane -->
        <div class="tab-pane" id="tab_2">
          <table class="table">
            <thead>
              <tr>
                <th>Date</th>
                <th style="text-align: right;">Individual</th>
                <th style="text-align: right;">Company</th>
                <th style="text-align: right;">Three</th>
                <th style="text-align: right;">Four</th>
                <th style="text-align: right;">Credit Card</th>
                <th style="text-align: right;">Installment</th>
                <th style="text-align: right;">Non-Installment</th>
                <th style="text-align: right;">Services</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $get_all_cins_data = $dbh->query("SELECT * FROM tbcins ORDER BY fld_date DESC");
                while ($gacd=$get_all_cins_data->fetch_array()) {
              ?>
              <tr>
                <td><?php echo $gacd['fld_date']; ?></td>
                <td style="text-align: right;"><?php echo $gacd['fld_subject_individual']; ?></td>
                <td style="text-align: right;"><?php echo $gacd['fld_subject_company']; ?></td>
                <td style="text-align: right;"><?php echo $gacd['fld_subject_three']; ?></td>
                <td style="text-align: right;"><?php echo $gacd['fld_subject_four']; ?></td>
                <td style="text-align: right;"><?php echo $gacd['fld_contract_credit_card']; ?></td>
                <td style="text-align: right;"><?php echo $gacd['fld_contract_installment']; ?></td>
                <td style="text-align: right;"><?php echo $gacd['fld_contract_non_installment']; ?></td>
                <td style="text-align: right;"><?php echo $gacd['fld_contract_services']; ?></td>
              </tr>
              <?php
                }
              ?>
            </tbody>
          </table>
        </div>
        <!-- /.tab-pane -->
      </div>
      <!-- /.tab-content -->
    </div><!-- /.card-body -->
  </div>
  <!-- ./card -->

</section>
<!-- /.content -->