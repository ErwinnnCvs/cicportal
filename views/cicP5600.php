<?php



?>

<!-- Main content -->
<section class="content">

  <?php
  if ($_GET['ctrlno']) {
    $controlno = $_GET['ctrlno'];
    $get_entity_details = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_tinno, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_tinno,
            AES_DECRYPT(fld_compregno, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_compregno,
            AES_DECRYPT(fld_compregtype, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_compregtype,
            AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_name,
            fld_type,
            AES_DECRYPT(fld_addr_number, MD5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_addr_number,
            AES_DECRYPT(fld_addr_street, MD5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_addr_street,
            AES_DECRYPT(fld_addr_subdv, MD5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_addr_subdv,
            AES_DECRYPT(fld_addr_region, MD5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_addr_region,
            AES_DECRYPT(fld_landline, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_landline,
            AES_DECRYPT(fld_zip, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_zip,
            AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_provcode,
            AES_DECRYPT(fld_lname_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_lname_ar,
            AES_DECRYPT(fld_fname_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_fname_ar,
            AES_DECRYPT(fld_mname_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_mname_ar,
            AES_DECRYPT(fld_extname_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_extname_ar,
            AES_DECRYPT(fld_position_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_position_ar,
            AES_DECRYPT(fld_contactno_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_contactno_ar,
            AES_DECRYPT(fld_landline_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_landline_ar,
            AES_DECRYPT(fld_landlinecode_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_landlinecode_ar,
            AES_DECRYPT(fld_landlinelocal_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_landlinelocal_ar,
            AES_DECRYPT(fld_email_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_email_ar,
            AES_DECRYPT(fld_upload_ts_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_upload_ts_ar,
            AES_DECRYPT(fld_fname_c1, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_fname_c1,
            AES_DECRYPT(fld_mname_c1, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_mname_c1,
            AES_DECRYPT(fld_lname_c1, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_lname_c1,
            AES_DECRYPT(fld_position_c1, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_position_c1,
            AES_DECRYPT(fld_contactno_c1, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_contactno_c1,
            AES_DECRYPT(fld_landline_c1, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_landline_c1,
            AES_DECRYPT(fld_fname_c2, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_fname_c2,
            AES_DECRYPT(fld_mname_c2, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_mname_c2,
            AES_DECRYPT(fld_lname_c2, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_lname_c2,
            AES_DECRYPT(fld_position_c2, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_position_c2,
            AES_DECRYPT(fld_contactno_c2, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_contactno_c2,
            AES_DECRYPT(fld_landline_c2, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_landline_c2,
            AES_DECRYPT(fld_head_fname,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_head_fname,
            AES_DECRYPT(fld_head_mname,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_head_mname,
            AES_DECRYPT(fld_head_lname,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_head_lname,
            AES_DECRYPT(fld_head_extname,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_head_extname,
            AES_DECRYPT(fld_head_position,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_head_position,
            AES_DECRYPT(fld_head_email,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_head_email,
            AES_DECRYPT(fld_address, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_address,
            AES_DECRYPT(fld_email_c1, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_email_c1,AES_DECRYPT(fld_email_c2, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_email_c2,
            fld_re_validation_status FROM tbentities WHERE fld_ctrlno = '".$controlno."'");
    $ged=$get_entity_details->fetch_array();

    $bgy = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".$ged['fld_address']."'");
    $b = $bgy->fetch_array();
    $cty = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".str_pad(substr($row['fld_address'], 0, 6), 9, "0", STR_PAD_RIGHT)."'");
    $c = $cty->fetch_array();
    $prv = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".str_pad(substr($row['fld_address'], 0, 4), 9, "0", STR_PAD_RIGHT)."'");
    $p = $prv->fetch_array();
  ?>
  


  <div class="col-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Covered Entity Details</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="cic-controlno">CIC Control No.</label>
                
                <p id="cic-controlno"><?php echo $ged['fld_ctrlno']; ?></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="provider-code">Provider Code</label>
                <?php
                  if (!empty($ged['fld_provcode'])) {
                ?>
                <p id="provider-code"><?php echo $ged['fld_provcode']; ?></p>
                <?php
                  } else {
                ?>
                <p id="provider-code">NA</p>
                <?php
                  }
                ?>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="entity-name">Entity Name</label>
                <p id="entity-name"><?php echo $ged['fld_name']; ?></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="entity-type">Entity Type</label>
                <p id="entity-type"><?php echo $ent2[$ged['fld_type']]; ?></p>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="tin">Tax Identification Number</label>
                <p id="tin"><?php echo $ged['fld_tinno']; ?></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="reg-no">Company Registration Number</label>
                <p id="reg-no"><?php echo $ged['fld_compregno']; ?></p>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="address">Address</label>
                <p id="address"><?php echo $ged['fld_addr_number']." ".$ged['fld_addr_street']." ".$ged['fld_addr_subdv']." ".$b['fld_geotitle']." ".$c['fld_geotitle']." ".$p['fld_geotitle']; ?></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="contact-no">Contact No.</label>
                <p id="contact-no"><?php echo $ged['fld_landline']; ?></p>
              </div>
            </div>
          </div>

          
          <br>
          <h3 class="card-title">Head of Office</h3>
          <br>
          <hr>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-name">Name</label>
                <p id="hof-name"><?php echo $ged['fld_head_fname']. " " .$ged['fld_head_mname']. " " .$ged['fld_head_lname']; ?></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-position">Position</label>
                <p id="hof-position"><?php echo $ged['fld_head_position']; ?></p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-email">Email</label>
                <p id="hof-email"><?php echo $ged['fld_head_email']; ?></p>
              </div>
            </div>
          </div>

          <br>
          <h3 class="card-title">Primary Contact Person</h3>
          <br>
          <hr>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-name">Name</label>
                <p id="hof-name"><?php echo $ged['fld_fname_c1']. " " .$ged['fld_mname_c1']. " " .$ged['fld_lname_c1']; ?></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-position">Position</label>
                <p id="hof-position"><?php echo $ged['fld_position_c1']; ?></p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-email">Email</label>
                <p id="hof-email"><?php echo $ged['fld_email_c1']; ?></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-email">Landline</label>
                <p id="hof-email"><?php echo $ged['fld_landline_c1']; ?></p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-email">Mobile No. </label>
                <p id="hof-email"><?php echo $ged['fld_contactno_c1']; ?></p>
              </div>
            </div>
          </div>

          <br>
          <h3 class="card-title">Secondary Contact Person</h3>
          <br>
          <hr>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-name">Name</label>
                <p id="hof-name"><?php echo $ged['fld_fname_c2']. " " .$ged['fld_mname_c2']. " " .$ged['fld_lname_c2']; ?></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-position">Position</label>
                <p id="hof-position"><?php echo $ged['fld_position_c2']; ?></p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-email">Email</label>
                <p id="hof-email"><?php echo $ged['fld_email_c2']; ?></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-email">Landline</label>
                <p id="hof-email"><?php echo $ged['fld_landline_c2']; ?></p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-email">Mobile No. </label>
                <p id="hof-email"><?php echo $ged['fld_contactno_c2']; ?></p>
              </div>
            </div>
          </div>

          <br>
          <h3 class="card-title">Authorized Representative</h3>
          <br>
          <hr>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-name">Name</label>
                <p id="hof-name"><?php echo $ged['fld_fname_ar']. " " .$ged['fld_mname_ar']. " " .$ged['fld_lname_ar']; ?></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-position">Position</label>
                <p id="hof-position"><?php echo $ged['fld_position_ar']; ?></p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-email">Email</label>
                <p id="hof-email"><?php echo $ged['fld_email_ar']; ?></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-email">Landline</label>
                <p id="hof-email"><?php echo $ged['fld_landline_ar']; ?></p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-email">Mobile No. </label>
                <p id="hof-email"><?php echo $ged['fld_contactno_ar']; ?></p>
              </div>
            </div>
          </div>

        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>



  <?php
  }else{
  ?>


  <!-- Default box -->
  <form method="POST">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <!-- <div class="col-md-3"> -->
        
         <div class="input-group" style="float: right; width: 350px;">
            <input class="form-control border-end-0 border" type="text" placeholder="Search" name="search" value="<?php echo $_POST['search'];?>">
            <span class="input-group-append">
                <button type="submit" value="1" name="btnSearch" class="btn btn-outline-secondary bg-white border-start-0 border ms-n3">
                    <i class="fa fa-search"></i>
                </button>
            </span>
          </div>
        
       <!-- </div> -->
        </div>
        <div class="card-body table-responsive p-0">
          <table class="table table-head-fixed text-nowrap">
            <thead>
              <tr>
                <th>Provider Code</th>
                <th>Entity Name</th>
                <th>Entity Type</th>
              </tr>
            </thead>
            <tbody>
              <?php
                if (isset($_POST['page_no']) && $_POST['page_no']!="") {
                  $page_no = $_POST['page_no'];
                } else {
                  $page_no = 1;
                }

                $total_records_per_page = 10;
                $offset = ($page_no-1) * $total_records_per_page;
                $previous_page = $page_no - 1;
                $next_page = $page_no + 1;
                $adjacents = "2";
                
                $search = '';
                if ($_POST['search']) {
                  $search = " WHERE UPPER(CONVERT(AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) USING latin1)) LIKE '%".strtoupper($_POST['search'])."%'";
                }

                $get_count_all_entities = $dbh4->query("SELECT COUNT(*) AS count FROM tbentities".$search.";");
                $r_count = $get_count_all_entities->fetch_array();
                $total_records = $r_count['count'];

                $get_all_entities = $dbh4->query("SELECT fld_ctrlno, fld_type, AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_provcode, AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_name FROM tbentities".$search." ORDER BY AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) LIMIT $offset, $total_records_per_page;");
                
                $total_no_of_pages = ceil($total_records / $total_records_per_page);
                $second_last = $total_no_of_pages - 1;
                while ($gae=$get_all_entities->fetch_array()) {
              ?>
              <tr>
                <td><?php echo $gae['fld_provcode']; ?></td>
                <td><a href="main.php?nid=56&sid=0&rid=0&ctrlno=<?php echo $gae['fld_ctrlno']; ?>"><?php echo $gae['fld_name']; ?></a></td>
                <td><?php echo $ent2[$gae['fld_type']]; ?></td>
              </tr>
              <?php 
                }
              ?>
            </tbody>
          </table>
          <br/><br/>
          <!-- <div class="row"> -->

                  <!-- <div class="col"> -->
                    <ul class="pagination" style="float: right;">                        
                      <li <?php if($page_no <= 1){ echo "class='disabled'"; } ?>>
                        <button class='page-link' name='page_no' value='<?php if($page_no > 1){ echo $previous_page;}?>'>Previous</button>
                      </li>
                           
                        <?php 
                          if ($total_no_of_pages <= 10){     
                            for ($counter = 1; $counter <= $total_no_of_pages; $counter++){
                              if ($counter == $page_no) {
                                echo "<li class='page-item active'><button class='page-link'>$counter</button></li>";  
                              }else{
                                echo "<li class='page-item'><button class='page-link' name='page_no' value='$counter'>$counter</button></li>";
                              }
                            }
                          }elseif($total_no_of_pages > 10){
                            
                            if($page_no <= 4) {     
                              for ($counter = 1; $counter < 8; $counter++){     
                                if ($counter == $page_no) {
                                  echo "<li class='page-item active'><button class='page-link'>$counter</button></li>";  
                                }else{
                                  echo "<li class='page-item'><button class='page-link' name='page_no' value='$counter'>$counter</button></li>";
                                }
                              }
                              echo "<li class='page-item'><button class='page-link'>...</button></li>";
                              echo "<li class='page-item'><button class='page-link' name='page_no' value='$second_last'>$second_last</button></li>";
                              echo "<li class='page-item'><button class='page-link' name='page_no' value='$total_no_of_pages'>$total_no_of_pages</button></li>";

                            }elseif($page_no > 4 && $page_no < $total_no_of_pages - 4) {     
                              echo "<li class='page-item'><button class='page-link' name='page_no' value='1'>1</button></li>";
                              echo "<li class='page-item'><button class='page-link' name='page_no' value='2'>2</button></li>";
                              echo "<li class='page-item'><button class='page-link'>...</button></li>";
                              for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {     
                                if ($counter == $page_no) {
                                  echo "<li class='page-itemactive'><button class='page-link'>$counter</button></li>";  
                                }else{
                                  echo "<li class='page-item'><button class='page-link' name='page_no' value='$counter'>$counter</button></li>";
                                }                  
                              } 
                              echo "<li class='page-item'><button class='page-link'>...</button></li>";
                              echo "<li class='page-item'><button class='page-link' name='page_no' value='$second_last'>$second_last</button></li>";
                              echo "<li class='page-item'><button class='page-link' name='page_no' value='$total_no_of_pages'>$total_no_of_pages</button></li>"; 

                            }else {
                              echo "<li class='page-item'><button class='page-link' name='page_no' value='1'>1</button></li>";
                              echo "<li class='page-item'><button class='page-link' name='page_no' value='2'>2</button></li>";
                              echo "<li class='page-item'><button class='page-link'>...</button></li>";

                              for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
                                if ($counter == $page_no) {
                                  echo "<li class='page-item active'><button class='page-link'>$counter</button></li>";  
                                }else{
                                   echo "<li class='page-item'><button class='page-link' name='page_no' value='$counter'>$counter</button></li>";
                                }                   
                              }
                            }
                          }
                        ?>
                        
                      <li <?php if($page_no >= $total_no_of_pages){ echo "class='disabled'"; } ?>>
                        <button class='page-link' name='page_no' value='<?php if($page_no < $total_no_of_pages) { echo $next_page;}?>'>Next</button>
                      </li>
                        <?php if($page_no < $total_no_of_pages){
                        echo "<li class='page-item'><button class='page-link' name='page_no' value='$total_no_of_pages'>Last &rsaquo;&rsaquo;</button></li>";
                        } ?>
                    </ul>


                  <!-- </div> -->
                <!-- </div> -->
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    
  </div>
  </form>
  <?php
  }
  ?>
</section>
<!-- /.content -->