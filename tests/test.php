<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PositionlyApi\PositionlyApi;

$clientId = '';
$clientSecret = '';
$username = '';
$password = '';

$client = new OAuth2\Client($clientId, $clientSecret);

$authorizationParams = array(
	'username' => $username,
	'password' => $password,
);

$result = $client->getAccessToken(PositionlyApi::getTokenEndpoint(), 'password', $authorizationParams);
$accessToken = json_decode($result['result'], true);

$client->setAccessToken($accessToken['access_token']);
$client->setAccessTokenType(OAuth2\Client::ACCESS_TOKEN_OAUTH);

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

print_r($response);
exit;
