<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

$dbh7 = new mysqli("10.250.111.80", "evaluser", 'xc&m9rSCkuXY2', "evaluation");

if (!$_POST['sel_training']) {
  $sql_s = $dbh7->query("SELECT * FROM trainer WHERE trainingdateone <= '".date("Y-m-d")."' ORDER BY trainingdateone DESC LIMIT 1");
  $r_s = $sql_s->fetch_array();
  $_POST['sel_training'] = $r_s['trainingdateone'];
}

if ($_POST['btnSwitch'] == '0' || $_POST['btnSwitch'] == '1') {
  $dbh7->query("UPDATE `tbswitchquestion` SET `fld_onoff` = '".$_POST['btnSwitch']."' WHERE `tbswitchquestion`.`fld_training_date` = '".$_POST['sel_training']."'");
}

if ($_POST['btnSwitch2'] == '1') {
  $dbh7->query("UPDATE `tbswitchquestion` SET `fld_onoff2` = '".date("Y-m-d H:i:s")."' WHERE `tbswitchquestion`.`fld_training_date` = '".$_POST['sel_training']."'");
}

if ($_POST['btnSaveAnswer'] == '1') {
  $stmt = $dbh7->prepare("UPDATE `tbswitchquestion` SET `fld_question1_text` = ?, `fld_question2_text` = ?, `fld_answer1_text` = ?, `fld_answer2_text` = ? WHERE `tbswitchquestion`.`fld_training_date` = '".$_POST['sel_training']."'");
  $stmt->bind_param("ssss", strtoupper($_POST['question1']), strtoupper($_POST['question2']), strtoupper($_POST['answer1']), strtoupper($_POST['answer2']));
  $stmt->execute();
}

