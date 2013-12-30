<?php

namespace PositionlyApi;

class PositionlyApiResponse {

	private $responseCode = 0;
	
	private $responseBody = '';
	
	private $success = false;
	
	private $result = array();
	
	public static function createResponse($code, $body, $http_method) {
		$obj = new self();
		
		$result = self::determineResult($body);
		$success = self::determineSuccess($code, $result, $http_method);
		
		$obj->setResponseCode($code)
		    ->setResponseBody($body)
		    ->setResult($result)
		    ->setSuccess($success);
		
		return $obj;
	}
	
	private static function determineResult($body) {
		$result = json_decode($body, true);
		return is_array($result) ? $result : array();
	}
	
	private static function determineSuccess($code, $result, $http_method) {
		switch($http_method) {
			case 'POST':
			case 'DELETE':
				return ($code === 200 && $result['status'] == 'ok') ? true : false;
				break;
			
			case 'GET':
				return ($code === 200) ? true : false;
				break;
			
			default:
				return ($code === 200) ? true : false;
				break;
		}
	}
	
	public function getResponseCode() {
		return $this->responseCode;
	}
	
	public function setResponseCode($responseCode) {
		$this->responseCode = $responseCode;
		return $this;
	}
	
	public function getResponseBody() {
		return $this->responseBody;
	}
	
	public function setResponseBody($responseBody) {
		$this->responseBody = $responseBody;
		return $this;
	}
	
	public function isSuccess() {
		return $this->success;
	}
	
	public function setSuccess($success) {
		$this->success = $success;
		return $this;
	}
	
	public function getResult() {
		return $this->result;
	}
	
	public function setResult($result) {
		$this->result = $result;
		return $this;
	}
}
