PHP library for Positionly
==============

Full API documentation: https://positionly.com/api

Installation
------------------

###Composer

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
  $response = $api->call('/accounts'); // for https://api.positionly.com/v1/accounts.json

  // the $response contains the array with result
  ```

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
$response = $api->call('/accounts');

print_r($response);

?>
```


Author & Contact
----------------

Piotr *Athlan* Pelczar
    - me@athlan.pl
