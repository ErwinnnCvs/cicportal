<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
require_once('classes/Auth.class.php');

$auth = new Auth();

$sae_codes = array("2019090002|SAE09670"=>"CIBI Information, Inc.", "2019120008|SAE09440"=>"CRIF Corporation", "2020040001|SAE09450"=>"TransUnion Information Solutions Inc.", "2024080012|SAE09460"=>"Island Credit Solutions Corporation", "2024080011|SAE09470"=>"JuanScore Corporation", "2024080010|SAE09480"=>"Advintel, Inc.");

if ($_POST['sbtSearch']) {
    $name = trim($_POST['search_name']);
    $email = trim($_POST['search_email']);


    if (!empty($name)) {
        $srchwhere = "fld_name LIKE '%".addslashes($name)."%'";
    }

    if (!empty($email)) {
        $srchwhere = "email LIKE '%".addslashes($email)."%'";
    }

    $query = "SELECT * FROM tbsaeusers WHERE ".$srchwhere;

    echo $query;

} else {
    $query = "SELECT * FROM tbsaeusers WHERE is_admin <> 1";
}

function randomString($length = 12)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $string = '';

    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters) - 1)];
    }

    return $string;
}

if($_POST['addUser']){

    $expl_code = explode("|", $_POST['sae_select']);

    $user_ctrlno = $expl_code[0];
    $user_provcode = strtoupper($expl_code[1]);
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $password = randomString();
    // echo $user_ctrlno. " - " .$user_provcode;
    if($auth->createSAEUser($user_email, $password, $user_name, $user_ctrlno, $user_provcode)){
        $msg = "User successfully added";
    } else {
        $error = "Error adding the user";
    }
}

if($_POST['enableBTN']){
    $user_id = $_POST['enableBTN'];
    $dbh->query("UPDATE tbsaeusers SET is_active = 1 WHERE pkUserId = '".$user_id."'");
}

if($_POST['disableBTN']){
    $user_id = $_POST['disableBTN'];
    
    $dbh->query("UPDATE tbsaeusers SET is_active = 0 WHERE pkUserId = '".$user_id."'");
}

?>

<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Users</h3>
    </div>
    <div class="card-body">
    <div class="row">
                            <div class="col">
                                <form method="post">
                                    <div class="form-row align-items-center">
                                        <div class="col-sm-4 my-1">
                                            <!-- <label class="sr-only" for="search_name">TRN</label> -->
                                            <input type="text" class="form-control" name="search_name" id="search_name" placeholder="Search Name" value="<?php echo $_POST['search_name']; ?>">
                                        </div>
                                        <div class="col-sm-4 my-1">
                                            <!-- <label class="sr-only" for="search_email">Email</label> -->
                                            <input type="text" class="form-control" name="search_email" id="search_email" placeholder="Search Email" value="<?php echo $_POST['search_email']; ?>">
                                        </div>
                                        <div class="col-auto my-1">
                                            <button type="submit" name="sbtSearch" value="1" class="btn btn-primary">Search</button>
                                        </div>
                                        <div class="col-auto my-1">
                                            <a href=<?php echo "main.php?nid=118&sid=0&rid=0"; ?> class="btn btn-secondary">Clear</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-success btn-flat pull-right" data-toggle="modal" data-target=".bd-example-modal-sm"><i class="fa fa-user"></i> ADD USER</button>
                                <div class="modal fade bd-example-modal-sm" style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Add User</h5>
                                                <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post">
                                                    <div class="dropdown">
                                                        <select class="form-control" name="sae_select">
                                                            <option selected disabled>---- SELECT ----</option>
                                                            <?php
                                                                foreach ($sae_codes as $key => $value) {
                                                                    echo "<option value='".$key."'>".$value."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <!-- <div>
                                                        <input type="text" class="form-control" name="user_ctrlno" id="user_ctrlno" placeholder="Control No." required>
                                                    </div>
                                                    <br>
                                                    <div>
                                                        <input type="text" class="form-control" name="user_provcode" id="user_provcode" placeholder="Provider Code" required>
                                                    </div> -->
                                                    <br>
                                                    <div>
                                                        <input type="text" class="form-control" name="user_name" id="user_name" placeholder="Name" required>
                                                    </div>
                                                    <br>
                                                    <div>
                                                        <input type="email" class="form-control" name="user_email" id="user_email" placeholder="Email" required>
                                                    </div>
                                                    <br>
                                                    <div>
                                                        <button type="submit" class="btn btn-success" value="1" name="addUser">Submit</button>
                                                    </div>
                                                    
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            
                        </div>

      <?php
        if ($msg) {
      ?>
      <div class="alert alert-primary" role="alert">
        <?php echo $msg; ?>
      </div>
      <?php
        }
      ?>


      <?php
        if ($error) {
      ?>
      <div class="alert alert-danger" role="alert">
        <?php echo $error; ?>
      </div>
      <?php
        }
      ?>
      <table class="table">
        <thead>
            <tr>
            <th>#</th>
            <th>Control No.</th>
            <th>Provider Code</th>
            <th>Special Accessing Entity</th>
            <th>User</th>
            <th><center>Status</center></th>
            <th><center>Action</center></th>
            </tr>
        </thead>
        <tbody>
        <?php
        $c = 0;
            $get_all_users = $dbh->query($query);
            while($gau=$get_all_users->fetch_array()){
                $c++;
        ?>
        <tr>
            <td><?php echo $c; ?></td>
            <td><?php echo $gau['fld_ctrlno']; ?></td>
            <td><?php echo $gau['fld_provcode']; ?></td>
            <td><?php echo $sae_codes[$gau['fld_ctrlno']."|".$gau['fld_provcode']]; ?></td>
            <td><?php echo $gau['fld_name']; ?></td>
            <td>
                <center>
                    <?php
                        if($gau['is_active'] == 0){
                    ?>
                    <label for="" class="text-danger">Deactivated</label>
                    <?php
                        } elseif($gau['is_active'] == 1){
                    ?>
                    <label for="" class="text-success">Active</label>
                    <?php
                        }
                    ?>
                </center>
            </td>
            <td>
                <center>
                    <form method="post">
                    <?php
                        if($gau['is_active'] == 0){
                    ?>
                    <button type="submit" value="<?php echo $gau['pkUserId']; ?>" name="enableBTN" class="btn btn-success">Enable</button>
                    <?php
                        } elseif($gau['is_active'] == 1){
                    ?>
                    <button type="submit" value="<?php echo $gau['pkUserId']; ?>" name="disableBTN" class="btn btn-danger">Disable</button>
                    <?php
                        }
                    ?>
                    </form>
                </center>
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