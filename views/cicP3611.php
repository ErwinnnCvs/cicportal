<?php
    #CC
    $credit_card_issuers_registered = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_batch_uat_creds_status = 1 and fld_type = 'CC'");
    $ccir=$credit_card_issuers_registered->fetch_array();

    $credit_card_issuers_testing = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_se_testing_status >= 1 and fld_type = 'CC'");
    $ccit=$credit_card_issuers_testing->fetch_array();

    $credit_card_issuers_validation = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_se_testing_status = 2 and fld_type = 'CC'");
    $cciv=$credit_card_issuers_validation->fetch_array();

    $credit_card_issuers_production = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_aeis >= 1 and fld_type = 'CC'");
    $ccip=$credit_card_issuers_production->fetch_array();

    $cctotal_participated = $ccir['no_se'] + $ccit['no_se'] + $cciv['no_se'] + $ccip['no_se'];

    #UB
    $ub_banks_registered = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_batch_uat_creds_status = 1 and fld_type = 'UB'");
    $ubbr=$ub_banks_registered->fetch_array();

    $ub_banks_testing = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_se_testing_status >= 1 and fld_type = 'UB'");
    $ubbt=$ub_banks_testing->fetch_array();

    $ub_banks_validation = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_se_testing_status = 2 and fld_type = 'UB'");
    $ubbv=$ub_banks_validation->fetch_array();

    $ub_banks_production = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_aeis >= 1 and fld_type = 'UB'");
    $ubbp=$ub_banks_production->fetch_array();

    $ubtotal_participated = $ubbr['no_se'] + $ubbt['no_se'] + $ubbv['no_se'] + $ubbp['no_se'];

    #TB
    $tb_banks_registered = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_batch_uat_creds_status = 1 and fld_type = 'TB'");
    $tbbr=$tb_banks_registered->fetch_array();

    $tb_banks_testing = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_se_testing_status >= 1 and fld_type = 'TB'");
    $tbbt=$tb_banks_testing->fetch_array();

    $tb_banks_validation = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_se_testing_status = 2 and fld_type = 'TB'");
    $tbbv=$tb_banks_validation->fetch_array();

    $tb_banks_production = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_aeis >= 1 and fld_type = 'TB'");
    $tbbp=$tb_banks_production->fetch_array();

    $tbtotal_participated = $tbbr['no_se'] + $tbbt['no_se'] + $tbbv['no_se'] + $tbbp['no_se'];

    #RB
    $rb_banks_registered = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_batch_uat_creds_status = 1 and fld_type = 'RB'");
    $rbbr=$rb_banks_registered->fetch_array();

    $rb_banks_testing = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_se_testing_status >= 1 and fld_type = 'RB'");
    $rbbt=$rb_banks_testing->fetch_array();

    $rb_banks_validation = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_se_testing_status = 2 and fld_type = 'RB'");
    $rbbv=$rb_banks_validation->fetch_array();

    $rb_banks_production = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_aeis >= 1 and fld_type = 'RB'");
    $rbbp=$rb_banks_production->fetch_array();

    $rbtotal_participated = $rbbr['no_se'] + $rbbt['no_se'] + $rbbv['no_se'] + $rbbp['no_se'];

    #CO
    $co_banks_registered = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_batch_uat_creds_status = 1 and fld_type = 'CO'");
    $cobr=$co_banks_registered->fetch_array();

    $co_banks_testing = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_se_testing_status >= 1 and fld_type = 'CO'");
    $cobt=$co_banks_testing->fetch_array();

    $co_banks_validation = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_se_testing_status = 2 and fld_type = 'CO'");
    $cobv=$co_banks_validation->fetch_array();

    $co_banks_production = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_aeis >= 1 and fld_type = 'CO'");
    $cobp=$co_banks_production->fetch_array();

    $cototal_participated = $cobr['no_se'] + $cobt['no_se'] + $cobv['no_se'] + $cobp['no_se'];

    #CB
    $cb_banks_registered = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_batch_uat_creds_status = 1 and fld_type = 'CB'");
    $cbbr=$cb_banks_registered->fetch_array();

    $cb_banks_testing = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_se_testing_status >= 1 and fld_type = 'CB'");
    $cbbt=$cb_banks_testing->fetch_array();

    $cb_banks_validation = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_se_testing_status = 2 and fld_type = 'CB'");
    $cbbv=$cb_banks_validation->fetch_array();

    $cb_banks_production = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_aeis >= 1 and fld_type = 'CB'");
    $cbbp=$cb_banks_production->fetch_array();

    $cbtotal_participated = $cbbr['no_se'] + $cbbt['no_se'] + $cbbv['no_se'] + $cbbp['no_se'];

    #SLA
    $sla_banks_registered = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_batch_uat_creds_status = 1 and fld_type = 'SLA'");
    $slabr=$sla_banks_registered->fetch_array();

    $sla_banks_testing = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_se_testing_status >= 1 and fld_type = 'SLA'");
    $slabt=$sla_banks_testing->fetch_array();

    $sla_banks_validation = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_se_testing_status = 2 and fld_type = 'SLA'");
    $slabv=$sla_banks_validation->fetch_array();

    $sla_banks_production = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_aeis >= 1 and fld_type = 'SLA'");
    $slabp=$sla_banks_production->fetch_array();

    $slatotal_participated = $slabr['no_se'] + $slabt['no_se'] + $slabv['no_se'] + $slabp['no_se'];

    #PF
    $pf_banks_registered = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE (fld_batch_uat_creds_status = 1 and fld_type = 'PF') or (fld_batch_uat_creds_status = 1 and fld_type = 'PLI')");
    $pfbr=$pf_banks_registered->fetch_array();

    $pf_banks_testing = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE (fld_se_testing_status >= 1 and fld_type = 'PF') or (fld_batch_uat_creds_status = 1 and fld_type = 'PLI')");
    $pfbt=$pf_banks_testing->fetch_array();

    $pf_banks_validation = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE (fld_se_testing_status = 2 and fld_type = 'PF') or (fld_batch_uat_creds_status = 1 and fld_type = 'PLI')");
    $pfbv=$pf_banks_validation->fetch_array();

    $pf_banks_production = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE (fld_aeis >= 1 and fld_type = 'PF') or (fld_batch_uat_creds_status = 1 and fld_type = 'PLI')");
    $pfbp=$pf_banks_production->fetch_array();

    $pftotal_participated = $pfbr['no_se'] + $pfbt['no_se'] + $pfbv['no_se'] + $pfbp['no_se'];

    #LS
    $ls_banks_registered = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_batch_uat_creds_status = 1 and fld_type = 'LS'");
    $lsbr=$ls_banks_registered->fetch_array();

    $ls_banks_testing = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_se_testing_status >= 1 and fld_type = 'LS'");
    $lsbt=$ls_banks_testing->fetch_array();

    $ls_banks_validation = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_se_testing_status = 2 and fld_type = 'LS'");
    $lsbv=$ls_banks_validation->fetch_array();

    $ls_banks_production = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_aeis >= 1 and fld_type = 'LS'");
    $lsbp=$ls_banks_production->fetch_array();

    $lstotal_participated = $lsbr['no_se'] + $lsbt['no_se'] + $lsbv['no_se'] + $lsbp['no_se'];

    #MFI
    $mfi_banks_registered = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_batch_uat_creds_status = 1 and fld_type = 'MFI'");
    $mfibr=$mfi_banks_registered->fetch_array();

    $mfi_banks_testing = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_se_testing_status >= 1 and fld_type = 'MFI'");
    $mfibt=$mfi_banks_testing->fetch_array();

    $mfi_banks_validation = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_se_testing_status = 2 and fld_type = 'MFI'");
    $mfibv=$mfi_banks_validation->fetch_array();

    $mfi_banks_production = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_aeis >= 1 and fld_type = 'MFI'");
    $mfibp=$mfi_banks_production->fetch_array();

    $mfitotal_participated = $mfibr['no_se'] + $mfibt['no_se'] + $mfibv['no_se'] + $mfibp['no_se'];

    #GOCC/GF
    $gf_banks_registered = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_batch_uat_creds_status = 1 and fld_type = 'GF'");
    $gfbr=$gf_banks_registered->fetch_array();

    $gf_banks_testing = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_se_testing_status >= 1 and fld_type = 'GF'");
    $gfbt=$gf_banks_testing->fetch_array();

    $gf_banks_validation = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_se_testing_status = 2 and fld_type = 'GF'");
    $gfbv=$gf_banks_validation->fetch_array();

    $gf_banks_production = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_aeis >= 1 and fld_type = 'GF'");
    $gfbp=$gf_banks_production->fetch_array();

    $gftotal_participated = $gfbr['no_se'] + $gfbt['no_se'] + $gfbv['no_se'] + $gfbp['no_se'];

    #IS
    $is_banks_registered = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_batch_uat_creds_status = 1 and fld_type = 'IS'");
    $isbr=$is_banks_registered->fetch_array();

    $is_banks_testing = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_se_testing_status >= 1 and fld_type = 'IS'");
    $isbt=$is_banks_testing->fetch_array();

    $is_banks_validation = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_se_testing_status = 2 and fld_type = 'IS'");
    $isbv=$is_banks_validation->fetch_array();

    $is_banks_production = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_aeis >= 1 and fld_type = 'IS'");
    $isbp=$is_banks_production->fetch_array();

    $istotal_participated = $isbr['no_se'] + $isbt['no_se'] + $isbv['no_se'] + $isbp['no_se'];


    #TE
    $te_banks_registered = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_batch_uat_creds_status = 1 and fld_type = 'TE'");
    $tebr=$te_banks_registered->fetch_array();

    $te_banks_testing = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_se_testing_status >= 1 and fld_type = 'TE'");
    $tebt=$te_banks_testing->fetch_array();

    $te_banks_validation = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_se_testing_status = 2 and fld_type = 'TE'");
    $tebv=$te_banks_validation->fetch_array();

    $te_banks_production = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_aeis >= 1 and fld_type = 'TE'");
    $tebp=$te_banks_production->fetch_array();

    $tetotal_participated = $tebr['no_se'] + $tebt['no_se'] + $tebv['no_se'] + $tebp['no_se'];

    #TE
    $ot_banks_registered = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_batch_uat_creds_status = 1 and fld_type = 'OT'");
    $otbr=$ot_banks_registered->fetch_array();

    $ot_banks_testing = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_se_testing_status >= 1 and fld_type = 'OT'");
    $otbt=$ot_banks_testing->fetch_array();

    $ot_banks_validation = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_se_testing_status = 2 and fld_type = 'OT'");
    $otbv=$ot_banks_validation->fetch_array();

    $ot_banks_production = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_aeis >= 1 and fld_type = 'OT'");
    $otbp=$ot_banks_production->fetch_array();

    $ottotal_participated = $otbr['no_se'] + $otbt['no_se'] + $otbv['no_se'] + $otbp['no_se'];
