<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

if ($_POST['sbtSaveRemarks']) {
  $remarks = addslashes($_POST['txtarearemarks']);

  if($dbh4->query("INSERT INTO tbcmsremarks (fld_remarks, fld_remarks_by, fld_userid, fld_provcode) VALUES ('".$remarks."', '".$_SESSION['name']."', '".$_SESSION['user_id']."', '".$_GET['provcode']."')")){
    $msg="Successfully saved remarks";
    $msgclr = "success";
  } else {
    $msg="Error saving remarks";
    $msgclr = "danger";
  }


}

if (in_array($_SESSION['user_id'], $tech_team)) {
  $type = 1;
} elseif (in_array($_SESSION['user_id'], $legal_team)) {
  $type = 2;
}

if ($_SESSION['user_id'] != 76 and $_SESSION['user_id'] != 197) {
  $globalview = ' and b.fld_assign = '.$_SESSION['user_id'];
  $typeglobal = "and fld_type = ".$type;
  
} else {
  $globalview = '';
  $typeglobal = '';
}

$get_se_details = $dbh4->query("SELECT a.fld_ctrlno, a.fld_type, AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) as provcode, AES_DECRYPT(a.fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as company, a.fld_type, a.fld_numacct_indv, a.fld_numacct_comp, a.fld_numacct_inst, a.fld_numacct_noninst, a.fld_numacct_cc, a.fld_numacct_util, AES_DECRYPT(a.fld_ip, MD5(CONCAT(a.fld_ctrlno, 'RA3019'))) as ip FROM tbentities a RIGHT JOIN tbassign b ON AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) = b.fld_provcode WHERE AES_DECRYPT(a.fld_provcode, md5(CONCAT(a.fld_ctrlno, 'RA3019'))) = '".$_GET['provcode']."'".$globalview);
$gsd=$get_se_details->fetch_array();


$get_loaded = $dbh4->query("SELECT CODPROVIDERCODE, SUM(NUMCPSRECORDSINSERTEDNUMBER + NUMCPSRECORDSUPDATEDNUMBER) as subject, SUM(NUMCPCRECORDSINSERTEDNUMBER + NUMCPCRECORDSUPDATEDNUMBER) as contract FROM tbsubmissiondata WHERE CODPROVIDERCODE = '".$gsd['provcode']."' AND YEAR(DATFILEREFERENCEDATE) = ".date("Y")." GROUP BY CODPROVIDERCODE, DATE_FORMAT(DATFILEREFERENCEDATE, '%Y-%m') ORDER BY DATFILEREFERENCEDATE DESC");

if (mysqli_num_rows($get_loaded) > 0) {
  $gl=$get_loaded->fetch_array();
}

$ranking_subject = array();


$get_loaded_subject_rank = $dbh4->query("SELECT CODPROVIDERCODE, SUM(NUMCPSRECORDSINSERTEDNUMBER + NUMCPSRECORDSUPDATEDNUMBER) as subject FROM tbsubmissiondata WHERE YEAR(DATFILEREFERENCEDATE) = ".date("Y")." GROUP BY CODPROVIDERCODE, DATE_FORMAT(DATFILEREFERENCEDATE, '%Y-%m') ORDER BY SUM(NUMCPSRECORDSINSERTEDNUMBER + NUMCPSRECORDSUPDATEDNUMBER) DESC");

if (mysqli_num_rows($get_loaded_subject_rank) > 0) {
  while($glsr=$get_loaded_subject_rank->fetch_array()){
    // echo $glsr['CODPROVIDERCODE']. " ".$glsr['subject']." "."<br>";
    $ranking_subject[$glsr['CODPROVIDERCODE']] = $glsr['subject'];
  }
}

// print_r($ranking_subject);

$ranking_contract= array();
$get_loaded_contract_rank = $dbh4->query("SELECT CODPROVIDERCODE, SUM(NUMCPCRECORDSINSERTEDNUMBER + NUMCPCRECORDSUPDATEDNUMBER) as contract FROM tbsubmissiondata WHERE YEAR(DATFILEREFERENCEDATE) = ".date("Y")." GROUP BY CODPROVIDERCODE, DATE_FORMAT(DATFILEREFERENCEDATE, '%Y-%m') ORDER BY SUM(NUMCPCRECORDSINSERTEDNUMBER + NUMCPCRECORDSUPDATEDNUMBER) DESC");

