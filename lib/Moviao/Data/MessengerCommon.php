<?php

namespace Moviao\Data;
use Moviao\Data\CommonData;

class MessengerCommon extends CommonData {

    //private $dbconn = 'mongodb://46.105.99.14:27017';
    //private $pushserver = "http://www.moviao.com:8888/";
    //private $pushserver = "http://82.169.55.205:8888/";

//    private $dbconn = 'mongodb://84.124.176.227:27017';
//    private $pushserver = "http://84.124.176.227:8888/";

    //private $dbconn = 'mongodb://192.168.2.73:27017';
    //private $pushserver = "http://192.168.2.73:8888/";

    private $dbconn = 'mongodb://192.168.2.73:27017';
    private $pushserver = "http://192.168.2.73:8888/";

    public function __construct() {}

    /**
     * send push notification async (multi)
     * @param array $data
     * @return array
     */
    private function sendPush(array $data) {

        try {
            $jsondata = json_encode($data);

            // Create get requests for each URL
            $mh = curl_multi_init();

            //$ch = curl_init($pushserver);
            $ch = curl_init($this->pushserver);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);

            curl_multi_add_handle($mh, $ch);

            // Start performing the request
            do {
                $execReturnValue = curl_multi_exec($mh, $runningHandles);
            } while ($execReturnValue == CURLM_CALL_MULTI_PERFORM);

           // Loop and continue processing the request
            while ($runningHandles && $execReturnValue == CURLM_OK)
            {
                // !!!!! changed this if and the next do-while !!!!!

                if (curl_multi_select($mh) != -1)
                {
                    usleep(100);
                }

                do {
                    $execReturnValue = curl_multi_exec($mh, $runningHandles);
                } while ($execReturnValue == CURLM_CALL_MULTI_PERFORM);
            }

            // Check for any errors
            if ($execReturnValue != CURLM_OK)
            {
                trigger_error("Curl multi read error $execReturnValue\n", E_USER_WARNING);
            }

            // Remove and close the handle
            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);

            // Clean up the curl_multi handle
            curl_multi_close($mh);

            //$response = curl_exec($ch);
            //curl_close($ch);
        } catch (\Error $e) {
            error_log('MessengerCommon >> sendPush : ' . $e);
        }

    }

    /**
     * send Message
     * @param stdClass $form
     * @return array
     */
    public function sendMessage(\stdClass $msg) : array {
        $bresult = false;

        try {

            error_log('MessengerCommon >> sendMessage : first');

            //var_dump($msg->msgid);
            $connection = new \MongoDB\Driver\Manager($this->dbconn);
            $bulk = new \MongoDB\Driver\BulkWrite;
            $doc = array(
                'msgid' => $msg->msgid,
                'type'  => 'chat',
                'from'  => $msg->from,
                'to'    => $msg->to,
                'data'  => $msg->data,
                'content_type' => $msg->content_type,
                'datetime' => $msg->datetime,
                'ack_receipt' => false,
                'ack_delivered' => false,
                'ack_read' => false
            );
            $bulk->insert($doc);
            $result = $connection->executeBulkWrite('moviao.messages', $bulk);
            $bresult = $result->getInsertedCount() > 0;
        } catch (\Error $e) {
            $bresult = false;
            error_log('MessengerCommon >> sendMessage : ' . $e);
        }

        // Send push Notification
        try {
            // Send Push Notification
            if ($bresult === true) {
                $this->sendPush($doc);
            }
        } catch (\Error $e) {
            error_log('MessengerCommon >> Send Push Notification : ' . $e);
        }

        $array = array('result' => $bresult);

        return $array;
    }

    /**
     * confirm Messages
     * @param stdClass $form
     * @return array
     */
    public function readMessages(\stdClass $msg) {
        $bresult = false;
        $return_data = array();

        try {
            parent::getSession()->startSession();
            parent::getSession()->Authorize();

            $uuid = parent::getSession()->getUSER_UUID();

            $filter = array('to' => $uuid, 'ack_delivered' => false);
            $options = array();

            $connection = new \MongoDB\Driver\Manager($this->dbconn);
            $query = new \MongoDB\Driver\Query($filter, $options);
            $result = $connection->executeQuery("moviao.messages", $query);

            foreach ($result as $document) {
                $bresult = true;
                $return_data[] = $document;
            }

            // confirm ack receipt
            $bulk = new \MongoDB\Driver\BulkWrite;
            $bulk->update(
                ['to' => $uuid, 'ack_receipt' => false],
                ['$set' => ['ack_receipt' => true]],
                ['multi' => true, 'upsert' => false]
            );
            $result2 = $connection->executeBulkWrite('moviao.messages', $bulk);
            //$result->getModifiedCount();

        } catch (\Error $e) {
            error_log('MessengerCommon >> readMessages : ' . $e);
        }

        if ($bresult === true) {
            $array = array('result' => $bresult,'data' => $return_data);
        } else {
            $array = array('result' => $bresult,'code' => parent::getError());
        }
        return $array;
    }

    /**
     * confirm receipt
     * @param stdClass $form
     * @return array
     */
    public function confirmReceipt(\stdClass $form) {
        $bresult = false;
        $return_data = array();

        //error_log('rentre dans confirm');

        try {
            parent::getSession()->startSession();
            parent::getSession()->Authorize();

            if (empty($form) || empty($form->msgid) || ! is_string($form->msgid)) {
                return array('result' => false,'code' => 666);
            }

            $msgid = $form->msgid;
            $from = $form->from;

            $uuid = parent::getSession()->getUSER_UUID();

            $filter = array('to' => $uuid, 'ack_delivered' => false, 'msgid' => $msgid);
            $options = array();

            $connection = new \MongoDB\Driver\Manager($this->dbconn);
            $query = new \MongoDB\Driver\Query($filter, $options);
            $result = $connection->executeQuery("moviao.messages", $query);

            foreach ($result as $document) {
                $bresult = true;
                $return_data[] = $document;
            }

            // confirm ack receipt
            $bulk = new \MongoDB\Driver\BulkWrite;
            $bulk->update(
                ['to' => $uuid, 'ack_delivered' => false, 'msgid' => $msgid],
                ['$set' => ['ack_delivered' => true]],
                ['multi' => true, 'upsert' => false]
            );
            $result2 = $connection->executeBulkWrite('moviao.messages', $bulk);
            //$result->getModifiedCount();

        } catch (\Error $e) {
            error_log('MessengerCommon >> confirmReceipt : ' . $e);
        }


        // Send push Notification
        try {
            // Send Push Notification
            if ($bresult === true) {

                $data = array(
                    'msgid' => $form->msgid,
                    'type'  => 'ack_delivered',
                    'from'  => $form->from
                );

                $this->sendPush($data);
            }
        } catch (\Error $e) {
            error_log('MessengerCommon >> Send Push Notification ACK DELIVERED : ' . $e);
        }

        if ($bresult === true) {
            $array = array('result' => $bresult);
        } else {
            $array = array('result' => $bresult,'code' => parent::getError());
        }
        return $array;
    }
}