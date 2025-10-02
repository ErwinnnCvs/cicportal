<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

$return_code_arr = array(0=>"NOT OK", 1=>"OK");


if (isset($_GET['page_no']) && $_GET['page_no']!="") {
  $page_no = $_GET['page_no'];
} else {
  $page_no = 1;
}

if(!$_POST['yearmonthday']){
  $_POST['yearmonthday'] = "2025-05-19";
}

$yrsel[$_POST['yearmonthday']] = " selected";

?>

<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
          
      <div class="input-group-prepend">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          Detailed View
        </button>
        <ul class="dropdown-menu">
          <li class="dropdown-item"><a href="main.php?nid=158&sid=0&rid=0">Chart View</a></li>
          <li class="dropdown-item"><a href="main.php?nid=158&sid=1&rid=1">Summary View</a></li>
          <li class="dropdown-item"><a href="main.php?nid=158&sid=1&rid=2">Detailed View</a></li>
        </ul>
      </div>
    </div>
    <div class="card-body">
      <form method="post">
        <label>Start Date</label>
        <select name="yearmonthday" class="form-control" style="width: 10%;" onchange="submit()">
        <?php
          $cnt1=0;
          $sql=$dbh4->query("SELECT DATE_FORMAT(fld_startdate, '%Y-%m-%d') AS ymd FROM tbcisauditloginquiries WHERE fld_startdate <= '".date("Y-m-d")."' GROUP BY DATE_FORMAT(fld_startdate, '%Y-%m-%d')");
          while($h=$sql->fetch_array()){
            if(!$_POST['yearmonthday']){
              $_POST['yearmonthday'] = $h['ymd'];
            }
            $dt = explode("-", $h['ymd']);
            echo "<option value='".$h['ymd']."'".$yrsel[$h['ymd']].">".date("F d, Y", strtotime($h['ymd']))."</option>";
          }
        ?>
        </select>
      </form>
      


      <br>
      <table class="table table-border table-sm">
        <thead>
          <tr>
            <th>#</th>
            <th><center>Sys Return Code</center></th>
            <th><center>Biz Return Code</center></th>
            <th><center>Start Date</center></th>
            <th><center>Elapsed Time</center></th>
            <th><center>Application</center></th>
            <th><center>Component</center></th>
            <th><center>Error Code</center></th>
            <th><center>Error Message</center></th>
            <th><center>User</center></th>
          </tr>
        </thead>
        <tbody>
          <?php

          $total_records_per_page = 20;

          $offset = ($page_no-1) * $total_records_per_page;
          $previous_page = $page_no - 1;
          $next_page = $page_no + 1;
          $adjacents = "2";

          $result_count = $dbh4->query("SELECT COUNT(*) as cnt_records FROM tbcisauditloginquiries WHERE fld_startdate LIKE '".$_POST['yearmonthday']."%'");
          $rc=$result_count->fetch_array();

          $total_records = $rc['cnt_records'];
          $total_no_of_pages = ceil($total_records / $total_records_per_page);
          $second_last = $total_no_of_pages - 1;


            $c = $offset;
            $get_all_pto_logs_detailed = $dbh4->query("SELECT fld_sys_return_code, fld_biz_return_code, fld_startdate, fld_elapsed_time, fld_application, fld_component, fld_error_code, fld_error_message, fld_user FROM tbcisauditloginquiries WHERE fld_startdate LIKE '".$_POST['yearmonthday']."%' ORDER BY fld_startdate ASC LIMIT ".$offset.", ".$total_records_per_page.";");
            while ($gapld=$get_all_pto_logs_detailed->fetch_array()) {
              $c++;
          ?>
          <tr>
            <td><center><?php echo $c; ?></center></td>
            <td><center><?php echo $return_code_arr[$gapld['fld_sys_return_code']]; ?></center></td>
            <td><center><?php echo $return_code_arr[$gapld['fld_biz_return_code']]; ?></center></td>
            <td><center><?php echo $gapld['fld_startdate']; ?></center></td>
            <td><center><?php echo $gapld['fld_elapsed_time']; ?></center></td>
            <td><center><?php echo $gapld['fld_application']; ?></center></td>
            <td><center><?php echo $gapld['fld_component']; ?></center></td>
            <td><center><?php echo $gapld['fld_error_code']; ?></center></td>
            <td><center><?php echo $gapld['fld_error_message']; ?></center></td>
            <td><center><?php echo $gapld['fld_user']; ?></center></td>
          </tr>
          <?php
            }

          ?>
        </tbody>
      </table>

      <br><br>
      <div style='padding: 5px 10px 0px; border-top: dotted 1px #CCC;'>
        <strong>Page <?php echo $page_no." of ".number_format($total_no_of_pages); ?></strong>
      </div>
      <br><br>
      <ul class="pagination pagination-month">
      <?php if($page_no > 1){
      echo "<li class='page-item'><a class='page-link' href='?nid=158&sid=1&rid=2&page_no=1'>First Page</a></li>";
      } ?>
          
      <li class="page-item" <?php if($page_no <= 1){ echo "class='disabled page-item'"; } ?>>
      <a class="page-link" <?php if($page_no > 1){
      echo "href='?nid=158&sid=1&rid=2&page_no=$previous_page'";
      } ?>>«</a>
      </li>
          
      <li class="page-item" <?php if($page_no >= $total_no_of_pages){
      echo "class='disabled'";
      } ?>>
      <a  class="page-link" <?php if($page_no < $total_no_of_pages) {
      echo "href='?nid=158&sid=1&rid=2&page_no=$next_page'";
      } ?>>»</a>
      </li>

      <?php if($page_no < $total_no_of_pages){
      echo "<li class='page-item'><a class='page-link' href='?nid=158&sid=1&rid=2&page_no=$total_no_of_pages'>Last &rsaquo;&rsaquo;</a></li>";
      } ?>
      </ul>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->