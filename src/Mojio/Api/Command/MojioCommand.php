<?php

namespace Mojio\Api\Command;

use Mojio\Api\Model\Entity;

class MojioCommand extends \Guzzle\Service\Command\OperationCommand
{
	protected function validateEntity()
	{
		$entity = $this->get('entity');
		if( $entity && $entity instanceof Entity )
		{
			if( !$this->get('type') )
				$this->set('type', $entity->getType() );
		
			if( $entity->getId() )
				$this->set('id',$entity->getId() );
		}
	}
	
	protected static function getEntityClass( $type )
	{
		return Entity::getClass( $type );
	}
}