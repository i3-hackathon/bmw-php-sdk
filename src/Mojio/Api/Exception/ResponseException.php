<?php

namespace Mojio\Api\Exception;

class ResponseException extends \Guzzle\Service\Exception\CommandException
{
	protected $_response;
	
	public function __construct( $response )
	{
		$this->_response = $response;
		
		parent::__construct();
	}
	
	public function getResponse()
	{
		return $this->_response;
	}
	
	public function getErrorCode()
	{
		return $this->getResponse()->getErrorCode();
	}
}