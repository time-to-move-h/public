<?php

require_once '../../package/oauth2-server/vendor/bshaffer/oauth2-server-php/src/OAuth2/Autoloader.php';
OAuth2\Autoloader::register();

$dsn = 'mysql:host=moviao-db;dbname=oauth2';
$username = 'oauth2';
$password = 'DC3hv32qF5ig5P';

$storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));
$server = new OAuth2\Server($storage);
$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage)); // or any grant type you like!

// Add the "Client Credentials" grant type (it is the simplest of the grant types)
$server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));