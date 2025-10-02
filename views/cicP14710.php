<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

if ($_POST['sbtCommentSave']) {
  if (!$_POST['selValidationOptions']) {
    $err = "Please select validation";
  } else {
    $validation = $_POST['selValidationOptions'];
    $name = $_SESSION['user_id'];
    $timestamp = date("Y-m-d H:i:s");

    $remarks = trim($_POST['commentTxt']);
    
    if($_POST['ictDate'] != ""){
      // echo "Ext Date: ".$_POST['extensionDate'];
      // echo "<br>";
      // echo "ICT Date: ".$_POST['ictDate'];
      // echo "<br>";
      $minRegDate = strtotime($_POST['extensionDate']);
      $currentDate = strtotime($_POST['ictDate']); 
      $getOverdueDayCnt = 0;
      //Compute Extension Day Count
      while(date('Y-m-d', $minRegDate) < date('Y-m-d', $currentDate)){
          $getOverdueDayCnt += date('N', $minRegDate) < 6 ? 1 : 0;
          $minRegDate = strtotime("+1 day", $minRegDate);
      }

      $getOverdueDayCnt += $_POST['extensionDateCnt'];
    }
    // echo 'Overdue:'.$getOverdueDayCnt;

    // echo "UPDATE tbextensionicmt SET fld_ext_status = ".$validation.", fld_validate_remarks = '".$remarks."', fld_validate_by = '".$name."', fld_validate_ts = '".$timestamp."', fld_ext_date_ict = '".$_POST['ictDate']."', fld_ext_date_cnt_ict = '".$getOverdueDayCnt."' WHERE fld_id = ".$_GET['id']." ;";


    // if(!isset($_POST['ictDate']) && $getOverdueDayCnt != ""){

    
    // echo "UPDATE tbextensionicmt SET fld_ext_status = ".$validation.", fld_validate_remarks = '".$remarks."', fld_validate_by = '".$name."', fld_validate_ts = '".$timestamp."', fld_ext_date_ict = '".$_POST['ictDate']."', fld_ext_date_cnt_ict = '".$getOverdueDayCnt."' WHERE fld_id = ".$_GET['id']." ;";
    // die();
    $dbh4->query("UPDATE tbextensionicmt SET fld_ext_status = ".$validation.", fld_validate_remarks = '".$remarks."', fld_validate_by = '".$name."', fld_validate_ts = '".$timestamp."', fld_ext_date_ict = '".$_POST['ictDate']."', fld_ext_date_cnt_ict = '".$getOverdueDayCnt."' WHERE fld_id = ".$_GET['id']." ;");
    $msg = " Successfully updated";
  // }

  }
}



$get_request_extension_details = $dbh4->query("SELECT * FROM tbextensionicmt WHERE fld_id = ".$_GET['id']."; ");

$gred=$get_request_extension_details->fetch_array();

$get_user_requested = $dbh5->query("SELECT * FROM tbusers WHERE pkUserId = ".$gred['fld_filed_by']."; ");
$gur=$get_user_requested->fetch_array();

$getValidatorName = $dbh5->query("SELECT * from tbcicusers where pkUserId = '".$gred['fld_validate_by']."' ");
$gvn = $getValidatorName->fetch_array();

$get_company_name = $dbh4->query("SELECT AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM tbentities WHERE fld_ctrlno = ".$gur['fld_ctrlno']."; ");
$gcn=$get_company_name->fetch_array();

$user = $gur['fld_name']. " - " .$gur['email'];
$company = $gcn['name'];

$gred['fld_ext_date'] = date("M d, Y", strtotime($gred['fld_ext_date']));
$gred['fld_inserted_date'] = date("M d, Y", strtotime($gred['fld_inserted_date']));
$minDate =  date("Y-m-d", strtotime($gred['fld_ext_date']));

if($gred['fld_process_type'] == 1){
    $gred['fld_process_type'] = "Registration";
}elseif($gred['fld_process_type'] == 2){
    $gred['fld_process_type'] = "Training & Evaluation";
}elseif($gred['fld_process_type'] == 3){
    $gred['fld_process_type'] = "Production";
}

$transType[$gred['fld_submission_type']] = ' selected';
// "1" => "Approved", "2" => "Rejected",
$selvaloptions = array(   "3" => "Endorsed for Approval", "4" => "Rejected"); 

if ($_POST['selValidationOptions']) {
    $valsel[$_POST['selValidationOptions']] = " selected";
} else {
    $valsel[$gred['fld_ext_status']] = " selected";
}



?>

