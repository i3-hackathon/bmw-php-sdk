<?php

namespace Mojio\Api\Command;

use Mojio\Api\Exception\ResponseException;
use Mojio\Api\Model\Entity;

class GetEntity extends MojioCommand
{	
	/**
	 * {@inheritdoc}
	 */
	protected function process()
	{
		if ($this->getResponse()->isSuccessful()) {
			$class = $this->getReturnType();
			$this->result = new $class( $this->getResponse()->json() );
		}else{
			throw new ResponseException( $this->getResponse() );
		}
	}
}