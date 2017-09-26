<?php
/**
 * WHMCS Sample Payment Gateway Module
 *
 * Payment Gateway modules allow you to integrate payment solutions with the
 * WHMCS platform.
 *
 * This sample file demonstrates how a payment gateway module for WHMCS should
 * be structured and all supported functionality it can contain.
 *
 * Within the module itself, all functions must be prefixed with the module
 * filename, followed by an underscore, and then the function name. For this
 * example file, the filename is "gatewaymodule" and therefore all functions
 * begin "gatewaymodule_".
 *
 * If your module or third party API does not support a given function, you
 * should not define that function within your module. Only the _config
 * function is required.
 *
 * For more information, please refer to the online documentation.
 *
 * @see https://developers.whmcs.com/payment-gateways/
 *
 * @copyright Copyright (c) WHMCS Limited 2017
 * @license http://www.whmcs.com/license/ WHMCS Eula
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

/**
 * Define module related meta data.
 *
 * Values returned here are used to determine module related capabilities and
 * settings.
 *
 * @see https://developers.whmcs.com/payment-gateways/meta-data-params/
 *
 * @return array
 */
function paybill_ng_MetaData()
{
    return array(
        'DisplayName' => 'PayBill.NG Payment Gateway Module',
        'APIVersion' => '1.1', // Use API Version 1.1
        'DisableLocalCredtCardInput' => true,
        'TokenisedStorage' => false,
    );
}

/**
 * Define gateway configuration options.
 *
 * The fields you define here determine the configuration options that are
 * presented to administrator users when activating and configuring your
 * payment gateway module for use.
 *
 * Supported field types include:
 * * text
 * * password
 * * yesno
 * * dropdown
 * * radio
 * * textarea
 *
 * Examples of each field type and their possible configuration parameters are
 * provided in the sample function below.
 *
 * @return array
 */
function paybill_ng_config()
{
    return array(
        // the friendly display name for a payment gateway should be
        // defined here for backwards compatibility
        'PayBill_NG' => array(
            'Type' => 'System',
            'Value' => 'PayBill.NG Payment Gateway Module',
        ),
        // a text field type allows for single line text input
        'organizationCode' => array(
            'FriendlyName' => 'Organization Code',
            'Type' => 'text',
            'Size' => '100',
            'Default' => '',
            'Description' => 'Enter your PayBill.NG Organization Code here',
        ),
        // a password field type allows for masked text input
        // the yesno field type displays a single checkbox option
        'testMode' => array(
            'FriendlyName' => 'Test Mode',
            'Type' => 'yesno',
            'Description' => 'Tick to enable test mode',
        ),

        // a text field type allows for single line text input
        'testPublicKey' => array(
            'FriendlyName' => 'Test Public Key',
            'Type' => 'text',
            'Size' => '1000',
            'Default' => '',
            'Description' => 'Enter your PayBill.NG Test Public Key',
        ),

        // a text field type allows for single line text input
        'testSecretKey' => array(
            'FriendlyName' => 'Test Secret Key',
            'Type' => 'text',
            'Size' => '1000',
            'Default' => '',
            'Description' => 'Enter your PayBill.NG Test Public Key',
        ),

        // a text field type allows for single line text input
        'livePublicKey' => array(
            'FriendlyName' => 'Live Public Key',
            'Type' => 'text',
            'Size' => '1000',
            'Default' => '',
            'Description' => 'Enter your PayBill.NG Live Public Key',
        ),
        // a text field type allows for single line text input
        'liveSecretKey' => array(
            'FriendlyName' => 'Live Secret Key',
            'Type' => 'text',
            'Size' => '1000',
            'Default' => '',
            'Description' => 'Enter your PayBill.NG Live Secret Key',
        ),

        // a text field type allows for single line text input
        'subAccountCode' => array(
            'FriendlyName' => 'Sub Account Code',
            'Type' => 'text',
            'Size' => '1000',
            'Default' => '',
            'Description' => 'For Split Settlement, enter the sub-account code',
        ),

        'organizationTransactionCharge' => array(
            'FriendlyName' => 'Transaction Fee',
            'Type' => 'text',
            'Size' => '1000',
            'Default' => '',
            'Description' => 'For Split Settlement, enter your transaction fee',
        ),

        'chargeBearer' => array(
            'FriendlyName' => 'Transaction Charge Bearer',
            'Type' => 'text',
            'Size' => '1000',
            'Default' => '',
            'Description' => 'For Split Settlement, who bears the transaction charge? sub_account or main_account',
        ),
        /* // the yesno field type displays a single checkbox option
         'testMode' => array(
             'FriendlyName' => 'Test Mode',
             'Type' => 'yesno',
             'Description' => 'Tick to enable test mode',
         ),
         // the dropdown field type renders a select menu of options
         'dropdownField' => array(
             'FriendlyName' => 'Dropdown Field',
             'Type' => 'dropdown',
             'Options' => array(
                 'option1' => 'Display Value 1',
                 'option2' => 'Second Option',
                 'option3' => 'Another Option',
             ),
             'Description' => 'Choose one',
         ),
         // the radio field type displays a series of radio button options
         'radioField' => array(
             'FriendlyName' => 'Radio Field',
             'Type' => 'radio',
             'Options' => 'First Option,Second Option,Third Option',
             'Description' => 'Choose your option!',
         ),
         // the textarea field type allows for multi-line text input
         'textareaField' => array(
             'FriendlyName' => 'Textarea Field',
             'Type' => 'textarea',
             'Rows' => '3',
             'Cols' => '60',
             'Description' => 'Freeform multi-line text input field',
         ),*/
    );
}