if (mysqli_num_rows($get_loaded_contract_rank) > 0) {
  while($glcr=$get_loaded_contract_rank->fetch_array()){
    // echo $glcr['CODPROVIDERCODE']. " ".$glcr['contract']." "."<br>";
    $ranking_contract[$glcr['CODPROVIDERCODE']] = $glcr['subject'];
  }
}

if ($gsd['fld_ctrlno']) {

$check_loc_logs = $dbh4->query("SELECT fld_created_at FROM tbloclogs WHERE fld_provcode = '".$gsd['provcode']."'");
$cll=$check_loc_logs->fetch_array();

if (empty($cll['fld_created_at'])) {
  $last_sent_logs = 0;
} else {
  $last_sent_logs = $cll['fld_created_at'];
}


?>

<!-- Main content -->
<section class="content">

  <div class="container-fluid">
    <?php
      if ($msg) {
    ?>
    <div class="alert alert-<?php echo $msgclr; ?> alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <h5><i class="icon fas fa-check"></i> Alert!</h5>
      <?php echo $msg; ?>
    </div>
    <?php
      }
    ?>
    <div class="row">
      <div class="col-md-3">

        <!-- Profile Image -->
        <div class="card card-primary card-outline">
          <div class="card-body box-profile">
            <div class="text-center">
              <img class="profile-user-img img-fluid img-circle"
                   src="dist/img/cic-logo.png"
                   alt="User profile picture">
            </div>

            <h3 class="profile-username text-center"><?php echo $gsd['company']; ?></h3>

            <p class="text-muted text-center"><?php echo $gsd['provcode']; ?></b><br/><?php echo $ent2[$gsd['fld_type']]; ?></p>

            <!-- <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <b>Individual</b> <a class="float-right"><?php echo $gsd['fld_numacct_indv']; ?></a>
              </li>
              <li class="list-group-item">
                <b>Corporate</b> <a class="float-right"><?php echo $gsd['fld_numacct_comp']; ?></a>
              </li>
              <li class="list-group-item">
                <b>Installment</b> <a class="float-right"><?php echo $gsd['fld_numacct_inst']; ?></a>
              </li>
              <li class="list-group-item">
                <b>Non-Installment</b> <a class="float-right"><?php echo $gsd['fld_numacct_noninst']; ?></a>
              </li>
              <li class="list-group-item">
                <b>Credit Card</b> <a class="float-right"><?php echo $gsd['fld_numacct_cc']; ?></a>
              </li>
              <li class="list-group-item">
                <b>Utilities</b> <a class="float-right"><?php echo $gsd['fld_numacct_util']; ?></a>
              </li>
            </ul> -->
            <p>Connectivity: 
              <?php
               if ($gsd['ip'] == 'will avail 2fa') {
                 echo "Two Factor Authentication";
               } else {
                 echo $gsd['ip'];
               }
              ?>    
            </p>
            <?php
                if ($last_sent_logs != 0) {
            ?>
            <p><b>LOC Last Sent: <?php echo date("d M Y", strtotime($last_sent_logs)); ?></b></p>
            <?php
                } else {
            ?>
            <p>LOC Last Sent: N/A</p>
            <?php
              }
            ?>
            <!-- <b type="submit" class="btn btn-primary btn-block"><b>Send LOC</b></b> -->
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <!-- <div class="card">
          <div class="card-header border-0">

            <h3 class="card-title">
              <i class="far fa-calendar-alt"></i>
              Calendar
            </h3>
            <div class="card-tools">
              <div class="btn-group">
                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-52">
                  <i class="fas fa-bars"></i>
                </button>
                <div class="dropdown-menu" role="menu">
                  <a href="#" class="dropdown-item">Add new event</a>
                </div>
              </div>
              <button type="button" class="btn btn-success btn-sm" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-success btn-sm" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <div class="card-body pt-0">
            <div id="calendar" style="width: 100%"></div>
          </div>
        </div> -->
        <!-- /.card -->
      </div>
      <!-- /.col -->

      <div class="col-md-9">
        <div class="card">
        <div class="card-header">
          <h3 class="card-title">Detail</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <input type="hidden" name="getProvCode" id="getProvCode" value="<?php echo $_GET['provcode']; ?>">
        <div class="card-body">
          <div class="row">
            <div class="col-12 col-md-12 col-lg-12 order-2 order-md-1">
              <div class="row">
                <div class="col-12 col-sm-2">
                  <div class="info-box bg-light">
                    <div class="info-box-content">
                      <span class="info-box-text text-center text-muted">Loaded (Subject)</span>
                      <span class="info-box-number text-center text-muted mb-0"><?php echo number_format($gl['subject']); ?></span>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-sm-2">
                  <div class="info-box bg-light">
                    <div class="info-box-content">
                      <span class="info-box-text text-center text-muted">Rank (Subject)</span>
                      <span class="info-box-number text-center text-muted mb-0">
                        <?php
                          $isr = array_search($_GET['provcode'], array_keys($ranking_subject));

                          if (empty($isr)) {
                            
                            echo "No rank";
                          } elseif ($isr == 0) {
                            echo 1;
                          } else {
                            $total_rank = $isr;
                            $count_all_ranks = count($ranking_subject);
                            // echo $total_rank."/".$count_all_ranks;

                            $divide_rank = $total_rank / $count_all_ranks;
                            $percen = $divide_rank * 100;

                            echo round($percen)."%";
                          }
                        ?>
                          
                      </span>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-sm-2">
                  <div class="info-box bg-light">
                    <div class="info-box-content">
                      <span class="info-box-text text-center text-muted">Loaded (Contract)</span>
                      <span class="info-box-number text-center text-muted mb-0"><?php echo number_format($gl['contract']); ?></span>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-sm-2">
                  <div class="info-box bg-light">
                    <div class="info-box-content">
                      <span class="info-box-text text-center text-muted">Rank (Contract)</span>
                      <span class="info-box-number text-center text-muted mb-0">
                        <?php 
                          $isrc = array_search($_GET['provcode'], array_keys($ranking_contract));

                          if (empty($isrc)) {
                            
                            echo "No rank";
                          } elseif ($isrc == 0) {
                            echo 1;
                          } else {
                            $total_rankc = $isrc;
                            $count_all_ranksc = count($ranking_contract);
                            // echo $total_rankc."/".$count_all_ranksc;

                            $divide_rankc = $total_rankc / $count_all_ranksc;
                            $percenc = $divide_rankc * 100;

                            echo round($percenc)."%";
                          } 
                        ?>
                          
                      </span>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-sm-2">
                  <div class="info-box bg-light">
                    <div class="info-box-content">
                      <span class="info-box-text text-center text-muted">Unresolved Ticket(s)</span>
                      <span class="info-box-number text-center text-muted mb-0">
                        <?php 

                          $get_unresolved_ticket = $dbh->query("SELECT COUNT(*) as unre_tix FROM tbfreshdesk WHERE fld_provcode = '".$gsd['provcode']."' ".$typeglobal." AND fld_status <> 4 AND fld_status <> 5;");
                          $gut=$get_unresolved_ticket->fetch_array();

                          echo $gut['unre_tix'];
                        ?>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                        <div class="card">
                    <div class="card-header border-0">
                      <h3 class="card-title">Latest Added Tickets</h3>
                      <div class="card-tools">
                        <!-- <a href="#" class="btn btn-tool btn-sm">
                          <i class="fas fa-download"></i>
                        </a> -->
                        <a href="main.php?nid=137&sid=1&rid=2&provcode=<?php echo $gsd['provcode']; ?>" class="btn btn-tool btn-sm">
                          See All
                        </a>
                      </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                      <table class="table table-striped table-valign-middle">
                        <thead>
                        <tr>
                          <th width="50%">Subject</th>
                          <th width="18%"><center>Status</center></th>
                          <th width="18%"><center>DateTime</center></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                          $get_all_freshdesk_tickets = $dbh->query("SELECT * FROM tbfreshdesk WHERE fld_provcode = '".$gsd['provcode']."' ".$typeglobal." ORDER BY fld_created_at DESC LIMIT 10");
                          if (mysqli_num_rows($get_all_freshdesk_tickets) > 0) {
                          while ($gaft=$get_all_freshdesk_tickets->fetch_array()) {
                        ?>
                        <tr>
                          <td>
                            <!-- <img src="dist/img/default-150x150.png" alt="Product 1" class="img-circle img-size-32 mr-2"> -->
                            <a href="main.php?nid=139&sid=1&rid=0&ticket=<?php echo $gaft['fld_ticket_id']; ?>">
                              <?php echo $gaft['fld_subject']; ?>  
                            </a>
                          </td>
                          <td><center><?php echo $cmstatus[$gaft['fld_status']]; ?></center></td>
                          <td>
                            <center>
                            <?php echo date("M d, Y h:i A", strtotime($gaft['fld_created_at'])); ?>
                              
                            </center>
                          </td>
                        </tr>
                        <?php
                            }
                          } else {
                        ?>
                        <tr>
                          <td colspan="3"><center>No Data</center></td>
                        </tr>
                        <?php
                          }
                        ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-12">
                        <div class="card">
                    <div class="card-header border-0">
                      <h3 class="card-title">Latest Submissions</h3>
                      <div class="card-tools">
                        <!-- <a href="#" class="btn btn-tool btn-sm">
                          <i class="fas fa-download"></i>
                        </a>
                        <a href="#" class="btn btn-tool btn-sm">
                          <i class="fas fa-bars"></i>
                        </a> -->
                      </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                      <table class="table table-striped table-valign-middle">
                        <thead>
                        <tr>
                          <th width="50%">Filename</th>
                          <th width="18%"><center>Arrival</center></th>
                          <th width="18%"><center>Transmittal</center></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                          $get_submissions = $dbh->query("SELECT * FROM tbprodtickets WHERE fld_provcode = '".$gsd['provcode']."' and fld_subject LIKE '%CSDF%' and fld_subject NOT LIKE '%File Discarded%' ORDER BY fld_created_time DESC LIMIT 5");
                          while ($gs=$get_submissions->fetch_array()) {
                            $subj_expl = explode(":", $gs['fld_subject']);
                            $file = str_split($subj_expl[1], 36);

                            $filename = $file[1].".TXT";

                            $check_if_have_transmittal = $dbh4->query("SELECT fld_filed_date_ts FROM tbtransmittal WHERE fld_provcode = '".$gsd['provcode']."' and fld_filename = '".$filename."'");
                            $ciht=$check_if_have_transmittal->fetch_array();
                        ?>
                        <tr>
                          <td>
                            <!-- <img src="dist/img/default-150x150.png" alt="Product 1" class="img-circle img-size-32 mr-2"> -->
                            <?php echo $filename; ?>
                          </td>
                          <td>
                            <center>
                            <?php echo date("M d, Y h:i A", strtotime($gs['fld_created_time'])); ?>
                              
                            </center>
                          </td>
                          <td>
                            <center>
                              <?php
                                if ($ciht['fld_filed_date_ts']) {
                                  echo '<span class="badge bg-success"><i class="nav-icon fas fa-check"></i></span>';
                                } else {
                                  echo '<span class="badge bg-danger"><i class="nav-icon fas fa-times"></i></span>';
                                }
                              ?>
                            </center>
                          </td>
                        </tr>
                        <?php
                          }
                        ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

            </div>
            
          </div>

          
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>

    <div class="row">
      
      <div class="col-md-6">
        <div class="card" style="overflow-y: scroll; height: 500px;">
          <div class="card-header">
            <h3 class="card-title">Latest Activity</h3>
          </div>
          <div class="card-body p-0">
            <ul class="products-list product-list-in-card pl-2 pr-2">
              <?php
                $get_audit_logs = $dbh4->query("SELECT fld_form, fld_user, AES_DECRYPT(fld_data, MD5(CONCAT(fld_ctrlno, 'RA3019'))) as data, fld_ts FROM tbupdatesaudit WHERE fld_ctrlno = ".$gsd['fld_ctrlno']. " ORDER BY fld_ts DESC");
                while($gal=$get_audit_logs->fetch_array()){
                  $get_user = $dbh->query("SELECT email, is_admin FROM tbusers WHERE pkUserId = ".$gal['fld_user']);
                  $gu=$get_user->fetch_array();

                  // echo $gal['fld_form']."-".$gal['data']."-".$gal['fld_ts']."<br>";
              ?>
              <li class="item">
                <div class="product-img">
                  <img src="dist/img/cic-logo.png" alt="Product Image" class="img-size-50">
                </div>
                <div class="product-info">
                  <p href="javascript:void(0)" class="product-title"><?php echo $gu['email']. " | " .($gu['is_admin'] == 3 ? 'Primary Contact Person' : ($gu['is_admin'] == 4 ? 'Web Operator' : '')); ?>
                    <span class="badge badge-secondary float-right"><?php echo date("d M Y h:i A", strtotime($gal['fld_ts'])); ?></span></p>
                  <span>
                    <?php echo ($gal['fld_form'] == 'DQUAREQEXEMP' ? "Requested for DQUA Exemption" : ($gal['fld_form'] == 'PSAE' ? "Registered SAE" : ($gal['fld_form'] == 'PWOFA' ? "Registered a New Web Operator" : ($gal['fld_form'] == 'PBCPPN' ? "Registered a BCPP" : ($gal['fld_form'] == 'PBCPPU' ? "Updated BCPP Details" : ($gal['fld_form'] == 'PAASAEDU' ? "Removed an SAE" : ($gal['fld_form'] == 'SAEPUDOC' ? "Uploaded SAE Document" : (substr($gal['fld_form'], 0, 5) == 'PBOFD' ? "Removed BCPP" : ($gal['fld_form'] == 'PBOFA' ? "Added BCPP" : $gal['fld_form'] ) ) ) ) ) ) ) ) ); ?>
                  </span>
                </div>
              </li>
              <?php
                }
              ?>
            </ul>
            
          </div>
        </div>  
      </div>

      <div class="col-md-6">
        <div class="card card-primary card-outline" style="overflow-y: scroll; height: 500px;">
          <div class="card-header">
            <h3 class="card-title">Remarks</h3>
            <div class="card-tools">
            <button type="button" class="btn btn-tool" title="Contacts" data-toggle="modal" data-target="#modal-default">
            <i class="fas fa-plus"></i> Add Remarks
            </button>
            </div>
          </div>
          <div class="card-body box-profile">
            <?php
              $get_all_remarks = $dbh4->query("SELECT * FROM tbcmsremarks WHERE fld_userid = ".$_SESSION['user_id']. " and fld_provcode = '".$_GET['provcode']."' ORDER BY fld_ts DESC");
              while ($gar=$get_all_remarks->fetch_array()) {
            ?>
            <div class="card-footer card-comments">
              <div class="card-comment">
                <!-- User image -->
                <!-- dist/img/user6-128x128.jpg -->
                <img class="img-circle img-sm" src="dist/img/user3-128x128.jpg" alt="User Image">

                <div class="comment-text">
                  <span class="username">
                    <?php echo $gar['fld_remarks_by']; ?>
                    <span class="text-muted float-right"><?php echo date("d M, Y h:i A", strtotime($gar['fld_ts'])); ?></span>
                  </span><!-- /.username -->
                  <?php echo $gar['fld_remarks']; ?>
                </div>
                <!-- /.comment-text -->
              </div>
              <!-- /.card-comment -->
          </div>
            <?php
              }
            ?>
        </div>  
      </div>

      <form method="POST">
        <div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Add Remarks</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-body">
                  <textarea class="form-control" name="txtarearemarks"></textarea>
                </div>
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" name="sbtSaveRemarks" value="1" class="btn btn-primary">Save</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
      </form>
    </div>
  </div>

</section>
<!-- /.content -->
<?php 

} else {
  include("403.php");
}

?>