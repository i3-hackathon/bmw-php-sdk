<?php

namespace Mojio\Api\Command;

use Mojio\Api\Model\ResultList;
use Mojio\Api\Exception\ResponseException;
use Mojio\Api\Model\Entity;

class GetList extends MojioCommand
{	
	/**
	 * {@inheritdoc}
	 */
	protected function process()
	{
		parent::process();
	
		if ($this->getResponse()->isSuccessful()) {
			$class = $this->getReturnType();
			
			$this->result = new ResultList( $this->getResponse()->json() , $class );
		}else{
			throw new ResponseException( $this->getResponse() );
		}
	}
}