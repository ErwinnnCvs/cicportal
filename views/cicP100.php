<?php

if ($_SESSION['user_id'] == 76 || $_SESSION['user_id'] == 108 || $_SESSION['user_id'] == 147 || $_SESSION['user_id'] == 121 || $_SESSION['user_id'] == 224) {
  include("dashboard/dashboard.php");
} else {
?>

<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-body">
      <center>
      <img src="dist/img/cic-web-banner.jpg">
      </center>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->
<?php
}
?>