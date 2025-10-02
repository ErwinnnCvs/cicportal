<?php
// trigger_curl.php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    print_r($_POST);
    // die();

    // Get the referring page
    $lastPage = 'https://www.creditinfo.gov.ph/mycic/main.php?nid=120&sid=0&rid=0&submit=success' ?? 'https://www.creditinfo.gov.ph/mycic/main.php?nid=120&sid=0&rid=0'; // Fallback if HTTP_REFERER is not set


    // Initialize cURL session
    $url = 'http://10.250.100.165/cicportal/Phpmailer/mailbcpp_ar_manual.php';
    $curl = curl_init($url);

    $ctrlno = $_POST['controlnoaeis'];

    // Set cURL options
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Get the response
    curl_setopt($curl, CURLOPT_POST, true); // Set method to POST
    curl_setopt($curl, CURLOPT_POSTFIELDS, ['ctrlno' => $ctrlno]); // POST data

    // Execute cURL and capture response
    $response = curl_exec($curl);

    // Check for errors
    if (curl_errno($curl)) {
        echo 'cURL Error: ' . curl_error($curl);
    } else {
        echo 'cURL Response: ' . htmlspecialchars($response);
    }

    // Close cURL session
    curl_close($curl);

     // Redirect back to the last page
    header("Location: $lastPage");
    exit;
}
