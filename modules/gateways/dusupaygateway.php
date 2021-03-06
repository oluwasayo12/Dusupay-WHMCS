<?php
//////////////////////////////////////////////////////
//***************************************************/
//* Do Not Run This File In Directly on ur Browser  */
//* Please see the ReadMe.txt file for instruction  */
//* This File is Written For DUSUPAY Payment Gateway*/
//* For Any Help, Contact me                        */
//***************************************************/
//* Email: oluwasayo12@gmail.com                  */
//////////////////////////////////////////////////////
function dusupaygateway_config() {
$pay_color  = 'blue'; //Default Favourite Color is blue
    $configarray = array(
     "FriendlyName" => array("Type" => "System", "Value"=>"DUSUPAY Global Gateway In Africa"),
     "dusupay_merchantId" => array("FriendlyName" => "DUSUPAY Merchant ID", "Type" => "text", "Size" => "20", ),
     "cur" => array("FriendlyName" => "Currency", "Type" => "dropdown", "Options" => "NGN,USD", "Description" => "Select Currency", )
    );
    return $configarray;
}
function dusupaygateway_link($params) {
    
    $cur  = $params['cur'];
    $merchant_id = $params['dusupay_merchantId'];
    
    $redirect_url = $params['dusupay_redirectURL'];
    # Invoice Variables
    $invoiceid = $params['invoiceid'];
    $description = $params["description"];
    $amount = $params['amount']; # Format: ##.##
    $currency = $params['currency']; # Currency Code
    # Client Variables
    $firstname = $params['clientdetails']['firstname'];
    $lastname = $params['clientdetails']['lastname'];
    $email = $params['clientdetails']['email'];
    $address1 = $params['clientdetails']['address1'];
    $address2 = $params['clientdetails']['address2'];
    $city = $params['clientdetails']['city'];
    $state = $params['clientdetails']['state'];
    $postcode = $params['clientdetails']['postcode'];
    $country = $params['clientdetails']['country'];
    $phone = $params['clientdetails']['phonenumber'];
    # System Variables
    $companyname = $params['companyname'];
    $systemurl = $params['systemurl'];
    $currency = $params['currency'];
    # Enter your code submit to the gateway...
  $isSSL = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443);
$callbackUrl = 'http' . ($isSSL ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] .
        substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/')) .
        '/modules/gateways/callback/dusupaygateway.php';

    $code = '<form method="post" action="https://dusupay.com/dusu_payments/dusupay">
<input type="hidden" name="dusupay_merchantId" value="'.$merchant_id.'" />
<input type="hidden" name="dusupay_itemId" value="Invoice Payment" />
<input type="hidden" name="dusupay_amount" value="'.$amount.'" />
<input type="hidden" name="dusupay_itemName" value="'.$description.'" />
<input type="hidden" name="dusupay_transactionReference" value="'.$invoiceid.'" />
<input type="hidden" name="dusupay_currency" value="'.$cur.'" />
<input type="hidden" name="dusupay_redirectURL" value="'.$callbackUrl.'" />
<input type="hidden" name="dusupay_successURL" value="'.$callbackUrl.'" />
<input type="image" src="https://dusupay.com/img/paybuttons/dusupaybtn6.png" border="0" alt="We Accept DUSUPAY" />
</form>';
    return $code;
}
?>
