<?php 
date_default_timezone_set("Asia/Manila");

if(isset($_REQUEST))
{
	$contracts = json_decode(json_encode(json_decode($_POST['contracts'])), true);

    

    // echo $contracts;
    $fp = fopen('../files/csv/contracts/'.$_POST['datetime'].'.csv', 'w');
    $placed_header = false;
    foreach($contracts as $c=>$v){
        $arr = array($v['MONTHYEAR'], $c,$v['ENTITY_TYPE'],$v['TYPE'],$v['CONTRACT_TYPE'],$v['CONTRACT_PHASE'],$v['CREDIT_LIMIT'],$v['LOAN_AMOUNT'],$v['COUNT'],$v['MEAN'],$v['MEDIAN']);
        $head = array('MONTH YEAR', 'Provider Code', 'Entity Type', 'Type', 'Contract Type', 'Contract Phase', 'Credit Limit / Financed Amount', 'Loan Application Amount', 'Count', 'Mean', 'Median');

        if(!$placed_header) {
            fputcsv($fp, $head);
            $placed_header = true;
        }

        // place row of data 
        fputcsv($fp, array_values($arr));
    }

    fclose($fp);
    echo $_POST['datetime'].".csv";



}
?>