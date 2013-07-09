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
			$returns = $this->get('returns') ? $this->get('returns') : $this->get('type');
			$this->result = Entity::factory( $returns, $this->getResponse()->json() );
		}else{
			throw new ResponseException( $this->getResponse() );
		}
	}
}