<?php

namespace Mojio\Api\Command;

use Mojio\Api\Model\Entity;
use Mojio\Api\Exception\ResponseException;

class SetStored extends GetEntity
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
	protected function build()
	{
		parent::build();
		
		$value = $this->get('value');
		if( $value )
		{
			$request = $this->getRequest();
			$request->setBody(json_encode($value));
			
			if ($this->jsonContentType && !$request->hasHeader('Content-Type')) {
                $request->setHeader('Content-Type', $this->jsonContentType);
            }
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function process()
	{
		if ($this->getResponse()->isSuccessful()) {
			$this->result = true;
		}else{
			throw new ResponseException( $this->getResponse() );
		}
	}
}