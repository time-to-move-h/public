<?php

ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

/*
 * How to verify Mollie API Payments in a webhook.
 *
 * See: https://docs.mollie.com/guides/webhooks
 */

try {

//    $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
//    $txt = var_export($params, true) . "\n";
//    fwrite($myfile, $txt);
//    fclose($myfile);

    if (isset($params["id"])) {

        /*
         * Initialize the Mollie API library with your API key.
         *
         * See: https://www.mollie.com/dashboard/developers/api-keys
         */
        require_once "package/Mollie/vendor/autoload.php";
        $mollie = new \Mollie\Api\MollieApiClient();
        $mollie->setApiKey("test_b7vWwkWkRsbE5PqK6R8CKuHb5m5G6g");
        /*
         * Retrieve the payment's current state.
         */
        $payment = $mollie->payments->get($params["id"]);
        $orderId = $payment->metadata->order_id;


        $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
        $txt = var_export($payment, true) . " - " . $orderId . "\n";
        fwrite($myfile, $txt);
        fclose($myfile);


        /*
         * Update the order in the database.
         */
        //database_write($orderId, $payment->status);

        if ($payment->isPaid() && !$payment->hasRefunds() && !$payment->hasChargebacks()) {
            /*
             * The payment is paid and isn't refunded or charged back.
             * At this point you'd probably want to start the process of delivering the product to the customer.
             */
            echo "Order Paid";

        } elseif ($payment->isOpen()) {
            /*
             * The payment is open.
             */

            echo "Payment open";

        } elseif ($payment->isPending()) {
            /*
             * The payment is pending.
             */
        } elseif ($payment->isFailed()) {
            /*
             * The payment has failed.
             */

            echo "Payment failed";

        } elseif ($payment->isExpired()) {
            /*
             * The payment is expired.
             */

            echo "Payment expired";

        } elseif ($payment->isCanceled()) {
            /*
             * The payment has been canceled.
             */

            echo "Payment canceled";

        } elseif ($payment->hasRefunds()) {
            /*
             * The payment has been (partially) refunded.
             * The status of the payment is still "paid"
             */

            echo "Payment has refund";

        } elseif ($payment->hasChargebacks()) {
            /*
             * The payment has been (partially) charged back.
             * The status of the payment is still "paid"
             */

            echo "Payment chargebacks";

        }

    } else {

        echo "nothing to see here";

        $myfile = fopen("nothingtosee.txt", "w") or die("Unable to open file!");
        $txt = "Nothing : " . var_export($params, true);
        fwrite($myfile, $txt);
        fclose($myfile);

    }

} catch (\Mollie\Api\Exceptions\ApiException $e) {
    echo "API call failed: " . htmlspecialchars($e->getMessage());

    $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
    $txt = "Error : " . $e->getMessage();
    fwrite($myfile, $txt);
    fclose($myfile);
}