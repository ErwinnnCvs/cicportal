<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

$controlNo = '';
if (isset($_POST['ctrlno']) && !empty($_POST['ctrlno'])) {
    $controlNo = $_POST['ctrlno'];
} elseif (isset($_GET['ctrlno']) && !empty($_GET['ctrlno'])) {
    $controlNo = $_GET['ctrlno'];
}

if (empty($controlNo)) {
    echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Error: No control number provided.</div>';
    echo '<a href="main.php?nid=109&sid=0&rid=0" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Go Back</a>';
    return;
}

$controlNo = mysqli_real_escape_string($dbh4, $controlNo);

if ($_POST['sbtGenerate']) {
    $check_operators = $dbh4->query("SELECT COUNT(*) as count FROM tboperators WHERE fld_ctrlno = '".$controlNo."' and fld_batch = 1 and fld_delete = 0");
    $operator_count = $check_operators->fetch_array();
    
    if ($operator_count['count'] == 0) {
        echo "<script>alert('No batch operators found for this entity.'); window.location.href='main.php?nid=109&sid=0&rid=0';</script>";
        exit;
    }
    
    $key = $controlNo."RA3019";
    $get_details = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_provcode, AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_name, fld_mnemonics FROM tbentities WHERE fld_ctrlno = '".$controlNo."'");
    $gd=$get_details->fetch_array();

    if (!$gd) {
        echo "<script>alert('Entity not found.'); window.location.href='main.php?nid=109&sid=0&rid=0';</script>";
        exit;
    }

    $mnemonic = $gd['fld_mnemonics'];
    $provcode = $gd['fld_provcode'];
    $company = $gd['fld_name'];

    $genCsv=$dbh4->query("SELECT fld_oid, AES_DECRYPT(fld_fname, md5('".$key."')) AS fld_fname, AES_DECRYPT(fld_mname, md5('".$key."')) fld_mname, AES_DECRYPT(fld_lname, md5('".$key."')) fld_lname, AES_DECRYPT(fld_email, md5('".$key."')) fld_email, fld_update, fld_delete FROM tboperators WHERE fld_ctrlno = '".$controlNo."' and fld_batch = 1 and fld_delete = 0");

    $timestamp = date("Y-m-d H-i-s");
    $filename = 'nocusers/seis/SEIS-Users_'.$timestamp.'.csv';

    if(file_exists($filename)){
        unlink($filename);
    }

    $fp = fopen($filename, 'w');
    $placed_header = false;
    while($gcsv = mysqli_fetch_array($genCsv, MYSQLI_ASSOC)) {
        $password = bin2hex(random_bytes(6));
        $middle = $gcsv['fld_mname'] ?: 'N';
        $fname = substr($gcsv['fld_fname'], 0, 1);
        $mname = $gcsv['fld_mname'] ? substr($gcsv['fld_mname'], 0, 1) : 'N';
        $lname = substr($gcsv['fld_lname'], 0, 1);
        $username = $mnemonic."4".$fname.$mname.$lname;

        $arr = [$mnemonic, $provcode, $username, $password, $gcsv['fld_fname'], $middle, $gcsv['fld_lname'], $gcsv['fld_email'], 'FTP / Production', $company];
        $head = ['Meme', 'ProviderCode', 'SamAccount', 'Password', 'Fname', 'Initial', 'LName', 'Email', 'Channel / Environment', 'Company Name'];
        
        if(!$placed_header) {
            fputcsv($fp, $head);
            $placed_header = true;
        }

        fputcsv($fp, array_values($arr));
        $timestamp = date("Y-m-d H:i:s");
       
        $dbh4->query("UPDATE tboperators SET fld_update = 0 WHERE fld_oid = '".$gcsv['fld_oid']."' and fld_update = 2");
    }

    fclose($fp);
    
    $timestamp = date("Y-m-d H:i:s");
    $dbh4->query("UPDATE tbentities SET fld_seis_noc_status = 1, fld_seis_noc_ts = '".$timestamp."', fld_seis_noc_by = '".$_SESSION['name']."', fld_noc_pass_status = 1, fld_batops_update = 0 WHERE fld_ctrlno = '".$controlNo."'");
    
    echo "<script>alert('Credentials generated successfully!'); window.location.href='main.php?nid=109&sid=0&rid=0';</script>";
}
?>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title">Control Number: <?php echo $controlNo; ?></h3>
                    <a href="main.php?nid=109&sid=0&rid=0" class="btn btn-secondary ml-auto">Back</a>
                </div>
                <div class="card-body">
                    <?php 
                        $sql = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_provcode, AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_name, fld_mnemonics, fld_type FROM tbentities WHERE fld_ctrlno = '".$controlNo."'");
                        $row=$sql->fetch_array();
                    ?>
                    <div class="row">
                        <div class="col-md-3">
                            <label>Name of Accessing Entity</label><br>
                            <p><?php echo $row['fld_name']; ?></p>
                        </div>
                        <div class="col-md-3">
                            <label>Accessing Entity Type</label><br>
                            <p><?php echo $ent2[$row['fld_type']]; ?></p>
                        </div>
                        <div class="col-md-3">
                            <label>MNEMONIC</label><br>
                            <p><?php echo $row['fld_mnemonics']; ?></p>
                        </div>    
                    </div>
                    <form method="post">
                    <input type="hidden" name="ctrlno" value="<?php echo $controlNo; ?>">
                    <h5>BATCH OPERATORS</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><center>#</center></th>
                                <th><center>ID</center></th>
                                <th><center>First name</center></th>
                                <th><center>Middle Name</center></th>
                                <th><center>Last Name</center></th>
                                <th><center>Emails</center></th>
                                <th><center>Status</center></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $c = 0;
                                $key = $controlNo."RA3019";
                                $sqlrows=$dbh4->query("SELECT fld_oid, AES_DECRYPT(fld_fname, md5('".$key."')) AS fld_fname, AES_DECRYPT(fld_mname, md5('".$key."')) fld_mname, AES_DECRYPT(fld_lname, md5('".$key."')) fld_lname, AES_DECRYPT(fld_email, md5('".$key."')) fld_email, fld_update, fld_delete FROM tboperators WHERE fld_ctrlno = '".$controlNo."' and fld_batch = 1");
                                
                                if ($sqlrows && mysqli_num_rows($sqlrows) > 0) {
                                    while ($rowssql=$sqlrows->fetch_array()) {
                                        $c++;
                            ?>
                            <tr>
                                <td><center><?php echo $c; ?></center></td>
                                <td><center><?php echo $rowssql['fld_oid']; ?></center></td>
                                <td><center><?php echo $rowssql['fld_delete'] == 1 ? "<strike>".$rowssql['fld_fname']."</strike>" : $rowssql['fld_fname']; ?></center></td>
                                <td><center><?php echo $rowssql['fld_delete'] == 1 ? "<strike>".$rowssql['fld_mname']."</strike>" : $rowssql['fld_mname']; ?></center></td>
                                <td><center><?php echo $rowssql['fld_delete'] == 1 ? "<strike>".$rowssql['fld_lname']."</strike>" : $rowssql['fld_lname']; ?></center></td>
                                <td><center><?php echo $rowssql['fld_delete'] == 1 ? "<strike>".$rowssql['fld_email']."</strike>" : $rowssql['fld_email']; ?></center></td>
                                <td><center><?php 
                                    if($rowssql['fld_delete'] == 0) { 
                                        if($rowssql['fld_update'] == 1) { 
                                            echo "UPDATE"; 
                                        } elseif($rowssql['fld_update'] == 2) { 
                                            echo "<p style='color:green;'>New</p>"; 
                                        } else { 
                                            echo "<p style='color:aqua;'>Active</p>"; 
                                        } 
                                    } else { 
                                        echo "<p style='color:red;'>Inactive</p>"; 
                                    }
                                ?></center></td>
                            </tr>
                            <?php
                                    }
                                } else {
                            ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    <i class="fa fa-info-circle"></i> No batch operators found for this entity.
                                </td>
                            </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                    <br>
                    <div class="text-right pb-2">
                        <button type="submit" class="btn btn-info text-right" name="sbtGenerate" value="1">Generate</button> 
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>