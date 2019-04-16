<?php

session_name("expensify_user");
session_start();
$baseUrl = "'https://www.expensify.com/api";
$url = 'https://www.expensify.com/api';
date_default_timezone_set('UTC');

if(isset($_POST['partnerName'])){
    $formData = [
        "partnerName" => $_POST["partnerName"],
        "partnerPassword" => $_POST["partnerPassword"],
        "partnerUserID" => $_POST["partnerUserID"],
        "partnerUserSecret" => $_POST["partnerUserSecret"]
    ];
    /* Configure Curl */
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url.'?command=Authenticate');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $formData);

    /* Get Response */
    $response = curl_exec($ch);
    if ($response === false)
        $response = curl_error($ch);
    $obj = json_decode($response);
    if(isset($obj->authToken)) {
        $_SESSION['authToken'] = $obj->authToken;
        $_SESSION['email'] = $obj->email;
        $responseCode = array(2, 'response' => $obj->jsonCode);
    }else{
        $responseCode = array(2, 'response' => $obj->jsonCode, 'message' => $obj->message);
    }
    echo json_encode($responseCode);
    curl_close($ch);
}
if(isset($_POST['merchant'])){
    $formData = [
        "authToken" => $_SESSION["authToken"],
        "created" => date("Y-m-d"),
        "amount" => $_POST["amount"],
        "merchant" => $_POST["merchant"]
    ];

    /* Configure Curl */
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url.'?command=CreateTransaction');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $formData);

    /* Get Response */
    $response = curl_exec($ch);
    if ($response === false)
        $response = curl_error($ch);
    $obj = json_decode($response);
    if(isset($obj->jsonCode)) {
        $responseCode = array(1, 'response' => $obj->jsonCode);
        echo json_encode($responseCode);
    }
    curl_close($ch);
}
?>