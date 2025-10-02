<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>srtdash - ICO Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="assets/images/icon/favicon.ico">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/metisMenu.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/slicknav.min.css">
    <!-- amchart css -->
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
    <!-- others css -->
    <link rel="stylesheet" href="assets/css/typography.css">
    <link rel="stylesheet" href="assets/css/default-css.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/responsive.css">

    <!-- modernizr css -->
    <script src="assets/js/vendor/modernizr-2.8.3.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
</head>



             <style>
.main1{ 
  margin-left: 10px; 
} 

          </style>



<?php
$conn = mysqli_connect("10.250.111.80", "evaluser", 'xc&m9rSCkuXY2', "evaluation");

 
        // Check connection
        if (mysqli_connect_errno())
          {
          echo "Failed to connect to MySQL: " . mysqli_connect_error();
          }




  $id=$_GET['id'];
  $query=mysqli_query($conn,"select * from `trainer` where id='$id'");
  $row=mysqli_fetch_array($query);
?>
<!DOCTYPE html>
<html>
<head>
<title>Module</title>
</head>
<body>
  <h2>Edit</h2>

  <body class="bg-dark">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card mt-5">
                    <div class="card-body">
     <form method="POST" action="views/cicP3315.php?id=<?php echo $id; ?>">
    <!--<label class="main1">Date:</label><input type="date" class="main1" value="" name="trainingdateone"> -->
    
    <label class="main1">Trainer:</label>
    <select name="secondtrainer" id="secondtrainer" class="form-control" value="<?php echo $row['secondtrainer'] ?>" style="height: 40px;">
                                  <option value="" disabled selected><?php echo $row['secondtrainer'] ?></option>
                                  <option value="Geraldine Alvarado">Geraldine Alvarado</option>
                                  <option value="Lady Hannah Despabiladeras">Lady Hannah Despabiladeras</option>
                                  <option value="Claude dela Torre">Claude dela Torre</option>
                                  <option value="Josan Mercado">Josan Mercado</option>
                                  <option value="Gilbert Mulano">Gilbert Mulano</option>
                                  <option value="Victoria Ualat">Victoria Ualat</option>
                                  <option value="Jacquiline Cardiño">Jacquiline Cardiño</option>
                                  <option value="Amy Amido">Amy Amido</option>
                              </select>
   
                              <br>
    <input type="submit" name="submit" class="main1">
    <a href="main.php?nid=33&sid=1&rid=0" class="main1">Back</a>
  </form>
</body>
</html>

</body>
</div>
</div>
</div>
</div>
</div>


