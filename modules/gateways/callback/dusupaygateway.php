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
//$gatewaymodule = "voguepaygateway.php"; # Enter your gateway module name here replacing template
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
$status = $transaction['status'];
$transid = $transaction['dusupay_transactionId'];
$invoiceid = checkCbInvoiceID($invoice_id,$GATEWAY["name"]); 
checkCbTransID($transid); 
if ($status =="COMPLETE" ) {
     //Successful
    addInvoicePayment($invoice_id,$transid,$amount,$fee,$gatewaymodule); 
    logTransaction($GATEWAY["name"],$_POST,"Transaction Was Successful");
}
else
{
logTransaction($GATEWAY["name"],$_POST,"Transaction Not Approved OR Unrecognised Merchant ID");
}
if ($status =="CANCELLED") {
    logTransaction($GATEWAY["name"],$_POST,"CANCELLED");
}
if ($status =="FAILED") {
    logTransaction($GATEWAY["name"],$_POST,"FAILED");
}
if ($status =="PENDING") {
    logTransaction($GATEWAY["name"],$_POST,"PENDING");
}
?>
