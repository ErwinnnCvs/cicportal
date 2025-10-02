<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">List</h3>
    </div>
    <div class="card-body">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th><center>#</center></th>
            <th>Provider Code</th>
            <th>Company Name</th>
            <th>Type</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $get_all_tb_entities = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as company, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provcode, fld_type, fld_access_type, fld_aeis, fld_aeis_save_ts, fld_bill_status, fld_bill_emailsent, AES_DECRYPT(fld_bill_contact_fname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_bill_contact_fname, AES_DECRYPT(fld_bill_contact_lname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_bill_contact_lname, AES_DECRYPT(fld_bill_contact_mname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_bill_contact_mname, AES_DECRYPT(fld_bill_contact_sname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_bill_contact_sname, AES_DECRYPT(fld_bill_contact_email, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_bill_contact_email, AES_DECRYPT(fld_bill_email, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_bill_email, fld_bill_emailconfirmed, fld_aeis_com_status, fld_aeisform_ts, fld_aeismoa_ts, fld_webops_ts, fld_aeissec_ts, fld_aesae_ts, fld_aeissaecrif_ts, fld_aeissaetransu_ts,fld_moa_status, fld_sae_status, fld_sae_by, fld_sae_ts, fld_sae_reject_remarks, fld_sae_validation_ts, fld_sae_validation_by, fld_sae_validation_status, fld_sae_validation_crif_status, fld_sae_validation_crif_reject_ts, fld_sae_validation_crif_approve_ts, fld_sae_validation_crif_by, fld_dqua_cert_appr_ts, fld_dqua_report_appr_ts, fld_dqua_validation_exemption_appr_ts, fld_dqua_validation_status, fld_dqua_validation_rej_ts, fld_dqua_validation_appr_ts, fld_dqua_validation_by, fld_dqua_cred_status, fld_dqua_cred_by, fld_dqua_cred_ts, fld_dqua_cert_status, fld_dqua_cert_rej_ts, fld_dqua_cert_appr_ts, fld_dqua_cert_appr_by, fld_dqua_report_status, fld_dqua_report_rej_ts, fld_dqua_report_appr_ts, fld_dqua_report_by, fld_dqua_sae_selected, fld_dqua_sae_cert_status, fld_dqua_sae_cert_ts, fld_dqua_sae_report_status, fld_dqua_sae_report_ts, fld_dqua_validation_approval_status, fld_dqua_validation_approval_ts, fld_dqua_validation_approval_rej_ts, fld_dqua_validation_approval_by, fld_dqua_validation_approval_remarks, fld_accountno FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) LIKE 'TB%'");
            while ($gatbe=$get_all_tb_entities->fetch_array()) {
              $c++;
              $statuses = $dbh->query("SELECT * FROM tbentities WHERE fld_ctrlno = '".$gatbe['fld_ctrlno']."'");
              $rowstatus = $statuses->fetch_array();

              $sqlpayment = $dbh->query("SELECT * FROM tbbillingpayment WHERE fld_acct_no = '".$gatbe['fld_accountno']."'");
              $sp=$sqlpayment->fetch_array();

              $check_moa_signed = $dbh->query("SELECT fld_moa_status, fld_aeis_process FROM tbentities WHERE fld_ctrlno = '".$gatbe['fld_ctrlno']."'");
              $cms=$check_moa_signed->fetch_array();

              $status = "N/A";

              if ($gatbe['fld_access_type'] == 0) {
                $status = "Pending AEIS Registration";
              } elseif ($gatbe['fld_access_type'] == 1 and $gatbe['fld_aeis'] == 1) {
                if ($gatbe['fld_bill_status'] == 0) {
                  $status = "Pending BCPP Confirmation";
                } elseif ($gatbe['fld_bill_status'] == 1) {
                  $status = "Pending BCPP Confirmation";
                } elseif ($gatbe['fld_bill_status'] >= 2) {
                  if ($gatbe['fld_aeis_com_status'] < 2) {
                    $status = "Pending: Sending of AEIS Attachments";
                  } elseif ($gatbe['fld_aeis_com_status'] >= 2) {
                    if (!$gatbe['fld_aeisform_ts'] or !$gatbe['fld_webops_ts'] or !$gatbe['fld_aeissec_ts'] or !$gatbe['fld_aeismoa_ts']) {
                      $status = "Pending Uploading of AEIS Document(s)";
                    } elseif ($gatbe['fld_aeisform_ts'] and $gatbe['fld_webops_ts'] and $gatbe['fld_aeissec_ts'] and $gatbe['fld_aeismoa_ts']) {
                      if ($gatbe['fld_aeis_com_status'] <= 2) {
                        $status = "Pending AEIS Documents - Validation";
                      } elseif ($gatbe['fld_aeis_com_status'] > 2) {
                        if (!$sp['fld_acct_no']) {
                          $status = "Pending Advance Payment";
                        } elseif ($sp['fld_acct_no']) {
                          if ($rowstatus['fld_moa_status'] == 0) {
                            $status = "Pending Signed MOA Approval";
                          } elseif ($rowstatus['fld_moa_status'] > 0) {
                            if ($rowstatus['fld_aeis_process'] !=4) {
                              $status = "Pending NOC - Web Access Credentials Generation";
                            } elseif ($rowstatus['fld_aeis_process'] == 4) {
                              $status = "Competed AEIS Process";
                            }
                          }
                        }
                      }
                    }
                  }
                }
              } 

              // if ($gatbe['fld_aeis'] > 0 and $gatbe['fld_bill_status'] == 0) {
              //   $status = "Pending BCPP Email Confirmation";
              // }

              // if ($gatbe['fld_aeis'] > 0 and $gatbe['fld_bill_status'] > 0 and $gatbe['fld_aeis_com_status'] < 2) {
              //   $status = "Pending sending of AEIS Attachment";
              // }

              // if ($gatbe['fld_aeis'] > 0 and $gatbe['fld_aeis_com_status'] <= 2) {
              //   $status = "Pending AEIS Validation (BDC)";
              // }

              // if ($gatbe['fld_aeis'] > 0 and !$sp['fld_acct_no']) {
              //   $status = "Pending AEIS Initial Payment";
              // }

              // if ($gatbe['fld_aeis'] > 0 and $rowstatus['fld_moa_status'] == 0) {
              //   $status = "Pending Signed MOA Approval";
              // }

              // if ($gatbe['fld_aeis'] > 0 and $rowstatus['fld_aeis_process'] == 4) {
              //   $status = "Pending NOC Generation of Web Access";
              // }
          ?>
          <tr>
            <td><center><?php echo $c; ?></center></td>
            <td><?php echo $gatbe['provcode']; ?></td>
            <td><?php echo $gatbe['company'] ?></td>
            <td><?php echo $gatbe['fld_type']; ?></td>
            <td><?php echo $status; ?></td>
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