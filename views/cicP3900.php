<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);


?>
<!-- Main content -->
<section class="content">

  
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">List of Disputes per TRN</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <?php
          // print_r($_POST);
          if ($_POST['btnSubmit']) {
            $sql2 = $dbh2->query("SELECT fld_TRN, fld_DateFilled, AES_DECRYPT(fld_Email, CONCAT(fld_Birthday, 'G3n13')) AS email, AES_DECRYPT(fld_Fname, CONCAT(fld_Birthday, 'G3n13')) AS fname, AES_DECRYPT(fld_Mname, CONCAT(fld_Birthday, 'G3n13')) AS mname, AES_DECRYPT(fld_Lname, CONCAT(fld_Birthday, 'G3n13')) AS lname, AES_DECRYPT(fld_Suffix, CONCAT(fld_Birthday, 'G3n13')) AS suffix, changes FROM `subject` WHERE fld_TRN = '".$_POST['btnSubmit']."'");
            $rsubj = $sql2->fetch_array();
            // print_r($rsubj);

            

           

          ?>
          <div class="modal fade in" tabindex="-1" role="dialog" id="myModal">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title"><?php echo $rsubj['fname'].($rsubj['mname']? ' '.$rsubj['mname'].' ': ' ').$rsubj['lname'].($rsubj['suffix']? ' '.$rsubj['suffix']:'');?> - TRN No. <?php echo $rsubj['fld_TRN'];?></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <!-- <p>Modal body text goes here.</p> -->
                  <?php
                  $sqlcontr=$dbh2->query("SELECT * FROM `contract` WHERE fld_TRN = ".$rsubj['fld_TRN']."");
                  while ($rcontr = $sqlcontr->fetch_array()) {
                    if ($rcontr['fld_prov']) {
                       $sqlse=$dbh4->query("SELECT AES_DECRYPT(fld_name, MD5(CONCAT( fld_ctrlno, 'RA3019'))) AS name FROM tbentities WHERE AES_DECRYPT(fld_provcode, MD5(CONCAT( fld_ctrlno, 'RA3019'))) = '".$rcontr['fld_prov']."'");
                        $rse = $sqlse->fetch_array();
                        $sename = $rse['name'];
                    }else{
                        $sename = $rcontr['fld_name'];
                    }

                    $subjarr = array("01"=>"Main Address","02"=>"Civil Status","03"=>"Secondary Address","04"=>"TIN Number","05"=>"Cars Owned","06"=>"SSS Number","07"=>"Number of Dependents","08"=>"GSIS Number","09"=>"Gross Income","10"=>"Additional Phone", "11"=>"Company Trade Name", "12"=>"Main Phone","13"=>"Company Main Address","14"=>"Name");
                    $subjdata = "<br/><u><b>Subject Data</b></u> :<br/>";
                    if($rsubj['changes']){
                        $subject = explode("|",$rsubj['changes']);
                        $subjdatactr = 0;
                        foreach($subject as $skey => $sval){
                            $subjdatactr++;
                            if($skey > 0 && $skey < (count($subject)-1)){
                                $subjdata .= $subjarr[$sval]."<br/>";
                            }
                        }
                    }



                    if($rcontr['fld_contractType']){
                        $contdata = "<u><b>Contract Data</b></u> :<br/>Contract Type : ".$rcontr['fld_contractType']."<br/>";
                        if($rcontr['fld_contractType'] == "Loan"){
                            $contdata .= "Loan Type : ".$rcontr['fld_loanName']."<br/>";
                        }
                        $contdata .= "Complaint : ".$rcontr['fld_complaint']."<br/>"
                            ."Description : ".$rcontr['fld_description']."<br/>";
                    }

                    echo "";
                    echo "<br/><hr><h5>".$sename."</h5>";
                    if ($rsubj['changes']) {
                      echo "<p align='justify'>".$subjdata."</p>";
                    }
                    
                    echo "<p align='justify'>".$contdata."</p>";
                  }

                  
                  ?>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          <?php
          }
          ?>
          
          <br/><br/>
          <form method="POST">
            <table id="tbdisputelist" class="table table-bordered table-striped" style="table-layout: fixed; width: 100%;">
              <thead>
                <tr>
                  <th width="20px" class="no-sort" style="display: none;"></th>
                  <th>Date Filed</th>
                  <th>TRN</th>
                  <th>Email</th>
                  
                </tr>
              </thead>
              <tbody>
                <?php
                  $sql = $dbh2->query("SELECT fld_TRN, fld_DateFilled, AES_DECRYPT(fld_Email, CONCAT(fld_Birthday, 'G3n13')) AS email FROM `subject` ORDER BY fld_DateFilled DESC");
                  while ($r=$sql->fetch_array()) {
                ?>
                <tr>
                  <td align="center" style="display: none;"></td>
                  <td data-sort="<?php echo date("Y-m-d", strtotime($r['fld_DateFilled']));?>"><?php echo date("M j, Y", strtotime($r['fld_DateFilled']));?></td>
                  <td><a href=""><button name="btnSubmit" type="submit" class="btn btn-link" value="<?php echo $r['fld_TRN']; ?>"><?php echo $r['fld_TRN']; ?></button></a></td>
                  <td><?php echo $r['email']; ?></td>
                  
                </tr>
                <?php
                  }
                  if ($sql->num_rows < 1) {
                ?>
                <tr>
                  <td colspan="3" align="center">No record</td>
                </tr>
                <?php
                  }
                ?>
              </tbody>
            </table>
          </form>
        </div>
      </div>
    </div>
  </div>

  
</section>


<script type="text/javascript">
  
  document.addEventListener("DOMContentLoaded", function(){
      // alert();
      $('#myModal').modal('show');
  });
</script>