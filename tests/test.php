<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PositionlyApi\PositionlyApi;

require '_credentials.php';

$client = new OAuth2\Client($clientId, $clientSecret);

$authorizationParams = array(
	'username' => $username,
	'password' => $password,
);

$result = $client->getAccessToken(PositionlyApi::getTokenEndpoint(), 'password', $authorizationParams);
$accessToken = json_decode($result['result'], true);

$client->setAccessToken($accessToken['access_token']);

$api = new PositionlyApi($client);

// get accounts
$response = $api->call('/accounts');
// get first account id
$accountId = $response[0]['id'];

// get first website id
$response = $api->call(sprintf('/accounts/%s/websites', $accountId));
$websiteId = $response[0]['id'];

// get first keyword id
$response = $api->call(sprintf('/accounts/%s/websites/%s/keywords', $accountId, $websiteId));
$keywordId = $response[0]['id'];

$engineId = 43; // google polska

$response = $api->call(sprintf('/accounts/%s/websites/%s/engines/%s/keywords/%s/positions', $accountId, $websiteId, $engineId, $keywordId));

if($response->isSuccess()) {
	echo 'Success!';
}
else {
	echo 'Failure';
}

print_r($response);
exit;
