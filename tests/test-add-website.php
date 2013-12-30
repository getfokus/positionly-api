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
$response = $api->get('/accounts');
$result = $response->getResult();

// get first account id
$accountId = $result[0]['id'];

// get first website id
$params = array(
	'scheme' => 'http',
	'name' => 'onet.pl',
	'title' => 'onet.pl title',

	'website_engines_attributes' => array(
	    array(
			"engine_id" => 43,
		)
	)
);

$response = $api->post(sprintf('/accounts/%s/websites', $accountId), $params);
$result = $response->getResult();

if($response->isSuccess()) {
	echo 'Success!';
}
else {
	echo 'Failure:';
	var_dump($result['errors']);
	exit;
}

$createdWebsiteId = $result['website']['id'];

$response = $api->delete(sprintf('/accounts/%s/websites/%s', $accountId, $createdWebsiteId));
$result = $response->getResult();

print_r($response);
exit;
