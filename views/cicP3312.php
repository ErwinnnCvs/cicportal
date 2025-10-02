<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>srtdash - ICO Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="assets/images/icon/favicon.ico">
    <!-- amchart css -->
    <!-- others css -->
    
    <link rel=”stylesheet” href=”https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css”>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


    <!-- modernizr css -->
</head>


            <style>
            #customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #1261A0;
  color: white;
}

.main1{ 
  text-align-last: center;
  font-weight:normal
} 
 
.main2{ 
  margin-left: 10px; 
} 
 
.main3{ 
  margin-left: 10px; 
} 



.main5{ 
  margin-left: 5px; 
} 

.main6{ 
  margin-left: 5px; 
}

.main7{ 
  margin-left: 68px; 
}

.main8{ 
  margin-left: 10px; 
}


.form-control1
{ 
  margin-left: 5px; 
}

.form-control2
{ 
  margin-left: 5px; 
}

h2 {text-align: center;}

button{
    height:25px; 
    width:50px; 
    margin: -20px -30px; 
    position:relative;
    top:50%; 
    left:50%;
    color:black;
}

select.form-control1{display:inline-block}

select.form-control2{display:inline-block}

#firsttrainer{
 width:200px;   
}

#secondtrainer{
 width:200px;   
}

.modal-dialog{
    position: relative;
    display: table; /* This is important */ 
    overflow-y: auto;    
    overflow-x: auto;
    width: 700px;
    min-width: 700px;   
}

.center1 {
text-align: center;
border-bottom: 5px solid lightgrey;
}

.center2 {
text-align: center;
}

.float-container {
    border: 3px solid #fff;
    padding: 30px;
}

.float-child {
    width: 10%;
    float: left;
    padding: 20px;
}  

.br {
    display: block;
    margin-bottom: 0.5em;
}

            
          </style>




<!DOCTYPE html>
<html>
<head>
<title>Module</title>
</head>

<body class="bg-dark">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card mt-5">
                    <div class="card-body">
                      <h2>Technical Workshop Schedule</h2>
                      <br>
                      <div id="ab">Status:</div><select id="fetchval" name="fetchby" onchange="location = this.value;">
                       <option value="main.php?nid=33&sid=1&rid=2">Done</option>
                        <option value="main.php?nid=33&sid=1&rid=0">All</option>
                        <option value="main.php?nid=33&sid=1&rid=1">Active</option>
                    </select>
                      






    


  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>
       
        <div class="modal-body">


    <div id="myDIV" style="display:none" class="center1">
        <form method="POST" action="views/cicP3313.php">
    
      
      
      <h4 style="text-align:center"><strong>Add New Schedule</strong></h4>
      <br>
      <label class="main7" style="font-weight: normal;">Training Date:</label>             <input type="date" name="trainingdateone" class="main5" style="width:200px" min="2021-06" max="3000-09" required>
      <span class="br"></span> 
      
      <div class="form-group mb-3" >
        <span style="white-space: nowrap">
                              <label for='condition' class="main8" style="font-weight: normal;">1st Trainer (Overview): </label> 
                                <select name="firsttrainer" id="firsttrainer" class="form-control1" style="height: 28px;" required>
                                  <option value="" disabled selected></option>
                                  <option value="Geraldine Alvarado">Geraldine Alvarado</option>
                                  <option value="Lady Hannah Despabiladeras">Lady Hannah Despabiladeras</option>
                                  <option value="Claude dela Torre">Claude dela Torre</option>
                                  <option value="Josan Mercado">Josan Mercado</option>
                              </select>
                                  <b class="form-text text-danger" id="ageError"></b>
                              </div> 
      <div class="form-group mb-3" >
        <span style="white-space: nowrap">
                              <label for='condition' class="main1" style="font-weight: normal;">2nd Trainer (Hands-On):</label> 
                                <select name="secondtrainer" id="secondtrainer" class="form-control2" style="height: 28px;" required>
                                    <option value="" disabled selected></option>
                                  <option value="Geraldine Alvarado">Geraldine Alvarado</option>
                                  <option value="Lady Hannah Despabiladeras">Lady Hannah Despabiladeras</option>
                                  <option value="Claude dela Torre">Claude dela Torre</option>
                                  <option value="Josan Mercado">Josan Mercado</option>
                              </select>
                                  <b class="form-text text-danger" id="ageError"></b>
                              </div> 
      <input type="submit" name="add" class="btn btn-primary" value="Submit">
      <input type="button" class="btn btn-danger" id="button2" value="Cancel" onclick="reloadPage()">
      <br>
      <br>
      </div>


  
  
   


      
    </form>
    <div id="myDIV" class="center2">
     <form id="upload_csv" method="post" enctype="multipart/form-data">
    
      <br>
     <h4 style="text-align:center"><strong>Add New Schedule (Batch)</strong></h4>
    
    <br>
    <div class="form-group mb-3" >
        <span style="white-space: nowrap">
                 
                    <label for='condition' class="main1" style="font-weight: normal;">Select CSV file to upload: </label><input type="file" name="csv_file" id="csv_file" accept=".csv"  class="main1" style="padding-left:20px;"/>
                </div>

                
                
                
                    <input type="submit" name="upload" id="upload" value="Upload" style="margin-top:10px;" class="btn btn-primary" />
                    </div>
                 
                <div style="clear:both"></div>
   </form>
   
   <br />
   <div id="csv_file_data"></div>
    

    
    
    

    
    
    
  
  </div>
        
      </div>
      </div>
      
    </div>
  </div>
  
  <br>
  <div>
    <table border="3" class="center" id="customers">
      <thead>
        <th style="text-align:center">Training Date</th>
        <th style="text-align:center">1st Trainer (Overview)</th>
        <th style="text-align:center">2nd Trainer (Hands-On)</th>
        <th><button class onclick="myFunction()" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Add</button></th>
      </thead>
      <tbody>
        <?php
     
        $conn = mysqli_connect("10.250.111.80", "evaluser", 'xc&m9rSCkuXY2', "evaluation");

 
        // Check connection
        if (mysqli_connect_errno())
          {
          echo "Failed to connect to MySQL: " . mysqli_connect_error();
          }

          
          $query=mysqli_query($conn," SELECT * from trainer where trainingdateone < CURRENT_DATE ORDER BY trainingdateone DESC; ");


          while($row=mysqli_fetch_array($query)){

            $date = date("F d, Y",strtotime ($row['trainingdateone']));
             ?>





            <tr>
              <td><?php echo $date; ?></td>
              <td><?php echo $row['firsttrainer']; ?></td>
              <td><?php echo $row['secondtrainer']; ?></td>
              
              <td>
                 <a href="main.php?nid=33&sid=1&rid=4&id=<?php echo $row['id']; ?>">Edit</a>
              
              </td>
            </tr>

        
          


            <?php
          }
        ?>
      </tbody>
    </table>
  </div>
