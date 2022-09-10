<?php
//
////echo getcwd() . "\n";
//
///*
// * Make sure to disable the display of errors in production code!
// */
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
//
////require_once('../../package/Mollie/vendor/autoload.php');
//
////require_once "package/Mollie/vendor/autoload.php";
////$mollie = new \Mollie\Api\MollieApiClient();
////$mollie->setApiKey("test_b7vWwkWkRsbE5PqK6R8CKuHb5m5G6g");
//
////echo var_dump($mollie);
//
//
//
//
//
//
//
///*
// * How to prepare a new payment with the Mollie API.
// */
//
//try {
//    /*
//     * Initialize the Mollie API library with your API key.
//     *
//     * See: https://www.mollie.com/dashboard/developers/api-keys
//     */
//
//    require_once "package/Mollie/vendor/autoload.php";
//    $mollie = new \Mollie\Api\MollieApiClient();
//    $mollie->setApiKey("test_b7vWwkWkRsbE5PqK6R8CKuHb5m5G6g");
//
//
//    /*
//     * Generate a unique order id for this example. It is important to include this unique attribute
//     * in the redirectUrl (below) so a proper return page can be shown to the customer.
//     */
//    $orderId = time();
//
//    /*
//     * Determine the url parts to these example files.
//     */
//    $protocol = isset($_SERVER['HTTPS']) && strcasecmp('off', $_SERVER['HTTPS']) !== 0 ? "https" : "http";
//    $hostname = $_SERVER['HTTP_HOST'];
//    $path = dirname(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF']);
//
//    /*
//     * Payment parameters:
//     *   amount        Amount in EUROs. This example creates a € 10,- payment.
//     *   description   Description of the payment.
//     *   redirectUrl   Redirect location. The customer will be redirected there after the payment.
//     *   webhookUrl    Webhook location, used to report when the payment changes state.
//     *   metadata      Custom metadata that is stored with the payment.
//     */
//    $payment = $mollie->payments->create([
//        "amount" => [
//            "currency" => "EUR",
//            "value" => "10.00" // You must send the correct number of decimals, thus we enforce the use of strings
//        ],
//        "description" => "Order #{$orderId}",
//        "redirectUrl" => "{$protocol}://{$hostname}{$path}/payments/return.php?order_id={$orderId}",
//        "webhookUrl" => "{$protocol}://{$hostname}{$path}/payments/webhook.php",
//        "metadata" => [
//            "order_id" => $orderId,
//        ],
//    ]);
//
//    /*
//     * In this example we store the order with its payment status in a database.
//     */
//    //database_write($orderId, $payment->status);
//
//    /*
//     * Send the customer off to complete the payment.
//     * This request should always be a GET, thus we enforce 303 http response code
//     */
//    header("Location: " . $payment->getCheckoutUrl(), true, 303);
//} catch (\Mollie\Api\Exceptions\ApiException $e) {
//    echo "API call failed: " . htmlspecialchars($e->getMessage());
//}

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0" />
    <title>Example 1 - Mollie Components</title>
    <link rel="stylesheet" href="/base.css" />
    <link rel="stylesheet" href="https://www.moviao.com/private/test/style.css" />
</head>
<body>
<a class="link-source" target="_blank" rel="noopener noreferrer" href="https://github.com/mollie/components-examples/tree/master/example-1">View source</a>

<div class="wrapper">
    <form method="post" class="form" id="mcForm">
        <div class="form-fields">
            <div class="form-group form-group--card-holder">
                <label class="label" for="card-holder">Card holder</label>
                <div id="card-holder"></div>
                <div id="card-holder-error" class="field-error" role="alert"></div>
            </div>

            <div class="form-group form-group--card-number">
                <label class="label" for="card-number">Card number</label>
                <div id="card-number"></div>
                <div id="card-number-error" class="field-error" role="alert"></div>
            </div>

            <div class="form-group form-group--expiry-date">
                <label class="label" for="expiry-date">Expiry date</label>
                <div id="expiry-date"></div>
                <div id="expiry-date-error" class="field-error" role="alert"></div>
            </div>

            <div class="form-group form-group--verification-code">
                <label class="label" for="verification-code">Verification code</label>
                <div id="verification-code"></div>
                <div id="verification-code-error" class="field-error" role="alert"></div>
            </div>
        </div>

        <button id="submit-button" class="submit-button" type="submit">
            Pay
        </button>

        <div id="form-error" class="form-error" role="alert"></div>
    </form>
</div>

<script src="https://js.mollie.com/v1/mollie.js"></script>
<script src="https://www.moviao.com/private/test/script.js"></script>
</body>
</html>
