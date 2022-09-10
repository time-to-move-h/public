<?php
declare(strict_types=1);
$result = array();
if (null !== $params && isset($params['query'])) {
$q = filter_var($params['query'], FILTER_SANITIZE_STRING);
$form = new \stdClass();
$form->q = $q;
$generic = new \Moviao\Data\GenericCommon();
$generic->iniDatabase();
$generic->setSession($sessionUser);
$data = $generic->searchTags($form);
$result = array("suggestions" => $data);
}
echo json_encode($result);
exit();