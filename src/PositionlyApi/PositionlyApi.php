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
	
	public function get($resource_url, $parameters = array()) {
		return $this->call($resource_url, $parameters, 'GET');
	}
	
	public function post($resource_url, $parameters = array()) {
		return $this->call($resource_url, $parameters, 'POST');
	}
	
	public function delete($resource_url, $parameters = array()) {
		return $this->call($resource_url, $parameters, 'DELETE');
	}
	
	public function call($resource_url, $parameters = array(), $http_method = 'GET', array $http_headers = array()) {
		$url = $this->apiUrl . $resource_url . '.json';
		
		if($http_method === 'POST') {
			// Positionly expects JSON as post input format
			if(is_string($parameters)) {
				$parameters = json_decode($parameters, true);
				
				if(!is_array($parameters))
					throw new PositionlyApiException('Invalid JSON string in POST parameter');
			}
			
			if(is_array($parameters)) {
				$parameters = json_encode($parameters);
			}
			else
				throw new PositionlyApiException('Invalid POST parameter. JSON string or array expected.');
		}
		
		$result = $this->client->fetch($url, $parameters, $http_method, $http_headers);
		
		$response = PositionlyApiResponse::createResponse($result['code'], $result['result'], $http_method);
		$responseResult = $response->getResult();
		
		if($response->getResponseCode() !== 200) {
			// an error occured.
			if(isset($responseResult['message']))
				throw new PositionlyApiException($responseResult['message'], $response->getResponseCode());
			
			// bad request, there are errors in form
			if($response->getResponseCode() === 400 && isset($responseResult['errors']))
				return $response;
			
			throw new PositionlyApiException("Unknown error (no message)", $response->getResponseCode());
		}
		
		return $response;
	}
}
