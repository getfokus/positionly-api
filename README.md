
PHP library for Positionly
==============

Full API documentation: https://positionly.com/api

**Table of Contents**
- [PHP library for Positionly](#php-library-for-positionly)
	- [Author & Contact](#author-&-contact)
	- [Installation](#installation)
		- [Composer](#composer)
	- [Usage instructions](#usage-instructions)
		- [`GET` request](#get-request)
		- [`DELETE` request](#delete-request)
		- [`POST` request](#post-request)
		- [Full example](#full-example)

###Author & Contact
----------------

Piotr *Athlan* Pelczar
    - me@athlan.pl

Installation
------------------

###Composer
----------------

Add dependency in `composer.json`:

```
"require": {
    "getfokus/positionly": "*"
},
```

Usage instructions
------------------

  1. Set credentials (clientId, clientSecret, username and password) for OAuth2 authentication and create OAuth2\Client object.
  2. Obtain the OAuth2 Access Token (password method)
  3. Pass client object into PositionlyApi instance.
  4. Call the api:
  ```php
  $response = $api->get('/accounts'); // for https://api.positionly.com/v1/accounts.json

  // the $response contains the array with result
  ```

### `GET` request
----------------

To make `GET` request just use:
```php
$response = $api->get('/accounts');
$response = $api->get('/accounts/<account id>/websites');

if($response->isSuccess()) {
	echo 'Success:';
	
	$result = $response->getResult();
	print_r($result);
}
else {
	echo 'Failure.';
}
```

### `DELETE` request
To make `DELETE` request just use:
```php
$response = $api->delete('/accounts/<account id>/websites/<website id>');

if($response->isSuccess()) {
	echo 'Success:';
}
else {
	echo 'Failure.';
}
```

### `POST` request
----------------

To make `POST` request just use:
```php
$params = array(
	'scheme' => 'http',
	'name' => 'example.com',
	'title' => 'Example website',

	'website_engines_attributes' => array(
	    array(
			"engine_id" => 43,
		)
	)
);

$response = $api->post('/accounts/<account id>/websites', $params);
	
if($response->isSuccess()) {
	echo 'Success:';

	$result = $response->getResult();
	print_r($result);
}
else {
	echo 'There are errors in form:';
	
	$result = $response->getResult();
	print_r($result['errors']);
}
```

### Full example
----------------

```php
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
$response = $api->get('/accounts');

print_r($response);

?>
```
