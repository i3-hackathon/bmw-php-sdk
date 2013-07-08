<?php

namespace Mojio\Api\Command;

use Mojio\Api\Model\Entity;
use Mojio\Api\Exception\ResponseException;

class DeleteEntity extends MojioCommand
{
	/**
	 * {@inheritdoc}
	 */
	protected function validate()
	{
		$entity = $this->get('entity');
		if( $entity )
			if( $entity instanceof Entity )
			{
				if( !$this->get('type') )
					$this->set('type', self::getController($entity) );
					
				if( $entity->getId() )
					$this->set('id',$entity->getId() );
			}else{
				throw new \Exception("Error");
			}
			
		parent::validate();
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