?>
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
    <div class="input-group-prepend">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                Summary
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
                  <th>Registration</th>
                  <td style="text-align: right;"><?php echo $ccir['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $ubbr['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $tbbr['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $rbbr['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $cobr['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $cbbr['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $slabr['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $pfbr['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $lsbr['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $mfibr['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $gfbr['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $isbr['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $tebr['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $otbr['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo number_format($ccir['no_se'] + $ubbr['no_se'] + $tbbr['no_se'] + $rbbr['no_se'] + $cobr['no_se'] + $cbbr['no_se'] + $slabr['no_se'] + $pfbr['no_se'] + 
                  $lsbr['no_se'] + $mfibr['no_se'] + $gfbr['no_se'] + $isbr['no_se'] + $tebr['no_se'] + $otbr['no_se']); ?></td>
              </tr>
              <tr>
                  <th>Testing</th>
                  <td style="text-align: right;"><?php echo $ccit['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $ubbt['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $tbbt['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $rbbt['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $cobt['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $cbbt['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $slabt['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $pfbt['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $lsbt['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $mfibt['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $gfbt['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $isbt['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $tebt['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $otbt['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo number_format($ccit['no_se'] + $ubbt['no_se'] + $tbbt['no_se'] + $rbbt['no_se'] + $cobt['no_se'] + $cbbt['no_se'] + $slabt['no_se'] + $pfbt['no_se'] + 
                  $lsbt['no_se'] + $mfibt['no_se'] + $gfbt['no_se'] + $isbt['no_se'] + $tebt['no_se'] + $otbt['no_se']); ?></td>
              </tr>
              <tr>
                  <th>Validation</th>
                  <td style="text-align: right;"><?php echo $cciv['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $ubbv['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $tbbv['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $rbbv['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $cobv['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $cbbv['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $slabv['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $pfbv['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $lsbv['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $mfibv['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $gfbv['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $isbv['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $tebv['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $otbv['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo number_format($cciv['no_se'] + $ubbv['no_se'] + $tbbv['no_se'] + $rbbv['no_se'] + $cobv['no_se'] + $cbbv['no_se'] + $slabv['no_se'] + $pfbv['no_se']
                  + $lsbv['no_se'] + $mfibv['no_se'] + $gfbv['no_se'] + $isbv['no_se'] + $tebv['no_se'] + $otbv['no_se']); ?></td>
              </tr>
              <tr>
                  <th>Production</th>
                  <td style="text-align: right;"><?php echo $ccip['no_se'] ?></td>
                  <td style="text-align: right;"><?php echo $ubbp['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $tbbp['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $rbbp['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $cobp['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $cbbp['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $slabp['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $pfbp['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $lsbp['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $mfibp['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $gfbp['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $isbp['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $tebp['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo $otbp['no_se']; ?></td>
                  <td style="text-align: right;"><?php echo number_format($ccip['no_se'] + $ubbp['no_se'] + $tbbp['no_se'] + $rbbp['no_se'] + $cobp['no_se'] + $cbbp['no_se'] + $slabp['no_se'] + $pfbp['no_se'] + $lsbp['no_se'] + $mfibp['no_se'] + $gfbp['no_se'] + $isbp['no_se'] + $tebp['no_se'] + $otbp['no_se']); ?></td>
              </tr>
              <tr >
                  <th>Total Paricipated</th>
                  <td style="text-align: right;"><?php echo $cctotal_participated; ?></td>
                  <td style="text-align: right;"><?php echo $ubtotal_participated; ?></td>
                  <td style="text-align: right;"><?php echo $tbtotal_participated; ?></td>
                  <td style="text-align: right;"><?php echo $rbtotal_participated; ?></td>
                  <td style="text-align: right;"><?php echo $cototal_participated; ?></td>
                  <td style="text-align: right;"><?php echo $cbtotal_participated; ?></td>
                  <td style="text-align: right;"><?php echo $slatotal_participated; ?></td>
                  <td style="text-align: right;"><?php echo $pftotal_participated; ?></td>
                  <td style="text-align: right;"><?php echo $lstotal_participated; ?></td>
                  <td style="text-align: right;"><?php echo $mfitotal_participated; ?></td>
                  <td style="text-align: right;"><?php echo $gftotal_participated; ?></td>
                  <td style="text-align: right;"><?php echo $istotal_participated; ?></td>
                  <td style="text-align: right;"><?php echo $tetotal_participated; ?></td>
                  <td style="text-align: right;"><?php echo $ottotal_participated; ?></td>
                  <td style="text-align: right;"><?php echo number_format($cctotal_participated + $ubtotal_participated + $tbtotal_participated + $rbtotal_participated + $cototal_participated + $cbtotal_participated + $slatotal_participated + $pftotal_participated + $lstotal_participated + $mfitotal_participated + $gftotal_participated + $istotal_participated + $tetotal_participated + $ottotal_participated) ?></td>
              </tr>
          </tbody>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->