/**
 * Payment link.
 *
 * Required by third party payment gateway modules only.
 *
 * Defines the HTML output displayed on an invoice. Typically consists of an
 * HTML form that will take the user to the payment gateway endpoint.
 *
 * @param array $params Payment Gateway Module Parameters
 *
 * @see https://developers.whmcs.com/payment-gateways/third-party-gateway/
 *
 * @return string
 */
function paybill_ng_link($params)
{
    // Gateway Configuration Parameters
    $organizationCode = $params['organizationCode'];
    $testPublicKey = $params['testPublicKey'];
    $livePublicKey = $params['livePublicKey'];
    $testMode = $params['testMode'];

    $subAccountCode = in_array('subAccountCode', $params) ? $params['subAccountCode'] : '';
    $transactionFee = in_array('organizationTransactionCharge', $params) ? $params['organizationTransactionCharge'] : '';
    $chargeBearer = in_array('chargeBearer', $params) ? $params['chargeBearer'] : '';


    $publicKey = $testMode == 'yes' ? $testPublicKey : $livePublicKey;

    $dropdownField = $params['dropdownField'];
    $radioField = $params['radioField'];
    $textareaField = $params['textareaField'];

    // Invoice Parameters
    $invoiceId = $params['invoiceid'];
    $description = $params["description"];
    $amount = $params['amount'];
    $currencyCode = $params['currency'];

    // Client Parameters
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

    // System Parameters
    $companyName = $params['companyname'];
    $systemUrl = $params['systemurl'];
    $returnUrl = $params['returnurl'];
    $langPayNow = $params['langpaynow'];
    $moduleDisplayName = $params['name'];
    $moduleName = $params['paymentmethod'];
    $whmcsVersion = $params['whmcsVersion'];


    $scriptUrl = '<script src="https://paybill.ng/assets/paynou/js/v1/paynou.inline.min.js" type="text/javascript"></script>';
    $paymentFunction = '<script>';
    $paymentFunction .= 'function payWithPayBillNG(){';
    $paymentFunction .= 'PayBillService.load({
		\'customer_email\': \'' . $email . '\',
		\'amount\':' . $amount . ',
		\'organization_code\': \'' . $organizationCode . '\',
		\'organization_unique_reference\': \'' . $invoiceId . '\',
		\'organization_public_key\': \'' . $publicKey . '\',
		\'sub_account_code\': \'' . $subAccountCode . '\',
        \'currency\':\'' . $currencyCode . '\',
		\'organization_transaction_charge\': \'' . $transactionFee . '\',
		\'payment_charge_bearer\': \'' . $chargeBearer . '\',
		\'onClose\': function (ref) {';
    $paymentFunction .= 'window.location.href = \'' . $systemUrl . '/modules/gateways/callback/' . $moduleName . '.php?x_ref=' . '\'';
    $paymentFunction .= 'console.log("closed page " + ref)';
    $paymentFunction .= '}})';
    $paymentFunction .= '</script>';

    $htmlOutput = '<input type="submit" onclick="payWithPayBillNG()" />';
    $htmlOutput .= $scriptUrl;
    $htmlOutput .= $paymentFunction;

    return $htmlOutput;
}

