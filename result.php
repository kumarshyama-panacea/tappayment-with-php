<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


include(dirname(__FILE__).'/goShellAPI.php');

$objGS = new goShellAPI();
//Customer Details
$Mobile = "98989898";		//Customer Mobile Number
$CstName = "Test Name";		//Customer Name
$Email = "98989898";		//Customer Email Address

//Merchant Details
$ref = "45445457007";		//Your ReferenceID or Order ID
$ReturnURL = "http://182.72.79.154/gosheel/result.php";  //After Payment success, customer will be redirected to this url
$PostURL = "http://182.72.79.154/gosheel/result.php";  //After Payment success, Payment Data's will be posted to this url
$amount = '10.000';

$arrInputs = array (
    'Email' => $Email,
     'Mobile' => $Mobile,
     'Name' => $CstName,
    'Orderid' => $ref,
    'Returnurl' => $ReturnURL,
    'PostUrl' => $PostURL,
    'total_amount' => $amount
            
);
//print_r($arrInputs);exit;
$res = $objGS->doPayment($arrInputs);