</body>
</body>
</div>
</div>
</div>
</div>
</div>
</html>

<script>
  function myFunction() {
  var x = document.getElementById("myDIV");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    
  }
}
  </script>


  <script>

$(document).ready(function(){
 $('#upload_csv').on('submit', function(event){
  event.preventDefault();
  $.ajax({
   url:"views/cicP3316.php",
   method:"POST",
   data:new FormData(this),
   dataType:'json',
   contentType:false,
   cache:false,
   processData:false,
   success:function(data)
   {
    var html = '<table class="table table-striped table-bordered">';
    //if(data.column)
    //{
       html += '<h5 style="text-align:center"><strong>Preview:</strong><span style="color: #CA0B00"> (You may edit any incorrect date and/or name here before uploading)</span></h5>';  
     html += '<tr>';
     //for(var count = 0; count < data.column.length; count++)
     //{
      html += '<th style="text-align:center">Training Date</th>';
       html += '<th style="text-align:center">1st Trainer (Overview)</th>';
        html += '<th style="text-align:center">  2nd Trainer (Hands-On)</th>';
     //}

     html += '</tr>';

    //}

    if(data.row_data)
    {
     for(var count = 0; count < data.row_data.length; count++)
     {
      html += '<tr>';
      //html += '<td class="trainingdateone" contenteditable>'+data.row_data[count].trainingdateone+'</td>';
      html += '<td class="trainingdateone" contenteditable><input type="date" value="'+data.row_data[count].trainingdateone+'"></td>';
      html += '<td class="firsttrainer" contenteditable>'+data.row_data[count].firsttrainer+'</td>';
      html += '<td class="secondtrainer" contenteditable>'+data.row_data[count].secondtrainer+'</td></tr>';
     }
    }
    html += '<table>';
    html += '<button type="button" id="import_data" class="btn btn-primary" style="width:auto;height:40px;margin-right: 50px;position:relative; left:280px">Upload</button>';
    html += '<button type="button" class="btn btn-danger" id="button2" value="Cancel" onclick="reloadPage()" style="width:auto;height:40px;position:relative; left:283px;">Cancel</button>';

    $('#csv_file_data').html(html);
    $('#upload_csv')[0].reset();
   }
  })
 });

 $(document).on('click', '#import_data', function(){
  var trainingdateone = [];
  var firsttrainer = [];
  var secondtrainer = [];
  $('.trainingdateone').each(function(){
   trainingdateone.push($(this).children().val());
  });
  $('.firsttrainer').each(function(){
   firsttrainer.push($(this).text());
  });
  $('.secondtrainer').each(function(){
   secondtrainer.push($(this).text());
  });
  $.ajax({
   url:"views/cicP3317.php",
   method:"post",
   data:{trainingdateone:trainingdateone, firsttrainer:firsttrainer, secondtrainer:secondtrainer},
   success:function(data)
   {
    $('#csv_file_data').html('<div class="alert alert-success">Data Imported Successfully</div>');
     location.reload();
   }
  })
 });
});

function reloadPage() { location.reload(); }

</script>

  