/**
 * Refund transaction.
 *
 * Called when a refund is requested for a previously successful transaction.
 *
 * @param array $params Payment Gateway Module Parameters
 *
 * @see https://developers.whmcs.com/payment-gateways/refunds/
 *
 * @return array Transaction response status
 */
function paybill_ng_refund($params)
{
    /* // Gateway Configuration Parameters
     $accountId = $params['accountID'];
     $secretKey = $params['secretKey'];
     $testMode = $params['testMode'];
     $dropdownField = $params['dropdownField'];
     $radioField = $params['radioField'];
     $textareaField = $params['textareaField'];

     // Transaction Parameters
     $transactionIdToRefund = $params['transid'];
     $refundAmount = $params['amount'];
     $currencyCode = $params['currency'];

     // Client Parameters
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

     // System Parameters
     $companyName = $params['companyname'];
     $systemUrl = $params['systemurl'];
     $langPayNow = $params['langpaynow'];
     $moduleDisplayName = $params['name'];
     $moduleName = $params['paymentmethod'];
     $whmcsVersion = $params['whmcsVersion'];*/

    // perform API call to initiate refund and interpret result

    return array(
        // 'success' if successful, otherwise 'declined', 'error' for failure
        'status' => 'declined',
        // Data to be recorded in the gateway log - can be a string or array
        //'rawdata' => $responseData,
        // Unique Transaction ID for the refund transaction
        //'transid' => $refundTransactionId,
        // Optional fee amount for the fee value refunded
        // 'fees' => $feeAmount,
    );
}

/**
 * Cancel subscription.
 *
 * If the payment gateway creates subscriptions and stores the subscription
 * ID in tblhosting.subscriptionid, this function is called upon cancellation
 * or request by an admin user.
 *
 * @param array $params Payment Gateway Module Parameters
 *
 * @see https://developers.whmcs.com/payment-gateways/subscription-management/
 *
 * @return array Transaction response status
 */
function paybill_ng_cancelSubscription($params)
{
    /*  // Gateway Configuration Parameters
      $accountId = $params['accountID'];
      $secretKey = $params['secretKey'];
      $testMode = $params['testMode'];
      $dropdownField = $params['dropdownField'];
      $radioField = $params['radioField'];
      $textareaField = $params['textareaField'];

      // Subscription Parameters
      $subscriptionIdToCancel = $params['subscriptionID'];

      // System Parameters
      $companyName = $params['companyname'];
      $systemUrl = $params['systemurl'];
      $langPayNow = $params['langpaynow'];
      $moduleDisplayName = $params['name'];
      $moduleName = $params['paymentmethod'];
      $whmcsVersion = $params['whmcsVersion'];*/

    // perform API call to cancel subscription and interpret result

    return array(
        // 'success' if successful, any other value for failure
        'status' => 'failed',
        // Data to be recorded in the gateway log - can be a string or array
        //'rawdata' => $responseData,
    );
}
