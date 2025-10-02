<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
	if ($_POST['sbtDeactivate']) {
		$dbh->query("UPDATE tbcicusers SET is_active = 0 WHERE pkUserId = '".$_POST['sbtDeactivate']."'");
	} elseif ($_POST['sbtReactivate']) {
		$dbh->query("UPDATE tbcicusers SET is_active = 1 WHERE pkUserId = '".$_POST['sbtReactivate']."'");
	}


	if($_POST['sbtConfAddUser']){

		function randomString($length = 50)
		{
			$characters = '0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$string = '';

			for ($p = 0; $p < $length; $p++) {
				$string .= $characters[mt_rand(0, strlen($characters) - 1)];
			}

			return $string;
		}
		if($_POST['email'] && $_POST['name']){
			$sql=$dbh->query("SELECT * FROM tbcicusers WHERE email = '".$_POST['email']."'");
			if($ab=$sql->fetch_array()){
				$msg = $ab['email']." is already in the database";
				$msgclr = "warning";
			}else{
				if($auth->createUser($_POST['email'], randomString(8), $_POST['name'], $_POST['fld_accesstype'], $is_admin = 0)){
					$msg = "User Added. Email sent to user.";
					$msgclr = "success";
				}
			}
		}else{
			$msg = "Incomplete details.";
			$msgclr = "danger";
		}
	}
