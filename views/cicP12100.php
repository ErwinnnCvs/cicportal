<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);


$type = array(1=>"INSTALLMENT", 2=>"NON-INSTALLMENT", 3=>"CREDIT CARD");

$selectedenttype[$_POST['entitytype']] = ' selected';

$selectedcinstype[$_POST['cinstype']] = ' selected';

$selectedcontracttype[$_POST['contracttype']] = ' selected';

$selectedcontractphase[$_POST['contractphase']] = ' selected';



$contracs_array = [];

$get_last_month_extracted = $dbh->query("SELECT fld_year, fld_month FROM tbcontracts GROUP BY fld_year, fld_month ORDER BY fld_month DESC LIMIT 1");
$glme=$get_last_month_extracted->fetch_array();

if(!$_POST['dateselect']){
  $_POST['dateselect'] = $glme['fld_year']. "-" .$glme['fld_month'];
}

$selecteddate[$_POST['dateselect']] = ' selected';

?>
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">
        <form method="post">
        <select class="form-control select2" name="dateselect" id="dateselect" onchange="submit()" width="100">
            <option value="all" selected>All</option>
            <?php

              $get_all_months_extracted = $dbh->query("SELECT fld_year, fld_month FROM tbcontracts GROUP BY fld_year, fld_month");
              while($game=$get_all_months_extracted->fetch_array()){
                $date = $game['fld_year']. "-" .$game['fld_month'];
            ?>
            <option value="<?php echo $date; ?>"<?php echo $selecteddate[$date]; ?>><?php echo date("F Y", strtotime($date)); ?></option>
            <?php
              }
            ?>
          </select>
        </form>
        
      </h3>
    </div>
    <div class="card-body">

    <form method="POST">
    <div class="row">
      <div class="col-2">
        <div>
        <label for="entitytype">Entity Type</label>
        <select class="form-control select2" name="entitytype" id="entitytype" onchange="submit()">
          <option value="all" selected>All</option>
          <?php
            $entity_type = $dbh4->query("SELECT * FROM tbenttypes;");
            while($et=$entity_type->fetch_array()){
          ?>
          <option value="<?php echo $et['fld_type']; ?>"<?php echo $selectedenttype[$et['fld_type']]; ?>><?php echo $et['fld_name']; ?></option>
          <?php
            }
          ?>
        </select>
      </div>
      </div>
      <div class="col-2">
        <div>
        <label for="cinstype">Type</label>
          <select class="form-control" name="cinstype" id="cinstype" onchange="submit()">
            <option value="all" selected>All</option>
            <?php
              foreach($type as $t=>$d){
            ?>
            <option value="<?php echo $t; ?>"<?php echo $selectedcinstype[$t]; ?>><?php echo $d; ?></option>
            <?php
              }
            ?>
          </select>
        </div>
      </div>
      <div class="col-2">
        <div>
        <label for="contracttype">Contract Type</label>
          <select class="form-control select2" name="contracttype" id="contracttype" onchange="submit()">
            <option value="all" selected>All</option>
            <?php
            $get_contract_type = $dbh->query("SELECT * FROM tbcontracttype ORDER BY fld_text;");
            while($gct=$get_contract_type->fetch_array()){
            ?>
            <option value="<?php echo $gct['fld_code']; ?>"<?php echo $selectedcontracttype[$gct['fld_code']]; ?>><?php echo $gct['fld_text']; ?></option>
            <?php
              }
            ?>
          </select>
        </div>
      </div>

      <div class="col-2">
        <div>
        <label for="contractphase">Contract Phase</label>
          <select class="form-control" name="contractphase" id="contractphase" onchange="submit()">
            <option value="all" selected>All</option>
            <?php
            $get_contract_phase = $dbh->query("SELECT * FROM tbcontractphase ORDER BY fld_code;");
            while($gcp=$get_contract_phase->fetch_array()){
            ?>
            <option value="<?php echo $gcp['fld_code']; ?>"<?php echo $selectedcontractphase[$gcp['fld_code']]; ?>><?php echo $gcp['fld_text']; ?></option>
            <?php
              }
            ?>
          </select>
        </div>
      </div>
      <div class="col-2">
        <div>
        <br>
        <a href="main.php?nid=120&sid=1&rid=1" class="btn btn-secondary">Clear Filter</a>
        </div>
            
      </div>
    </div>
    <br>

    <div class="row">
      <div class=col-2>
        <label for="">Credit Limit / Financed Amount</label>
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label for="creditfinanced_min">Min</label>
              <input type="number" class="form-control" id="creditfinanced_min" name="creditfinanced_min" placeholder="Min" value="<?php echo $_POST['creditfinanced_min']; ?>" onchange="submit()">
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="creditfinanced_max">Max</label>
              <input type="number" class="form-control" id="creditfinanced_max" name="creditfinanced_max" placeholder="Max" value="<?php echo $_POST['creditfinanced_max']; ?>" onchange="submit()">
            </div>
          </div>
        </div>
      </div>

      <div class=col-2>
        <label for="">Loan Application Amount</label>
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label for="loanapplication_min">Min</label>
              <input type="number" class="form-control" id="loanapplication_min" name="loanapplication_min" placeholder="Min" value="<?php echo $_POST['loanapplication_min']; ?>" onchange="submit()">
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="loanapplication_max">Max</label>
              <input type="number" class="form-control" id="loanapplication_max" name="loanapplication_max" placeholder="Max" value="<?php echo $_POST['loanapplication_max']; ?>" onchange="submit()">
            </div>
          </div>
        </div>
      </div>
    </div>
    

    


    </form>
    
    
    <br><br>

    <button class="btn btn-success" id="extractContractsExcel">CSV</button>
    <table class="tbcontracts" class="table table-head-fixed text-nowrap" >
        <thead>
        <tr>
            <th style='text-align: left; width: 7%;'>MONTH YEAR</th>  
            <th style='text-align: left; width: 7%;'>Provider Code</th>
            <th style='text-align: left; width: 13%;'>Entity Type</th>
            <th style='text-align: left;'>Type</th>
            <th style='width: 10%;'>Contract Type</th>
            <th style='text-align: left; width: 10%;'>Contract Phase</th>
            <th style='text-align: right; width: 10%;'>Credit Limit / Financed Amount</th>
            <th style='text-align: right; width: 10%;'>Loan Application Amount</th>
            <th style='text-align: right;'>Count</th>
            <th style='text-align: right;'>Mean</th>
            <th style='text-align: right;'>Median</th>
        </tr>
        </thead>
        <tbody>
        <?php

          if($_POST['entitytype'] != "all" and $_POST['entitytype']){
            $entitytype_query = ' AND SUBSTRING(fld_provcode, 1, 2) = "'.$_POST['entitytype'].'"';
          } else {
            $entitytype_query = '';
          }

          if($_POST['cinstype'] != "all" and $_POST['cinstype']){
            $cinstype_query = ' AND fld_type = '.$_POST['cinstype'];
          } else {
            $cinstype_query = '';
          }

          if($_POST['contracttype'] != "all" and $_POST['contracttype']){
            $contracttype_query = ' AND fld_contract_type = '.$_POST['contracttype'];
          } else {
            $contracttype_query = '';
          }

          if($_POST['contractphase'] != "all" and $_POST['contractphase']){
            $contractphase_query = ' AND fld_contract_phase = "'.$_POST['contractphase'].'"';
          } else {
            $contractphase_query = '';
          }

          if($_POST['creditfinanced_min'] != '' and $_POST['creditfinanced_max'] != ''){
            $creditfinanced_min = ' AND fld_credit_limit >= '.$_POST['creditfinanced_min']. ' AND fld_credit_limit <= '.$_POST['creditfinanced_max'];
          } else {
            $creditfinanced_min = '';
          }

          if($_POST['loanapplication_min'] != '' and $_POST['loanapplication_max'] != ''){
            $loanapplication_filter = ' AND fld_loan_amount >= '.$_POST['loanapplication_min']. ' AND fld_loan_amount <= '.$_POST['loanapplication_max'];
          } else {
            $loanapplication_filter = '';
          }

          
          $query = $entitytype_query.$cinstype_query.$contracttype_query.$contractphase_query.$creditfinanced_min.$loanapplication_filter;//.$meanmedian_query
          
          if($_POST['dateselect'] == "all"){
            $date_query = '';
          } else {
            $splice_date = explode("-", $_POST['dateselect']);

            $date_query = ' fld_year = '.$splice_date[0].' and fld_month = '.$splice_date[1].' and ';
          }
        
          $contracts = $dbh->query("SELECT * FROM tbcontracts WHERE".$date_query." fld_provcode <> ''".$query." ORDER BY fld_provcode");
          while($c=$contracts->fetch_array()){
            $substring_str = substr($c['fld_provcode'],0, 2);
            $entity_type = $dbh4->query("SELECT fld_name FROM tbenttypes WHERE fld_type = '".$substring_str."' ");
            $et=$entity_type->fetch_array();

            $get_contract_type = $dbh->query("SELECT fld_text FROM tbcontracttype WHERE fld_code = ".$c['fld_contract_type'].";");
            $gct=$get_contract_type->fetch_array();

            if($gct['fld_text']){
              $contract_type = $gct['fld_text'];
            } else {
              $contract_type = "(".$c['fld_contract_type'].") NO VALUE";
            }
            
            $get_contract_phase = $dbh->query("SELECT fld_text FROM tbcontractphase WHERE fld_code = '".$c['fld_contract_phase']."';");
            $gcp=$get_contract_phase->fetch_array();

            $contracs_array[$c['fld_provcode']] = array(
              "MONTHYEAR"=>date("F Y", strtotime($c['fld_year']. "-" .$c['fld_month'])),"ENTITY_TYPE"=>$et['fld_name'], "TYPE"=>$type[$c['fld_type']], "CONTRACT_TYPE"=>$contract_type, "CONTRACT_PHASE"=>$gcp['fld_text'], "CREDIT_LIMIT"=>number_format($c['fld_credit_limit']), "LOAN_AMOUNT"=>number_format($c['fld_loan_amount']), "COUNT"=>number_format($c['fld_count']), "MEAN"=>number_format($c['fld_avg']), "MEDIAN"=>number_format($c['fld_median'])
            );


        ?>
        <tr>
            <td style='text-align: left;'><?php echo date("F Y", strtotime($c['fld_year']. "-" .$c['fld_month'])); ?></td>
            <td style='text-align: left;'><?php echo $c['fld_provcode']; ?></td>
            <td style='text-align: left;'><?php echo $et['fld_name']; ?></td>
            <td style='text-align: left;'><?php echo $type[$c['fld_type']]; ?></td>
            <td style='text-align: left;'><?php echo $contract_type; ?></td>
            <td style='text-align: left;'><?php echo $gcp['fld_text']; ?></td>
            <td style='text-align: right;'><?php echo number_format($c['fld_credit_limit']); ?></td>
            <td style='text-align: right;'><?php echo number_format($c['fld_loan_amount']); ?></td>
            <td style='text-align: right;'><?php echo number_format($c['fld_count']); ?></td>
            <td style='text-align: right;'><?php echo number_format($c['fld_avg']); ?></td>
            <td style='text-align: right;'><?php echo number_format($c['fld_median']); ?></td>
            
        </tr>
        <?php
          }
          
          // print_r($contracs_array);
        ?>
        <input type="hidden" name="contracts_array" id="contracts_array" value="<?php echo htmlspecialchars(json_encode($contracs_array)); ?>">
        </tbody>
    </table>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->