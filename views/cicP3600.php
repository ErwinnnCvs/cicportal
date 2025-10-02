<?php
if(!$_POST['sel_type']){
    $_POST['sel_type'] = 'CC';
}

$selectedtype[$_POST['sel_type']] = " selected";
?>
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
        <div class="input-group-prepend">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                SE Participation
            </button>
            <ul class="dropdown-menu">
                <li class="dropdown-item"><a href="main.php?nid=36&sid=0&rid=0">SE Participation</a></li>
                <li class="dropdown-item"><a href="main.php?nid=36&sid=1&rid=1">Summary</a></li>
                <li class="dropdown-item"><a href="main.php?nid=36&sid=1&rid=2">Prod Breakdown</a></li>
            </ul>
        </div>

    </div>
    <div class="card-body">
        <center>    
                <img src="dist/img/separticipation.PNG">
        </center>
        <!-- <div class="row">
            <div class="col-md-4">
                <form method="post">
                    <div class="form-group">
                        <label>Select Type</label>
                        <select class="form-control select2" name="sel_type" id="sel_type" style="width: 100%;" onchange="submit()">
                            <?php
                                $get_all_se_types = $dbh4->query("SELECT SUBSTRING(AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))), 1, 2) AS types FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) <> '' GROUP BY types ORDER BY types");
                                while($gast=$get_all_se_types->fetch_array()){
                                    echo "<option value='".$gast['types']."'".$selectedtype[$gast['types']].">".$gast['types']." - ".$ent2[$gast['types']]."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </form>  
            </div>
        </div>

        <table class="table table-bordered" id="separticipation">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th>Submitting Entity</th>
                    <th></th>
                    <th>Registration</th>
                    <th>Testing</th>
                    <th>Validation</th>
                    <th>Production</th>
                    <th>Remarks</th>
                    <th>Certification Issued</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $get_submitting_entities = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, fld_batch_uat_creds_status, fld_sae_status, fld_se_testing_status, fld_aeis FROM tbentities WHERE fld_type = '".$_POST['sel_type']."' ORDER BY fld_aeis DESC");#AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019')))
                    while($gse=$get_submitting_entities->fetch_array()){
                        $getting_sep_aeis_noc_status = $dbh1->query("SELECT fld_aeis_noc_status FROM tbentities WHERE fld_ctrlno = '".$gse['fld_ctrlno']."'");
                        $gsans=$getting_sep_aeis_noc_status->fetch_array();
                        $c++;

                        if($gse['fld_sae_status'] == 1 or $gsans['fld_aeis_noc_status'] > 0){
                            $registration_status = "<center><b>x</b></center>";
                            $testing_status = "<center><b>x</b></center>";
                            $validation_status = "<center><b>x</b></center>";

                            $production_status = "<center><b>x</b></center>";
                            $circle_color = "green";
                            $remarks = "In production";
                        } else {
                            if($gse['fld_batch_uat_creds_status'] == 1 and $gse['fld_se_testing_status'] < 1){
                                $registration_status = "<center><b>x</b></center>";
                                $testing_status = "";
                                $validation_status = "";
                                $production_status = "";
                                $circle_color = "red";
                                $remarks = "Registered";
                            } elseif($gse['fld_batch_uat_creds_status'] == 1 and $gse['fld_se_testing_status'] == 1) {
                                $registration_status = "<center><b>x</b></center>";
                                $testing_status = "<center><b>x</b></center>";
                                $validation_status = "";
                                $production_status = "";
                                $circle_color = "yellow";
                                $remarks = "Testing";
                            } elseif($gse['fld_batch_uat_creds_status'] == 1 and $gse['fld_se_testing_status'] == 2) {
                                $registration_status = "<center><b>x</b></center>";
                                $testing_status = "<center><b>x</b></center>";
                                $validation_status = "<center><b>x</b></center>";
                                $production_status = "";
                                $circle_color = "yellow";
                                $remarks = "Validation";
                            } elseif(($gse['fld_batch_uat_creds_status'] == 1 and $gse['fld_se_testing_status'] == 3) or $gse['fld_sae_status'] == 1 or $gsans['fld_aeis_noc_status'] > 0) {
                                $registration_status = "<center><b>x</b></center>";
                                $testing_status = "<center><b>x</b></center>";
                                $validation_status = "<center><b>x</b></center>";
                                $production_status = "<center><b>x</b></center>";
                                $circle_color = "green";
                                $remarks = "In Production";
                            }
                        }
                        
                ?>
                <tr>
                    <td><?php echo $c; ?></td>
                    <td>
                        <center>
                            
                        <span style="width: 25px; height: 25px; background-color: <?php echo $circle_color; ?>; border-radius: 50%; display: inline-block;"></span>
                        </center>
                    </td>
                    <td><?php echo $gse['name']; ?></td>
                    <td></td>
                    <td>
                        <?php echo $registration_status; ?>
                    </td>
                    <td>
                        <?php echo $testing_status; ?>
                    </td>
                    <td>
                        <?php echo $validation_status; ?>
                    </td>
                    <td>
                        <?php echo $production_status; ?>
                    </td>
                    <td>
                        <?php echo $remarks; ?>
                    </td>
                    <td></td>
                </tr>
                <?php
                    }
                ?>
                
            </tbody>
        </table> -->
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->