?>
<section class="content">
	<?php
		if ($msg) {
	?>
	<div class="alert alert-<?php echo $msgclr; ?> alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <?php echo $msg; ?>
    </div>
	<?php
		}
	?>
	<!-- Default box -->
	<div class="card">
	<div class="card-header">
	  <h3 class="card-title">List of Users</h3>

	  <div class="card-tools">
	    <button type="button" class="btn btn-tool" data-toggle="modal" data-target="#modal-default">
	      <i class="fas fa-plus"></i> Add User
	    </button>
	    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Enter User</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="post">
	            <div class="modal-body">
	            	<div class="form-group">
	                    <label for="email">Email address</label>
	                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter email">
	                 </div>
	                 <div class="form-group">
	                    <label for="name">Name</label>
	                    <input type="text" class="form-control" name="name" id="name" placeholder="Name">
	                 </div>
	                 <div class="form-group">
	                    <label for="exampleInputFile">Image</label>
	                    <div class="input-group">
	                      <div class="custom-file">
	                        <input type="file" class="custom-file-input" id="exampleInputFile">
	                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
	                      </div>
	                      <div class="input-group-append">
	                        <span class="input-group-text">Upload</span>
	                      </div>
	                    </div>
	                  </div>
	                  <div class="form-group">
		                  <label for="exampleSelectBorder">Group</label>
		                  <select class="custom-select form-control-border" id="exampleSelectBorder" name="fld_accesstype">
		                    <option selected disabled>----</option>
		                    <?php
		                    		$c = 0;
		                    	foreach ($user_position as $key) {
		                    		echo "<option value='".$c."'>".$key."</option>";
		                    		$c++;
		                    	}
		                    ?>
		                  </select>
		                </div>
	            </div>
	            <div class="modal-footer justify-content-between">
	              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	              <button type="submit" name="sbtConfAddUser" value="1" class="btn btn-primary">Save changes</button>
	            </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
	  </div>
	</div>
	<div class="card-body p-0">
	  <table class="table table-striped projects">
	      <thead>
	          <tr>
	              <th style="width: 1%">
	                  #
	              </th>
	              <th style="width: 10%">
	              </th>
	              <th style="width: 20%">
	                  User
	              </th>
	              <th style="width: 30%">
	                  Modules/Access
	              </th>
	              <th style="width: 8%" class="text-center">
                      Status
                  </th>
	              <th style="width: 20%">
	              </th>
	          </tr>
	      </thead>
	      <tbody>
	      	  <?php
	      	  	$get_all_users = $dbh->query("SELECT * FROM tbcicusers");
	      	  	while ($gau=$get_all_users->fetch_array()) {
	      	  		$c++;
	      	  ?>

	          <tr>
	              <td>
	                  <?php echo $c; ?>
	              </td>
	              <td class="project_progress">
	              	<center>	
	                  	<img alt="Avatar" class="profile-user-img img-fluid img-circle" src="dist/img/employees-2x2/<?php echo $gau['fld_image']; ?>">
	              	</center>
	              </td>
	              <td>
	                  <a>
	                      <?php echo $gau['fld_name']; ?>
	                  </a>
	                  <!-- <br/>
	                  <small>
	                      Created 01.01.2019
	                  </small> -->
	              </td>
	              <td>
	                  <ul class="list-inline">
	                  	<?php
	                  		$get_user_modules = $dbh->query("SELECT * FROM tbmenu WHERE fld_sid = 0");
	                  		while ($gum=$get_user_modules->fetch_array()) {
	                  			$check_users = explode("|", $gum['fld_users']);
	                  		if (in_array($gau['pkUserId'], $check_users)) {
	                  			if ($gum['fld_published'] == 1) {
	                  				$bdgclr = "success";
	                  			} else {
	                  				$bdgclr = "danger";
	                  			}
	                  	?>		
	                  	<li class="list-inline-item">
	                          <!-- <img alt="Avatar" class="table-avatar" src="../../dist/img/avatar.png"> -->
	                          
	                          <span class="badge badge-<?php echo $bdgclr; ?>"><?php echo $gum['fld_title']; ?></span>
	                     </li>
	                  	<?php
	                  			}
	                  		}
	                  	?>
	                      <!-- <li class="list-inline-item">
	                          <img alt="Avatar" class="table-avatar" src="../../dist/img/avatar.png">
	                      </li>
	                      <li class="list-inline-item">
	                          <img alt="Avatar" class="table-avatar" src="../../dist/img/avatar2.png">
	                      </li>
	                      <li class="list-inline-item">
	                          <img alt="Avatar" class="table-avatar" src="../../dist/img/avatar3.png">
	                      </li>
	                      <li class="list-inline-item">
	                          <img alt="Avatar" class="table-avatar" src="../../dist/img/avatar4.png">
	                      </li> -->
	                  </ul>
	              </td>
	              <td class="project-state">
	              	  <?php
	              	  	if ($gau['is_active'] == 1) {
	              	  ?>
	                  <span class="badge badge-success">Active</span>
	              	  <?php
	              	  	} else {
	              	  ?>
	              	  <span class="badge badge-warning">Inactive</span>
	              	  <?php
	              	  	}
	              	  ?>
	              </td>
	              <td class="project-actions text-right">
	                <!--   <a class="btn btn-primary btn-sm" href="#">
	                      <i class="fas fa-folder">
	                      </i>
	                      View
	                  </a> -->
	                  <a class="btn btn-info btn-sm" href="main.php?nid=101&sid=1&rid=1&id=<?php echo $gau['pkUserId']; ?>">
	                      <i class="fas fa-pencil-alt">
	                      </i>
	                      Edit
	                  </a>
	                  <form method="post">
	                  	  <?php
	                  	  	if ($gau['is_active'] == 1) {
	                  	  ?>
		                  <button type="submit" class="btn btn-danger btn-sm" name="sbtDeactivate" value="<?php echo $gau['pkUserId']; ?>">
		                      <i class="fas fa-trash">
		                      </i>
		                      Deactivate
		                  </button>
	                  	  <?php
	                  	  	} else {
	                  	  ?>
	                  	  <button type="submit" class="btn btn-warning btn-sm" name="sbtReactivate" value="<?php echo $gau['pkUserId']; ?>">
		                      <i class="fas fa-check">
		                      </i>
		                      Reactivate
		                  </button>
	                  	  <?php
	                  	  	}
	                  	  ?>
	                  </form>
	              </td>
	          </tr>
	      	  <?php
	      	  	}
	      	  ?>
	      </tbody>
	  </table>
	</div>
	<!-- /.card-body -->
	</div>
	<!-- /.card -->

	</section>
	<!-- /.content -->