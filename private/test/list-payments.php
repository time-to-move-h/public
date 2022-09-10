<?php

//echo getcwd() . "\n";

/*
 * Make sure to disable the display of errors in production code!
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//require_once('../../package/Mollie/vendor/autoload.php');

//require_once "package/Mollie/vendor/autoload.php";
//$mollie = new \Mollie\Api\MollieApiClient();
//$mollie->setApiKey("test_b7vWwkWkRsbE5PqK6R8CKuHb5m5G6g");

//echo var_dump($mollie);


try {
    /*
     * Initialize the Mollie API library with your API key.
     *
     * See: https://www.mollie.com/dashboard/developers/api-keys
     */


    require_once "package/Mollie/vendor/autoload.php";
    $mollie = new \Mollie\Api\MollieApiClient();
    $mollie->setApiKey("test_b7vWwkWkRsbE5PqK6R8CKuHb5m5G6g");


    /*
     * Get all the activated methods for this API key.
     * By default we are using the resource "payments".
     * See the orders folder for an example with the Orders API.
     */
    $methods = $mollie->methods->allActive();
    foreach ($methods as $method) {
        echo '<div style="line-height:40px; vertical-align:top">';
        echo '<img src="' . htmlspecialchars($method->image->size1x) . '" srcset="' . htmlspecialchars($method->image->size2x) . ' 2x"> ';
        echo htmlspecialchars($method->description) . ' (' . htmlspecialchars($method->id) . ')';
        echo '</div>';
    }
} catch (\Mollie\Api\Exceptions\ApiException $e) {
    echo "API call failed: " . htmlspecialchars($e->getMessage());
}














/*
 * How to prepare a new payment with the Mollie API.
 */

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

