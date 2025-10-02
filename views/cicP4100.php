<style type="text/css">
  .rating-ul {margin:0;padding:0;}
  .rating-li {display: inline-block;color: #fff700;text-shadow: 0 0 1px #666666;font-size:50px;}
</style>
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="row">
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <!-- <h3 class="card-title">Title</h3> -->
        </div>
        <div class="card-body">
          <h4>CE Portal Ratings</h4>
          <ul class="rating-ul">
            <?php
            for($i=1;$i<=5;$i++) {
            $selected = "";
            ?>
            <li class='rating-li'>&#9733;</li>  
            <?php }  ?>
            -
            <?php
              $get_five_ratings = $dbh->query("SELECT COUNT(fld_id) as five_ratings FROM tbrating WHERE fld_rating = 5 GROUP BY fld_rating");
              $gfr=$get_five_ratings->fetch_array();
              echo number_format($gfr['five_ratings']);
            ?>
          <ul>
          <ul class="rating-ul">
            <?php
            for($i=1;$i<=4;$i++) {
            $selected = "";
            ?>
            <li class='rating-li'>&#9733;</li>  
            <?php }  ?>
            -
            <?php
              $get_four_ratings = $dbh->query("SELECT COUNT(fld_id) as four_ratings FROM tbrating WHERE fld_rating = 4 GROUP BY fld_rating");
              $g4fr=$get_four_ratings->fetch_array();
              echo number_format($g4fr['four_ratings']);
            ?>
          <ul>
          <ul class="rating-ul">
            <?php
            for($i=1;$i<=3;$i++) {
            $selected = "";
            ?>
            <li class='rating-li'>&#9733;</li>  
            <?php }  ?>
            -
            <?php
              $get_three_ratings = $dbh->query("SELECT COUNT(fld_id) as three_ratings FROM tbrating WHERE fld_rating = 3 GROUP BY fld_rating");
              $gtr=$get_three_ratings->fetch_array();
              echo number_format($gtr['three_ratings']);
            ?>
          <ul>
          <ul class="rating-ul">
            <?php
            for($i=1;$i<=2;$i++) {
            $selected = "";
            ?>
            <li class='rating-li'>&#9733;</li>  
            <?php }  ?>
            -
            <?php
              $get_two_ratings = $dbh->query("SELECT COUNT(fld_id) as two_ratings FROM tbrating WHERE fld_rating = 2 GROUP BY fld_rating");
              $gt2r=$get_two_ratings->fetch_array();
              echo number_format($gt2r['two_ratings']);
            ?>
          <ul>
          <ul class="rating-ul">
            <?php
            for($i=1;$i<=1;$i++) {
            $selected = "";
            ?>
            <li class='rating-li'>&#9733;</li>  
            <?php }  ?>
            -
            <?php
              $get_one_ratings = $dbh->query("SELECT COUNT(fld_id) as one_ratings FROM tbrating WHERE fld_rating = 1 GROUP BY fld_rating");
              $gor=$get_one_ratings->fetch_array();
              echo number_format($gor['one_ratings']);
            ?>
          <ul>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
  </div>

</section>
<!-- /.content -->