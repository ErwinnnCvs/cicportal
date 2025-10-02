<?php

// if ($_POST['is_active']) {
//   // echo "string".$_POST['is_active'][key($_POST['is_active'])];
//   var_dump($_POST['is_active']);
// }


if ($_POST['sbtMaintenanceYes']) {
  $id = $_POST['module_id'];
  $dbh->query("UPDATE tbmenu SET fld_maintenance = '1' WHERE fld_id = '".$id."'");
} elseif ($_POST['sbtMaintenanceNo']) {
  $id = $_POST['module_id'];
  $dbh->query("UPDATE tbmenu SET fld_maintenance = '0' WHERE fld_id = '".$id."'");
}


if ($_POST['sbtAddUserSave']) {
  $get_current_users = $dbh->query("SELECT fld_title, fld_users FROM tbmenu WHERE fld_id = '".$_POST['id_module']."'");
  $gcu=$get_current_users->fetch_array();

  $current_users = trim($gcu['fld_users']);
  // echo "<br>";
  // echo $_POST['id_module'];
  // echo $_POST['user_select'];

  if (empty($current_users)) {
    $new_user = $_POST['user_select']."|";
    // echo $new_user;
    $dbh->query("UPDATE tbmenu SET fld_users = '".$new_user."' WHERE fld_id = '".$_POST['id_module']."'");
  } else {
    $new_user = $current_users.$_POST['user_select']."|";
    // echo $new_user;
    $dbh->query("UPDATE tbmenu SET fld_users = '".$new_user."' WHERE fld_id = '".$_POST['id_module']."'");
  }


}

if ($_POST['removeUser']) {
  $slice = explode("|", $_POST['removeUser']);
  // echo "UPDATE tbmenu SET fld_users = REPLACE(fld_users, '".$slice[0]."|', '') WHERE fld_id = '".$slice[1]."'";
  $dbh->query("UPDATE tbmenu SET fld_users = REPLACE(fld_users, '".$slice[0]."|', '') WHERE fld_id = '".$slice[1]."'");
}

if ($_POST['sbtSaveMainMenu']) {
  $fld_id = $_POST['fld_id'];
  $fld_nid = $_POST['fld_nid'];
  $fld_title = $_POST['title-input'];
  $fld_icon = $_POST['icon-input'];
  $fld_description = $_POST['description-input'];

  if ($dbh->query("INSERT INTO tbmenu (fld_id, fld_nid, fld_sid, fld_rid, fld_title, fld_icon, fld_description, fld_users, fld_published, fld_maintenance) VALUES ('".$fld_id."', '".$fld_nid."', '0', '0', '".$fld_title."', '".$fld_icon."', '".$fld_description."', '76|', '1', 0) ")) {
//     $fp=fopen('views/cicP'.$fld_nid.'00.php','w');
//     fwrite($fp, '<section class="content">

//   <div class="card">
//     <div class="card-header">
//       <h3 class="card-title">Title</h3>
//     </div>
//     <div class="card-body">
//       Start creating your amazing application!
//     </div>
//   </div>

// </section>');
//     fclose($fp);
  }
}

if ($_POST['sbtSelectMenu']) {
  $get_menus = $dbh->query("SELECT * FROM tbmenu WHERE fld_nid = '".$_POST['main_menu_sel']."' ORDER BY fld_id DESC");
  $gm=$get_menus->fetch_array();

  $sid = $gm['fld_sid'];
  $rid = $gm['fld_rid'];

  if ($sid == 0 and $rid == 0) {
    $sid += 1;
    $rid += 1;
  } elseif ($sid > 0) {
    $rid += 1;
  }
  $get_last_id = $dbh->query("SELECT fld_id FROM tbmenu WHERE fld_id < 99 GROUP BY fld_id DESC LIMIT 1");
  $gli=$get_last_id->fetch_array();
  $id = $gli['fld_id'] + 1;
  // echo $sid. " " .$rid;

  if($dbh->query("INSERT INTO tbmenu (fld_id, fld_nid, fld_sid, fld_rid, fld_title, fld_users, fld_published, fld_maintenance) VALUES ('".$id."','".$gm['fld_nid']."', '".$sid."', '".$rid."', '".$gm['fld_title']."', '".$gm['fld_users']."', 1, 0)")){
    // echo getcwd();
//     $fp=fopen('views/cicP'.$gm['fld_nid'].$sid.$rid.'.php','w');
//     fwrite($fp, '<section class="content">

//   <div class="card">
//     <div class="card-header">
//       <h3 class="card-title">Title</h3>
//     </div>
//     <div class="card-body">
//       Start creating your amazing application!
//     </div>
//   </div>

// </section>');
//     fclose($fp);
  }
}