<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <?php
        if($msg){
      ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        <?php echo $msg; ?>
      </div>
      <?php
        }
      ?>
    </div>
    <div class="card-body">
      <div class="row">

          <div class="col-lg-6">
              <div class="form-group">
                  <label for="input-mname" class="col-form-label">Requested By</label>
                  <input type="text" class="form-control"  name="companyName" id="companyName" value="<?php echo $company; ?>" disabled>
              </div>
              <div class="form-group">
                  <label for="input-mname" class="col-form-label">User</label>
                  <input type="text" class="form-control"  name="filedBy" id="filedBy" value="<?php echo $user; ?>" disabled>
              </div>
              <div class="form-group">
                  <label for="input-mname" class="col-form-label">Requested Date for Extension</label>
                  <input type="text" class="form-control"  name="extensionDate" id="extensionDate" value="<?php echo date("F d, Y", strtotime($gred['fld_ext_date'])); ?>" disabled>
              </div>
              <div class="form-group">
                      <label class="col-form-label">Process Type</label>
                      <input type="text" class="form-control"  name="processType" id="processType" value="<?php echo $gred['fld_process_type']?>" disabled>

                  
              </div>

              <div class="form-group">
                  <label for="input-mname" class="col-form-label">Date Request Filed</label>
                  <input type="text" class="form-control"  name="filedDate" id="filedDate" value="<?php echo date("F d, Y", strtotime($gred['fld_inserted_date'])); ?>" disabled>
              </div>
              <br>
             <div id="all" > <div style="display:block;width:100%;max-width:650px;margin:0 auto" > <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr style="background-color: #f5f5f5;"> </tr> <tr style="background-color:#f5f5f5"> <td align="center" style="border-left:1px solid rgb(202,201,200);border-right:1px solid rgb(202,201,200);padding-bottom: 15px" class="m_-3480498238929770810gmail-m_3586475887212474448null-pad-logo"><a href="https://www.creditinfo.gov.ph/" style="display:block;margin-bottom:10px" target="_blank"><img class="m_-3480498238929770810gmail-m_3586475887212474448head_1_logo-833 CToWUd" src="https://www.creditinfo.gov.ph/cicportal/images/CICLogo.png" width="100%" style="background-color: #f5f5f5" alt="" style="max-width:645px;display:block"></a></td> </tr> <tr> <td> <div> <table bgcolor="#ffffff" width="100%" border="0" cellspacing="0" cellpadding="0" style="border-left:1px solid rgb(202,201,200);border-right:1px solid rgb(202,201,200);background-color: #f5f5f5" > <tbody> <tr height="20px" rowspan="1" colspan="3"> <td style="max-width: 645px; word-wrap: break-word;font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:15px;color:black;line-height:1.25;min-width:300px;padding:10px 30px 15px;" id="editTR"> <?php echo $gred['fld_ext_remarks']; ?> </td> </tr> </tbody> </table> </div> </td> </tr> <tr> <table width="100%" bgcolor="#cac9c8" style="background-color:#cac9c8" border="0" cellpadding="0" cellspacing="0" align="center"> <tbody> <tr> <td width="50%" valign="top" dir="ltr" class="m_-8036216849319893849m_-6971811544154621716m_3167257287174861847gmail-m_3586475887212474448full m_-8036216849319893849m_-6971811544154621716m_3167257287174861847gmail-m_3586475887212474448mobile-pad" style="padding:0px 30px"> <table align="left" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse"> <tbody> <tr>  </tr> </tbody> </table> </td> <td width="50%" valign="top" dir="ltr" class="m_-8036216849319893849m_-6971811544154621716m_3167257287174861847gmail-m_3586475887212474448full m_-8036216849319893849m_-6971811544154621716m_3167257287174861847gmail-m_3586475887212474448mobile-pad" style="padding:0px 30px"> <table align="left" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse"> <tbody> <tr> <td valign="top" class="m_-8036216849319893849m_-6971811544154621716m_3167257287174861847gmail-m_3586475887212474448mobile-padding" style="padding-right:10px;padding-left:0px"> <p style="margin:25px 0px 15px;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:16px;color:rgb(20,72,133);text-align:left;line-height:16px;font-weight:bold"></p> <p style="margin:3px 0px;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:11px;color:rgb(130,128,128);text-align:left;font-weight:normal"><a href="http://lt.bmetrack.com/c/l?u=758CDE5&amp;e=BECF3A&amp;c=BEA8C&amp;t=0&amp;l=728FD611&amp;email=P0Q4uV88FhK3ya6LXlVcn5VpQyVbkcC0zM4%2B82xIOs0%3D&amp;seq=1" class="m_-8036216849319893849m_-6971811544154621716m_3167257287174861847gmail-m_3586475887212474448sp-footer3-website-link" style="display:inline-block;margin:0px;text-decoration:none" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=http://lt.bmetrack.com/c/l?u%3D758CDE5%26e%3DBECF3A%26c%3DBEA8C%26t%3D0%26l%3D728FD611%26email%3DP0Q4uV88FhK3ya6LXlVcn5VpQyVbkcC0zM4%252B82xIOs0%253D%26seq%3D1&amp;source=gmail&amp;ust=1516678374634000&amp;usg=AFQjCNG-0iiHeMHag5ZTrnLRjcP620Dulg"><span style="vertical-align:top;display:inline-block;line-height:24px;margin-left:5px;color:rgb(187,115,36)"></span></a></p> </td> </tr> </tbody> </table> </td> </tr> <tr> <td colspan="2"> </td> </tr> </tbody> </table> <table width="100%" cellpadding="0" cellspacing="0" bgcolor=""> <tbody> <tr> <td style="padding:0px"><img src="https://ci5.googleusercontent.com/proxy/OeFR5c8xevLQDr5TL_ubgXgUjI2sMwl5fD_p7CZASJiFqcgdOd-5sXDkFMHPlcSXgkA0XF-mHpEVdZW466k5-QHntFNS5HXHTI14bVNLKktQcNnL2hg_RmJQlNMDiREGm_EkV-7SLUQWqA=s0-d-e1-ft#https://tools.propelrr.com/email-campaign/email-template/images/social-media-bot.jpg" alt="" style="display:block;width:100%" class="CToWUd"></td> </tr> </tbody> </table> </tr> <tr style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:10px;color:#666666;line-height:18px;padding-bottom:10px"> <footer><!-- <small>© 2017 Credit Information Corporation. <a href="https://www.google.com/maps/place/Credit+Information+Corporation/@14.5560001,121.0148883,17z/data=!3m1!4b1!4m5!3m4!1s0x3397c90e63386907:0x2132dc0efdf6cf6e!8m2!3d14.5560001!4d121.017077" target="_blank"> 6th Floor, Exchange Corner Building 107 V.A. Rufino Street corner Esteban Street Legaspi Village,1229, Makati City. </a></small> --></footer> </tr> </table> <tr height="16px"></tr> </div></div>

          </div>  
                  
          <div class="col-lg-6">
              <div class="card">
        <div class="card-header d-flex p-0">
          <h3 class="card-title p-3">Action</h3>
        </div><!-- /.card-header -->
        <div class="card-body">
          <form method="post">
            <div class="form-group">

            <?php
                  if ($gred['fld_validate_by'] != NULL && $gred['fld_validate_ts'] != NULL) { ?>
                  <div class="form-group">
                    <label>Endorsed By</label>
                    <input type="text" class="form-control" value="<?php echo $gvn['fld_name']; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label>Endorsed Date</label>
                    <input type="text" class="form-control" value="<?php echo $gred['fld_validate_ts']; ?>" disabled>
                  </div>
                  <input type="text" class="form-control"  name="extensionDate" id="extensionDate" value="<?php echo $gred['fld_ext_date']; ?>" hidden>
                  <input type="text" class="form-control"  name="extensionDateCnt" id="extensionDateCnt" value="<?php echo $gred['fld_ext_date_cnt']; ?>" hidden>
            <?php } ?>
                  <?php
                  if ($gred['fld_ext_status'] != 0 ) {
                    $validation = "disabled";
                    if(is_null($gred['fld_ext_date_ict'])){
                      
                    }else{

                      // if($gred['fld_ext_date_ict'] == "0000-00-00 00:00:00"){
                      //    $gred['fld_ext_date_ict'] =
                      // }
                  ?>
                    <div class="form-group">
                      <label>Override Extension Date (Optional)</label>
                      <input type="text" class="form-control" name = "ictDate" value="<?php $gred['fld_ext_date_ict'] == "0000-00-00 00:00:00" ? print_r("N/A")  : print_r(date("Y-m-d" , strtotime($gred['fld_ext_date_ict']))) ;?>" disabled>
                    </div>
                  <?php } }else{ 
                  
                  ?>
                   <div class="form-group">
                      <label>Override Extension Date (Optional)</label>
                      <input type="date" class="form-control" name = "ictDate" value="<?php $_POST['ictDate']; ?>" >
                    </div>
                  <?php } ?>
                  

                  <label>Validation</label>
                  <?php
                  if ($gred['fld_ext_status'] == 0) {
                  ?>
                  <select class="form-control" name="selValidationOptions" required>
                    <option selected disabled>--SELECT OPTION---</option>
                    <?php
                     foreach ($selvaloptions as $key => $value) {
                      echo "<option value='".$key."'".$valsel[$key].">".$value."</option>";
                      
                     }
                    ?>
                  </select>  
                </div>
                
                <label>Add Comment</label>
                <textarea class="form-control" name="commentTxt"></textarea>
                <br>
                <button type="submit" name="sbtCommentSave" value="1" class="btn btn-default">Save</button>
                <?php
                  } else {
                ?>
                <select class="form-control" name="selValidationOptions" disabled>
                    <option selected disabled>--SELECT OPTION---</option>
                    <?php
                     foreach ($selvaloptions as $key => $value) {
                      echo "<option value='".$key."'".$valsel[$key].">".$value."</option>";
                      
                     }
                    ?>
                  </select>

                  <label>Comments</label>
                  <textarea class="form-control" name="commentTxt" disabled>
                    <?php
                      echo $gred['fld_validate_remarks'];
                    ?>
                  </textarea>
                <?php
                  }
                ?>
          </form>
        </div><!-- /.card-body -->
      </div>
      <!-- ./card -->
          </div>

      </div>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->