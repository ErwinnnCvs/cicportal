<?php
    if($_POST['sbtAssign']){
      if($_POST['fininst'] && $_POST['assigntooperator']){
        if($update = $dbh4->query("UPDATE tbentities SET fld_assignedto = '".$_POST['assigntooperator']."' WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$_POST['fininst']."'")){
          $msg = "Record saved.";
        }
      }else{
        $err = "Incomplete details.";
      }
    }
    
    $osel[$_POST['selectoperator']] = " selected";
?>
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Assign Financial Institution</h3>
    </div>
    <div class="card-body">
      <form method="post">
        <div class="row">
          <?php if($err){ ?>
          <div class="col-lg-2"></div>
          <div class="col-lg-8">
            <div class="alert alert-danger alert-dismissible">
              <h4><i class="icon fa fa-ban"></i> Error : <?php echo $err; ?></h4>
            </div>
          </div>
          <div class="col-lg-2"></div>
          <?php } ?>
          <?php if($msg){ ?>
          <div class="col-lg-2"></div>
          <div class="col-lg-8">
            <div class="alert alert-success alert-dismissible">
              <h4><i class="icon fa fa-ban"></i> <?php echo $msg; ?></h4>
            </div>
          </div>
          <div class="col-lg-2"></div>
          <?php } ?>
          <div class="col-lg-3">
            <div class="form-group">
              <label>Operator</label>
              <select class="form-control" name="selectoperator" onchange="submit();">
                <option value="NULL">Select Operator</option>
              <?php
                if(!$_POST['selectoperator']){
                  $_POST['selectoperator'] = "NULL";
                }
                foreach($oprtrs as $okey => $oval){
                  echo "<option value='".$okey."'".$osel[$okey].">".$oval."</option>";
                }
              ?>
              </select>
            </div>
          </div>
          <div class="col-lg-5">
            <div class="form-group">
              <label>Select Financial Institution</label>
              <select id="fininst" class="form-control select2" name="fininst"><!-- multiple -->
              <?php
                if($_POST['selectoperator']){
                  if($_POST['selectoperator'] == "NULL"){#
                    $sql1=$dbh4->query("SELECT aes_decrypt(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_code, aes_decrypt(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_name FROM tbentities"); # WHERE fld_assignedto IS NULL
                  }else{
                    $sql1=$dbh4->query("SELECT aes_decrypt(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_code, aes_decrypt(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_name FROM tbentities WHERE fld_assignedto = '".$_POST['selectoperator']."'");
                  }
                  while($r=$sql1->fetch_array()){
                    echo "<option value='".$r['fld_code']."'>".$r['fld_code']." - ".$r['fld_name']."</option>";
                  }
                }
              ?>
              </select>
            </div>
          </div>
          <div class="col-lg-3">
            <div class="form-group">
              <label>Assign to Operator</label>
              <select class="form-control" name="assigntooperator">
                <option value="">Select Operator</option>
              <?php
                foreach($oprtrs as $okey => $oval){
                  echo "<option value='".$okey."'".$osel[$okey].">".$oval."</option>";
                }
              ?>
              </select>
            </div>
          </div>
          <div class="col-lg-1">
            <div class="form-group">
              <br/><input type="submit" class="btn btn-primary" name="sbtAssign" value="Save">
            </div>
          </div>
        </div>

      </form>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->