<?php
//////////////////////////////////////////////////////
//***************************************************/
//* Please see the ReadMe.txt file for instruction  */
//* This File is Written For DUSUPAY Payment Gateway */
//* For Any Help, Contact me                        */
//***************************************************/
//* Email: oluwasayo12@gmail.com                    */
//////////////////////////////////////////////////////
# Required File Includes
//include("../../../dbconnect.php");
//include("../../../includes/functions.php");
//include("../../../includes/gatewayfunctions.php");
//include("../../../includes/invoicefunctions.php");
require_once __DIR__ . '/../../../init.php';
require_once __DIR__ . '/../../../includes/functions.php'; 
require_once __DIR__ . '/../../../includes/gatewayfunctions.php';
require_once __DIR__ . '/../../../includes/invoicefunctions.php';
$gatewayModuleName = basename(__FILE__, '.php');
$GATEWAY = getGatewayVariables($gatewaymodule);
if (!$GATEWAY["type"]) die("Module Not Activated"); # Checks gateway module is active before accepting callback
// $transaction_id = $_POST['transaction_id'];
$merchant_id = $_POST['dusupay_merchantId'];
$tx_ref = $_POST['dusupay_transactionReference'];

$data = file_get_contents("https://dusupay.com/transactions/check_status/".$merchant_id."/".$tx_ref.".json");
$transaction = json_decode($data, true);
# Get Returned Variables - Adjust for Post Variable Names from your Gateway's Documentation
$invoice_id = $transaction['dusupay_transactionReference'];
$status2 = $transaction['dusupay_transactionStatus'];
$status = $transaction['status'];
$transid = $transaction['dusupay_transactionId'];
$invoiceid = checkCbInvoiceID($invoice_id,$gatewayModuleName); 
checkCbTransID($transid); 
if ($status =="success" ) {
     //Successful
    addInvoicePayment($invoice_id,$transid,$amount,$fee,$gatewaymodule); 
    logTransaction($gatewayModuleName,$_POST,"Transaction Was Successful");
     $redirect_url = "//".$_SERVER['SERVER_NAME']."/billing/viewinvoice.php?id=".$invoiceid;
     header('Location: '.$redirect_url);
     
}
else
{
logTransaction($gatewayModuleName,$_POST,"Transaction Not Approved OR Unrecognised Merchant ID");
exit('Error');
}
if ($status2 =="CANCELLED") {
    logTransaction($gatewayModuleName,$_POST,"CANCELLED");
     exit('Error');
}
if ($status2 =="FAILED") {
    logTransaction($gatewayModuleName,$_POST,"FAILED");
     exit('Error');
}
if ($status2 =="PENDING") {
    logTransaction($gatewayModuleName,$_POST,"PENDING");
     exit('Error');
}
?>
