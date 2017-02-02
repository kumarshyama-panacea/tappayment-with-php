<?php

/*
 * Library Name: goShell Payment Gateway API Integration
 * Description:  This library helps developer to connect goshell payment gateway APi Integration for the website.
 * Author: KumarShyama
 * Website: https://shyamapadabatabyal.wordpress.com/
 * Version: 1.0
 */

if (DEBUG == 1) {
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    error_reporting(E_ALL);
    ini_set("error_reporting", E_ALL);
    error_reporting(E_ALL & ~E_NOTICE);
    ini_set('display_errors', '1');
}

include(dirname(__FILE__) . '/configuration.php');

class goShellAPI {
    /* The API Key, Secret, & URL will be used in every function. */

    private $merchant_id = MERCHANT_ID;
    private $username = USER_NAME;
    private $api_key = API_KEY;
    private $webservices_url = WEB_SERVICE_URL;

    //private $request;
    // Function to send HTTP POST Requests Used by every function below to make HTTP POST call

    function sendRequest($arrInputs, $action) {
        $apiheader = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "Content-length: " . strlen($arrInputs),
        );
        $apiheader = array(
            'Content-Type: text/xml; charset=utf-8',
            'Content-Length: ' . strlen($arrInputs),
            'SOAPAction: ' . $action
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->webservices_url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $apiheader);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arrInputs);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        // Send the request and check the response
        if (($result = curl_exec($ch)) === FALSE) {
            die('cURL error: ' . curl_error($ch) . "<br />\n");
        } else {
           return $result;
        }
        curl_close($ch);

        if (!$result) {
            return false;
        }
        /* Return the data in JSON format */
       
    }

    //This will return hashing based values of a string
    function applyHash($arrInputs) {
        $str = 'X_MerchantID' . $this->merchant_id . 'X_UserName' . $this->username . 'X_ReferenceID' . $arrInputs['Orderid'] . 'X_Mobile' . $arrInputs['Mobile'] . 'X_CurrencyCode' . $arrInputs['CountryCode'] . 'X_Total' . $arrInputs['total_amount'] . '';
        $hashstr = hash_hmac('sha256', $str, $this->api_key);
        return $hashstr;
    }

    function prepareRequest($arrInputs, $hashStr) {
//
//        $request = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/" xmlns:tap="http://schemas.datacontract.org/2004/07/Tap.PayServiceContract">';
//        $request .= '<soapenv:Header/>';
//        $request .= '<soapenv:Body>';
//        $request .= '<tem:PaymentRequest>';
//        $request .= '<tem:PayRequest>';
//        $request .= '<tap:CustomerDC>';
//        $request .= '<tap:Email>'.$arrInputs['Email'].'</tap:Email>';
//        $request .= '<tap:Mobile>' . $arrInputs['Mobile'] . '</tap:Mobile>';
//        $request .= '<tap:Name>' . $arrInputs['Name'] . '</tap:Name>';
//        $request .= '</tap:CustomerDC>';
//        $request .= '<tap:MerMastDC>';
//        $request .= '<tap:ErrorURL>' . $arrInputs['ErrorUrl'] . '</tap:ErrorURL>';
//        $request .= '<tap:HashString>' . $hashStr . '</tap:HashString>';
//        $request .= '<tap:MerchantID>' . $this->merchant_id . '</tap:MerchantID>';
//        $request .= '<tap:PostURL>' . $arrInputs['ErrorUrl'] . '</tap:PostURL>';
//        $request .= '<tap:ReferenceID> ' . $arrInputs['Orderid'] . '</tap:ReferenceID>';
//        $request .= '<tap:ReturnURL>' . $arrInputs['Returnurl'] . '</tap:ReturnURL>';
//        $request .= '<tap:UserName> ' . $this->username . '</tap:UserName>';
//        $request .= '</tap:MerMastDC>';
//        $request .= '<tap:lstGateWayDC>';
//        $request .= '<tap:GateWayDC>';
//        $request .= '<tap:Name>ALL</tap:Name>';
//        $request .= '</tap:GateWayDC>';
//        $request .= '</tap:lstGateWayDC>';
//        $request .= '<tap:lstProductDC><tap:ProductDC>';
//        $request .= '<tap:CurrencyCode>KWD</tap:CurrencyCode>';
//        $request .= '<tap:ImgUrl>imageurl</tap:ImgUrl>';
//        $request .= '<tap:Quantity>1</tap:Quantity>';
//        $request .= '<tap:TotalPrice>' . $arrInputs['total_amount'] . '</tap:TotalPrice>';
//        $request .= '<tap:UnitID>1</tap:UnitID>';
//        $request .= '<tap:UnitName>order - 1001</tap:UnitName>';
//        $request .= '<tap:UnitPrice>10</tap:UnitPrice>';
//        $request .= '</tap:ProductDC>';
//        $request .= '</tap:lstProductDC>';
//        $request .= '</tem:PayRequest>';
//        $request .= '</tem:PaymentRequest>';
//        $request .= '</soapenv:Body>';
//        $request .= '</soapenv:Envelope>';
        
        $request = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/" xmlns:tap="http://schemas.datacontract.org/2004/07/Tap.PayServiceContract">
   <soapenv:Header/>
   <soapenv:Body>
      <tem:PaymentRequest>
         <tem:PayRequest>
            <tap:CustomerDC>
               <tap:Email>'.$arrInputs['Email'].'</tap:Email>
               <tap:Mobile>'.$arrInputs['Mobile'].'</tap:Mobile>
               <tap:Name>'.$arrInputs['Name'].'</tap:Name>
            </tap:CustomerDC>
            <tap:MerMastDC>
               <tap:AutoReturn>Y</tap:AutoReturn>
               <tap:HashString>' . $hashStr . '</tap:HashString>
               <tap:MerchantID>' . $this->merchant_id . '</tap:MerchantID>
               <tap:ReferenceID>'.$arrInputs['Orderid'].'</tap:ReferenceID>
               <tap:ReturnURL>'.$arrInputs['Returnurl'].'</tap:ReturnURL>
			   <tap:PostURL>'.$arrInputs['PostUrl'].'</tap:PostURL>
               <tap:UserName>'.$this->username.'</tap:UserName>
            </tap:MerMastDC>
            <tap:lstProductDC>
               <tap:ProductDC>
                  <tap:CurrencyCode>KWD</tap:CurrencyCode>
                  <tap:Quantity>1</tap:Quantity>
                  <tap:TotalPrice>10.000</tap:TotalPrice>
                  <tap:UnitName>Order - 45445457007</tap:UnitName>
                  <tap:UnitPrice>'.$arrInputs['total_amount'].'</tap:UnitPrice>
               </tap:ProductDC>
            </tap:lstProductDC>
         </tem:PayRequest>
      </tem:PaymentRequest>
   </soapenv:Body>
</soapenv:Envelope>';

        return $request;
    }

    function parsingResult($xmlOutput) {
        
        $xmlobj = simplexml_load_string($xmlOutput);
        $xmlobj->registerXPathNamespace('a', 'http://schemas.datacontract.org/2004/07/Tap.PayServiceContract');
        $xmlobj->registerXPathNamespace('i', 'http://www.w3.org/2001/XMLSchema-instance');

        $xmlOutput = $xmlobj->xpath('//a:ReferenceID/text()');
        $temp='';
        if (is_array($xmlOutput)) {
            foreach ($xmlOutput as $temp) {
               // echo "<br>ReferenceID : " . $temp;
            }
        }

        $xmlOutput = $xmlobj->xpath('//a:ResponseCode/text()');
        if (is_array($xmlOutput)) {
            foreach ($xmlOutput as $temp) {
                //echo "<br>ResponseCode : " . $temp;
            }
        }

        $xmlOutput = $xmlobj->xpath('//a:ResponseMessage/text()');
        if (is_array($xmlOutput)) {
            foreach ($xmlOutput as $temp) {
               // echo "<br>ResponseMessage : " . $temp;
            }
        }

        $xmlOutput = $xmlobj->xpath('//a:PaymentURL/text()');
        if (is_array($xmlOutput)) {
            foreach ($xmlOutput as $temp) {
                //echo "<br>PaymentURL : " . $temp;
            }
        }
        
        header("Location: $temp");
    }

    function doPayment($arrInputs) {
        $action = "http://tempuri.org/IPayGatewayService/PaymentRequest";
        $hashStr = $this->applyHash($arrInputs);

        $arrParsedInput = $this->prepareRequest($arrInputs, $hashStr);
//        print_r($arrParsedInput);exit;
        $res = $this->sendRequest($arrParsedInput, $action);


        $this->parsingResult($res);
    }

}

?> 