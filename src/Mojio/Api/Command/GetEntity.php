<?php

namespace Mojio\Api\Command;

use Mojio\Api\Exception\ResponseException;

class GetEntity extends MojioCommand
{	
	/**
	 * {@inheritdoc}
	 */
	protected function process()
	{
		if ($this->getResponse()->isSuccessful()) {
			$class = self::getEntityClass( $this->get('type') );
			$this->result = new $class( $this->getResponse()->json() );
		}else{
			throw new ResponseException( $this->getResponse() );
		}
	}
}