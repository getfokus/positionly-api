<?php

namespace PositionlyApi;

use OAuth2\Client as OAuth2Client;

class PositionlyApi {
	
	const AUTHORIZE_ENDPOINT = "https://auth.positionly.com/oauth2/token";
	const TOKEN_ENDPOINT = "https://auth.positionly.com/oauth2/token";
	
	/**
	 * @var OAuth2\Client
	 */
	private $client;
	
	private $apiUrl = "https://api.positionly.com/v1";
	
	public function __construct(OAuth2Client $client) {
		$this->setClient($client);
	}
	
	public static function getAuthorizeEndpoint() {
		return self::AUTHORIZE_ENDPOINT;
	}
	
	public static function getTokenEndpoint() {
		return self::TOKEN_ENDPOINT;
	}
	
	public function getApiUrl() {
		return $this->apiUrl;
	}
	
	public function setApiUrl($apiUrl) {
		$this->apiUrl = $apiUrl;
	}
	
	public function getClient() {
		return $this->client;
	}

	public function setClient(OAuth2Client $client) {
		$this->client = $client;
	}

	public function call($resource_url, $parameters = array(), $http_method = 'GET', array $http_headers = array()) {
		$url = $this->apiUrl . $resource_url . '.json';
		
		$result = $this->client->fetch($url, $parameters, $http_method, $http_headers);
		
		$code = isset($result['code']) ? $result['code'] : 0;
		$body = isset($result['result']) ? json_decode($result['result'], true) : array();
		
		if($code !== 200) {
			// an error occured.
			if(isset($body['message']))
				throw new PositionlyApiException($body['message'], $code);
			
			throw new PositionlyApiException("Unknown error (no message)", $code);
		}
		
		return $body;
	}
}
