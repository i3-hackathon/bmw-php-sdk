<?php

namespace Mojio\Api\Command;

use Mojio\Api\Model\Entity;

class SaveEntity extends GetEntity
{
	protected $jsonContentType = 'application/json';
	
	/**
	 * {@inheritdoc}
	 */
	protected function validate()
	{
		$entity = $this->get('entity');
		if( $entity && $entity instanceof Entity )
		{
			if( !$this->get('type') )
				$this->set('type', self::getController($entity) );
				
			if( $entity->getId() )
				$this->set('id',$entity->getId() );
			
			foreach( $entity->data as $k => $v )
				$this->set( $k , $v );
		}else{
			throw new \Exception("Error");
		}
			
		parent::validate();
	}
	
	protected function build()
	{
		parent::build();
		
		$entity = $this->get('entity');
		if( $entity )
		{
			if( $entity instanceof Entity)
				$entity = $entity->toArray();
			
			$request = $this->getRequest();
			$request->setBody(json_encode($entity));
			
			if ($this->jsonContentType && !$request->hasHeader('Content-Type')) {
                $request->setHeader('Content-Type', $this->jsonContentType);
            }
		}
	}
}