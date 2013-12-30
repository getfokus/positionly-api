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

$api = new PositionlyApi($client);

// get accounts
$response = $api->call('/accounts');
// get first account id
$accountId = $response[0]['id'];

// get first website id
$params = array(
	'scheme' => 'http',
	'name' => 'onet.pl',
	'title' => 'onet.pl title',

	/*'website_engines_attributes' => array(
	    array(
			"engine_id" => 43,
		)
	)*/
);
$params = '{
  "scheme": "http",
  "name": "example.com",
  "title": "Example",
  "website_engines_attributes": [
    { "engine_id": 31 }
  ]
}';
$response = $api->call(sprintf('/accounts/%s/websites', $accountId), $params, 'POST');

print_r($response);
exit;
