<?php

namespace Mojio\Api\Command;

use Mojio\Api\Model\Entity;

class MojioCommand extends \Guzzle\Service\Command\OperationCommand
{
	protected $resultTypeOrder = array('return_type','action','type');
	
	protected function getReturnType()
	{
		foreach( $this->resultTypeOrder as $name )
		{
			$v = $this->get($name);
			if( !$v ) continue;
				
			$c = Entity::getClass( $v );
			if( $c ) return $c;
		}
	
		throw new \Exception( "Unknown result class" );
	}
	
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