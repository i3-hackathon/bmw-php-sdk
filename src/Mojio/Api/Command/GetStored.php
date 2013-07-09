<?php

namespace Mojio\Api\Command;

use Mojio\Api\Model\Entity;
use Mojio\Api\Exception\ResponseException;

class GetStored extends GetEntity
{
	protected $jsonContentType = 'application/json';
	
	/**
	 * {@inheritdoc}
	 */
	protected function validate()
	{
		$this->validateEntity();
		
		parent::validate();
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function process()
	{
		if ($this->getResponse()->isSuccessful()) {
			$this->result = $this->getResponse()->json();
		}else{
			throw new ResponseException( $this->getResponse() );
		}
	}
}