?>
<!-- Main content -->
    <section class="content">
      <!-- Buttons -->
        <?php

        $sql_check = $dbh7->query("SELECT * FROM trainer WHERE trainingdateone = '".$_POST['sel_training']."'");
        if ($r_check =  $sql_check->fetch_array()) {
          $sql = $dbh7->query("SELECT * FROM tbswitchquestion WHERE fld_training_date = '".$r_check['trainingdateone']."'");
          if (!$r = $sql->fetch_array()) {
            $dbh7->query("INSERT INTO tbswitchquestion (fld_training_date) VALUES ('".$r_check['trainingdateone']."')");
          }
        }

        $sql = $dbh7->query("SELECT * FROM tbswitchquestion WHERE fld_training_date = '".$_POST['sel_training']."'");
        
        if ($r = $sql->fetch_array()) {
          if ($r['fld_onoff'] == 0) {
            $switch = 1;
            $switch_txt = 'Open';
            $switch_class = 'primary';
            $switch_status = '<p style="color: red;">The questionnaire is currently close.</p>';
          }else{
            $switch = 0;
            $switch_txt = 'Close';
            $switch_class = 'danger';
            $switch_status = '<p style="color: green;">The questionnaire is currently open.</p>';
          }
        }
        

        
        ?>
    <div class="row vertical-center">
      <div class="col-md-3">
        <a href="main.php?nid=33&sid=0&rid=0&cid=1">&nbsp;&nbsp;&nbsp;Go Back</a>
      </div>
      <div class="col-md-6">
        <div class="card">
            
            <div class="card-body">
              <form method="POST">
                <?php
                echo $switch_status;
                ?>
      <div class="form-group row">
        
        <label for="staticEmail" class="col-sm-3 col-form-label">Training Date</label>
        <div class="col-sm-9">
          <!-- <?php
          if ($r['fld_training_date']) {
            $btn_disabled = '';
          ?>
          <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo date("F j, Y", strtotime($r['fld_training_date']));?>">
          <?php
          }else{
            $btn_disabled = ' disabled';
          ?>
          No Scheduled Training for today
          <?php
          }
          ?> -->
          
          <select class="form-control" id="exampleFormControlSelect1" name="sel_training" onchange="submit()">
            <?php
            $sql_t = $dbh7->query("SELECT * FROM trainer ORDER BY trainingdateone DESC");
            while ($r_t = $sql_t->fetch_array()) {
              $sel_disabled = '';
              if ($r_t['trainingdateone'] > date("Y-m-d")) {
                $sel_disabled = ' disabled';
              }
              $sel[date("Ymd", strtotime($_POST['sel_training']))] = ' selected';
            ?>
            <option value="<?php echo $r_t['trainingdateone'];?>"<?php echo $sel[date("Ymd", strtotime($r_t['trainingdateone']))].$sel_disabled;?>><?php echo date("Y F j", strtotime($r_t['trainingdateone']));?></option>
            <?php
            }
            ?>
          </select>
          
        </div>
      </div>
      <?php
      if ($r['fld_training_date']) {
      ?>
      <div class="form-group row">
        <label for="inputPassword" class="col-sm-3 col-form-label">Questionnaire</label>
        <div class="col-sm-9">
          
          <?php
          if (!$r['fld_answer1_text'] && !$r['fld_answer2_text']) {
          ?>
          <div class="form-group">
            <input type="text" name="question1" class="form-control" placeholder="Question" required>
          </div>
          <div class="form-group">
            <input type="text" name="answer1" class="form-control" placeholder="Answer" required>
          </div>
          <!-- <div class="form-group">
            <input type="text" name="question2" class="form-control" placeholder="Question 2" required>
          </div>
          <div class="form-group">
            <input type="text" name="answer2" class="form-control" placeholder="Answer 2" required>
          </div> -->
          <button name="btnSaveAnswer" type="submit" class="btn btn-success mb-2" value="1" style="padding-left: 70px;padding-right: 70px;">Save Questionnaire</button>
          <?php
          }else{
          ?>
          <div class="form-group">
            <?php echo '<b>Question: </b> '.$r['fld_question1_text'];?>
          </div>
          <div class="form-group">
            <?php echo '<b>Answer: </b> '.$r['fld_answer1_text'];?>
          </div>
          
          
          <button name="btnSwitch" type="submit" class="btn btn-<?php echo $switch_class;?> mb-2" value="<?php echo $switch;?>" style="padding-left: 70px;padding-right: 70px;"<?php echo $btn_disabled;?>><?php echo $switch_txt;?></button>
          <?php
          }
          ?>
          
        </div>
      </div>
      <div class="form-group row">
        <label for="inputPassword" class="col-sm-3 col-form-label">Evaluation</label>
        <div class="col-sm-9">
          <?php
          if (!$r['fld_onoff2'] || $r['fld_onoff2'] == '0000-00-00 00:00:00') {
            $switch2 = 1;
            $switch2_txt = 'Open';
            $switch2_class = 'primary';
            $switch2_status = '<p style="color: red;">The evaluation is currently close.</p>';
          ?>
          <button name="btnSwitch2" type="submit" class="btn btn-<?php echo $switch2_class;?> mb-2" value="<?php echo $switch2;?>" style="padding-left: 70px;padding-right: 70px;"<?php echo $btn_disabled;?>><?php echo $switch2_txt;?></button>
          <?php
          }else{
            echo 'Activated';
          }
          ?>
          
        </div>
      </div>
      <?php
      }
      ?>
      
    </form>
            </div>
        </div>

        

      </div>
      <div class="col-md-3"></div>
    </div>




      <!-- List -->
        <?php


        // $sql = $dbh7->query("SELECT * FROM tbswitchquestion WHERE fld_training_date = '".date("Y-m-d")."'");
        // $r = $sql->fetch_array();
        // if ($r['fld_onoff'] == 0) {
        //   $switch = 1;
        //   $switch_txt = 'Open';
        //   $switch_class = 'primary';
        //   $switch_status = '<p style="color: red;">The questionnaire is currently close.</p>';
        // }else{
        //   $switch = 0;
        //   $switch_txt = 'Close';
        //   $switch_class = 'danger';
        //   $switch_status = '<p style="color: green;">The questionnaire is currently open.</p>';
        // }

        
        ?>
    <br/>
    <div class="row vertical-center">
      <!-- <div class="col-md-3"></div> -->
      <div class="col-md-12">
        <div class="card">
            
            <div class="card-body">
              <h3>List of <?php echo date("F j, Y", strtotime($_POST['sel_training']));?> Participants</h3>
              <table id="tbevaluation2" class="table table-bordered table-striped" style="table-layout: fixed; width: 100%;">
              <thead>
                <tr>
                  <th width="50px" class="no-sort"></th>
                  <th>Name</th>
                  <th width="">Questionnaire link sent</th>
                  <th>Question</th>
                  
                  <th width="">Evaluation link sent</th>
                </tr>
              </thead>
              <tbody>
                <?php
                // $sql = $dbh7->query("SELECT fld_answer1, fld_answer2, AES_DECRYPT(firstname, CONCAT(id, 'PLANNER')) AS firstname, AES_DECRYPT(surname, CONCAT(id, 'PLANNER')) AS surname, fld_survey_link_sent FROM demo WHERE trainingdate = '".date("Y-m-d")."' AND AES_DECRYPT(email, CONCAT(id, 'PLANNER')) NOT LIKE '%creditinfo%' ORDER BY AES_DECRYPT(firstname, CONCAT(id, 'PLANNER'));");
                $sql = $dbh7->query("SELECT fld_answer1, fld_answer2, AES_DECRYPT(firstname, CONCAT(id, 'PLANNER')) AS firstname, AES_DECRYPT(surname, CONCAT(id, 'PLANNER')) AS surname, fld_survey_link_sent, fld_questionnaire_link_sent FROM demo WHERE trainingdate = '".$_POST['sel_training']."' ORDER BY AES_DECRYPT(firstname, CONCAT(id, 'PLANNER'));");
                while ($r = $sql->fetch_array()) {
                  // echo $r['firstname'].'<br/>';
                  $ctr++;
                ?>
                <tr>
                  <td><?php echo $ctr;?></td>
                  <td><?php echo $r['firstname'].' '.$r['surname'];?></td>
                  <td><?php echo $r['fld_questionnaire_link_sent']? date("M j, Y g:i a", strtotime($r['fld_questionnaire_link_sent'])): '';?></td>
                  <td><?php echo $r['fld_answer1']? date("M j, Y g:i a", strtotime($r['fld_answer1'])): '';?></td>
                  <td><?php echo $r['fld_survey_link_sent']? date("M j, Y g:i a", strtotime($r['fld_survey_link_sent'])): '';?></td>
                </tr>
                <?php  
                }
                ?>
                
              </tbody>
            </table>
            </div>
        </div>

        

      </div>
      <!-- <div class="col-md-3"></div> -->
    </div>

    </section>
    <!-- /.content -->

