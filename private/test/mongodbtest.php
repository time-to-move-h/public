<?php

//phpinfo();

echo extension_loaded("mongodb") ? "loaded\n" : "not loaded\n";

//$filter = array('to' => '6EF5D18F-A4B5-4573-9C1D-BE3CE1F4CE1F', 'ack_delivered' => false);
//$options = array();
//$connection = new MongoDB\Driver\Manager("mongodb://46.105.99.14:27017");
//$query = new MongoDB\Driver\Query($filter, $options);
//$result = $connection->executeQuery("moviao.messages", $query);
//var_dump($result);

//
//$r = array();
//
//foreach ($result as $document) {
//    $r[] = $document;
//}
//
//echo var_dump($r);





//$bulk = new MongoDB\Driver\BulkWrite;

//$doc = array(
//    'id'      => new MongoDB\BSON\ObjectID,     #Generate MongoID
//    'name'    => 'harry',
//    'age'     => 14,
//    'roll_no' => 1
//);

//$doc = array(
//    'type'      => 'chat',
//    'from'    => 'harry',
//    'to'     => 14,
//    'data' => 1234
//);
//
//$bulk->insert($doc);
//$connection->executeBulkWrite('moviao.messages', $bulk);



//$bulk = new \MongoDB\Driver\BulkWrite;
//$bulk->update(
//    ['to' => 'BD901D8E-0A89-4DFC-938C-F7BE0C302929', 'ack_receipt' => false],
//    ['$set' => ['ack_receipt' => true]],
//    ['multi' => true, 'upsert' => false]
//);
//$result = $connection->executeBulkWrite('moviao.messages', $bulk);
//
//echo $result->getModifiedCount();