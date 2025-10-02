<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

if (in_array($_SESSION['user_id'], $tech_team)) {
  $type = 1;
} elseif (in_array($_SESSION['user_id'], $legal_team)) {
  $type = 2;
}


function searchArrayByKeyword($array, $keyword) { 
  $results = array(); 
  foreach ($array as $item) { 
    foreach ($item as$key => $value) { 
      if (stripos($value, $keyword) !== false) { // Case-insensitive search 
        $results[] = $item; 
        break; // Break inner loop once a match is found 
      } 
    } 
  } return $results; 
}

if ($_SESSION['user_id'] == 76 || $_SESSION['user_id'] == 197) {
  $query = 'admin';
  $queryfreshdesk = '';
} else {
  $query = ' and b.fld_assign = '.$_SESSION['user_id'];
  $queryfreshdesk = " and fld_type = ".$type;
}


  
?>

<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <form method="POST">
    <div class="card-body">
      <div class="row">
        <div class="col-lg-4">
          <div class="form-group">
<!--                    <form action="#" method="get" class="sidebar-form">    -->
            <label>Search</label> <small>( Enter either Provider Code or Part of the Company Name )</small>
            <div class="input-group">
              <input type="text" name="txtSearch" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                  <button type="submit" name="sbtSearch" id="search-btn" value="1" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
                <a href="main.php?nid=137&sid=0&rid=0&page=1" name="sbtClearSearch" id="search-btn" value="1" class="btn btn-success btn-flat">Clear Search
                </a>
              </span>
            </div>
<!--                    </form>    -->
          </div>
        </div>
      </div>
      <div>
      </div>
      <div id="secimage-loading" style="display:none;width:69px;height:89px;position:absolute;top:100%;left:40%;padding:2px; z-index: 5;"><img src="images/process.gif"></div>
      <div id="secoverlay"></div>
      <input type="hidden" name="query" value="<?php echo $query; ?>">
      <table id="casemanagement" class="table table-bordered table-striped dataTable dtr-inline" aria-describedby="example1_info">
      <thead>
          <tr>
            <th tabindex="0" rowspan="1" colspan="1">Provider Code</th>
            <th tabindex="0" rowspan="1" colspan="1">Submitting Entity</th>
            <th tabindex="0" rowspan="1" colspan="1" width="15%"><center>Last Login (CE Portal)</center></th>
            <!-- <th tabindex="0" rowspan="1" colspan="1"><center>Priority</center></th> -->
            <th tabindex="0" rowspan="1" colspan="1"><center>Tickets</center></th>
            <th tabindex="0" rowspan="1" colspan="1"><center>Missed Months</center></th>
            <th tabindex="0" rowspan="1" colspan="1"><center>Last Submitted</center></th>
            <th tabindex="0" rowspan="1" colspan="1"><center>Last Loaded</center></th>
            <!-- <th tabindex="0" rowspan="1" colspan="1" width="10%"><center>Action</center></th> -->
            <!-- <th tabindex="0" rowspan="1" colspan="1"><center>System Remarks</center></th> -->
            <th tabindex="0" rowspan="1" colspan="1"><center>CIC Remarks</center></th>
          </tr>
      </thead>
      <tbody id="casemanagementbody">
      
      
      </tbody>
      </table>
      
    </div>
    <!-- /.card-body -->
    </form>
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->