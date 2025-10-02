<?php

if($_POST['fetch']){
    $date = $_POST['fetch_date'];
    include("freshdesk/fetch_tickets_manual.php");


}

if($_POST['load']){
    $filename = $_POST['load'];
    include("freshdesk/load.php");
}
?>

<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Ticket</h3>
    </div>
    <div class="card-body">
        <?php
            if($msg){
        ?>
        <div class="callout-success">
                <p>SUCCESSFULLY LOADED</p>
        </div>
        <?php
            } elseif($msgfailed){
        ?>
        <div class="callout-danger">
                <p>NOT LOADED</p>
        </div>
        <?php
            }
        ?>
        <div class="row">
            
                <div class="col-md-2 col-lg-3">
                    <form method="POST">
                    <label for="">Fetch Tickets</label>
                    <input type="date" class="form-control" name="fetch_date">
                    <br>
                    <button type="submit" class="btn btn-success" value="1" name="fetch">Fetch</button>
                    </form>
                    
                    
                </div>
                <div class="col-md-9 col-lg-9">
                <label for="">Preview</label>
                <br>
                <?php
                    if($filename){
                        $data = file_get_contents($filename);
                        echo "<p>".$data."</p>";
                        
                ?>
                    <form method="POST">
                        <label for="">Load to Database</label>
                        <div>
                        <button type="submit" class="btn btn-success"  value="<?php echo $filename; ?>" name="load">Load</button>
                        </div>
                    </form>
                <?php
                    } else {
                        echo "NO DATA";
                    }
                ?>
                </div>
            
        </div>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->