$is_active[1] = "checked";
$isselectedMainMenu[$_POST['sbtSubMenu']] = "selected";
?>
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">List of Modules/Pages</h3>
    </div>
    <div class="card-body">
      <form method="post">
        <?php
          if (!$_POST['sbtAddMenu']) {
        ?>
        <button type="submit" value="1" name="sbtAddMenu" class="btn btn-success">Add Menu</button>
        <?php
          }elseif ($_POST['sbtAddMenu']) {
      ?>
      <div class="row">
        <div class="col-4">
          
        </div>
        <div class="col-4">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Add Menu</h3>
            </div>
            <div class="card-body">
              <input type="hidden" name="sbtAddMenu" value="1">

              <?php
                if (!$_POST['sbtMainMenu'] && !$_POST['sbtSubMenu']) {
              ?>
              <button type="submit" class="btn btn-primary" name="sbtMainMenu" value="1">Main Menu</button>
              <button type="submit" class="btn btn-secondary" name="sbtSubMenu" value="1">Sub Menu</button>
              <?php
                } elseif ($_POST['sbtMainMenu']) {
                  $get_prev_main_menu = $dbh->query("SELECT fld_id, fld_nid FROM tbmenu WHERE fld_id < 99 ORDER BY fld_id DESC LIMIT 1");
                  $gpmm=$get_prev_main_menu->fetch_array();

                  $fld_id = $gpmm['fld_id'] + 1;
                  $fld_nid = $gpmm['fld_nid'] + 1; 
              ?>
              <p>ID : <?php echo $fld_id; ?></p>
              <input type="hidden" name="fld_id" value="<?php echo $fld_id; ?>">
              <p>NID : <?php echo $fld_nid; ?></p>
              <input type="hidden" name="fld_nid" value="<?php echo $fld_nid; ?>">
              <div class="form-group">
                <label for="title-input">Title</label>
                <input type="text" class="form-control" id="title-input" name="title-input" placeholder="Enter Title">
              </div>
              <div class="form-group">
                <label for="icon-input">Icon</label>
                <input type="text" class="form-control" id="icon-input" name="icon-input" placeholder="Enter Icon">
              </div>
              <div class="form-group">
                <label for="description-input">Description</label>
                <input type="text" class="form-control" id="description-input" name="description-input" placeholder="Enter Icon">
              </div>
              <a href="main.php?nid=100&sid=0&rid=0">Cancel</a>
              <button type="submit" class="btn btn-success float-right" value="1" name="sbtSaveMainMenu">Save</button>
              <?php
                } elseif ($_POST['sbtSubMenu']) {
              ?>
              <div class="form-group">
                <label>Main Menu</label>
                <select class="form-control select2" style="width: 100%;" name="main_menu_sel">
                <?php
                  $get_all_main_menu = $dbh->query("SELECT * FROM tbmenu WHERE fld_nid > 0 and fld_sid = 0");
                  while ($gamm=$get_all_main_menu->fetch_array()) {
                ?>
                <option value="<?php echo $gamm['fld_nid']; ?>" <?php echo $isselectedMainMenu[$gamm['fld_nid']]; ?>><?php echo $gamm['fld_title']; ?></option>
                <?php
                  }
                ?>
                </select>
              </div>
              <button type="submit" value="1" class="btn btn-success" name="sbtSelectMenu">Select Menu</button>
<!--               <p>ID : <?php echo $fld_id; ?></p>
              <input type="hidden" name="fld_id" value="<?php echo $fld_id; ?>">
              <p>NID : <?php echo $fld_nid; ?></p>
              <input type="hidden" name="fld_nid" value="<?php echo $fld_nid; ?>"> -->
              <?php
                }
              ?>
            </div>
          </div>
        </div>
        <div class="col-4">
          
        </div>
      </div>
      <?php
          }
        ?>
      </form>
      <br>
      <br>
        <?php 
          if ($_POST['sbtAddUser']) {
            $module_name = $dbh->query("SELECT fld_title, fld_users FROM tbmenu WHERE fld_id = '".$_POST['module_id']."'");
            $mn=$module_name->fetch_array();

            // echo $mn['fld_users'];
            $already_user = explode("|", $mn['fld_users']);
            // var_dump($already_user);
        ?>
        <div class="row">
          <div class="col-lg-4">
            
          </div>
          <div class="col-lg-4">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">User Add to Module '<?php echo $mn['fld_title']; ?>'</h3> 
              </div>
              <div class="card-body">
                <form method="post">
                  <div class="form-group">
                    <label>Select User</label>
                    <input type="hidden" name="id_module" value="<?php echo $_POST['module_id']; ?>">
                    <select class="form-control select2" style="width: 100%;" name="user_select">
                      <option selected="selected" disabled>-----SELECT-----</option>
                      <?php
                        $get_all_users = $dbh->query("SELECT * FROM tbcicusers");
                        while ($gau=$get_all_users->fetch_array()) {
                          if (!in_array($gau['pkUserId'], $already_user)) {
                      ?>
                      <option value="<?php echo $gau['pkUserId']; ?>"><?php echo $gau['fld_name']; ?></option>
                      <?php
                          }
                        }
                      ?>
                    </select>
                  </div>
                <button type="submit" class="btn btn-success" value="1" name="sbtAddUserSave">Save</button>
                </form>
                
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            
          </div>
        </div>
        <?php
          }
        ?>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th><center>#</center></th>
              <th>Module</th>
              <th>User(s)</th>
              <th><center>Active</center></th>
              <th><center>Maintenance</center></th>
            </tr>
          </thead>
          <tbody>
            <?php
              $get_all_menu_pages = $dbh->query("SELECT * FROM tbmenu WHERE fld_nid > 0");
            while ($gamp=$get_all_menu_pages->fetch_array()) {
                $c++;
              $page = $gamp['fld_nid'].$gamp['fld_sid'].$gamp['fld_rid'];
            ?>
            <form method="post">
            <tr>
              <td><center><?php echo $c; ?></center></td>
              <td><?php echo $gamp['fld_title']. " <a href='main.php?nid=".$gamp['fld_nid']."&sid=".$gamp['fld_sid']."&rid=".$gamp['fld_rid']."' target='_blank'>GO TO PAGE</a>"; if($gamp['fld_sid'] > 0) { echo "<br><b>SUB MENU</b>"; } ?></td>
              <td>
                <ul>
                <?php
                  $users = explode("|", trim($gamp['fld_users']));
                  foreach ($users as $key) {
                    if (!empty($key)) {
                      $get_users = $dbh->query("SELECT pkUserId, fld_name FROM tbcicusers WHERE pkUserId = '".$key."'");
                      $gu=$get_users->fetch_array();
                      echo "<li>".$gu['fld_name']." <button type='submit' value='".$gu['pkUserId']."|".$gamp['fld_id']."' name='removeUser'>x</button></li>";
                    }

                  }
                ?>
                <input type="hidden" name="module_id" value="<?php echo $gamp['fld_id']; ?>">
                <button type="submit" class="btn btn-primary" value="1" name="sbtAddUser">Add User</button>
                </ul>
              </td>
              <td>
                <center>
                  <input type="checkbox" name="is_active[]" value="<?php echo $gamp['fld_id']; ?>" <?php echo $is_active[$gamp['fld_published']]; ?>>  
                </center>
              </td>
              <td>
                <center>
                  <?php
                    if ($gamp['fld_maintenance'] == 0) {
                  ?>
                  <input type="hidden" name="module_id" value="<?php echo $gamp['fld_id']; ?>">
                  <button type="submit" value="1" name="sbtMaintenanceYes" class="btn btn-danger"><i class="fa fa-times"></i></button>
                  <?php
                    } else {
                  ?>
                  <input type="hidden" name="module_id" value="<?php echo $gamp['fld_id']; ?>">
                  <button type="submit" value="1" name="sbtMaintenanceNo" class="btn btn-success"><i class="fa fa-check"></i></button>
                  <?php
                    }
                  ?>
                </center>
              </td>
            </tr>
            </form>
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