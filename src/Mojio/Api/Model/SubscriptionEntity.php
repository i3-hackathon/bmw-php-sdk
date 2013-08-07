<?php

namespace Mojio\Api\Model;

use Mojio\Api\Exception\Exception;

class SubscriptionEntity extends Entity
{
	public static $type = 'subscription';
	public static $defaultCallback = null;
	
    static public function factory($event = null, $type = null, $id = null, $callback = null ) {
    	if( $type instanceof Entity )
    	{
    		$callback = $id;
    		 
    		$id = $type->getId();
    		$type = $type->getType();
    	}
    	
    	$sub = new self(array(
    		'ChannelType' => 'Post',
    		'EntityType' => $type,
    		'EntityId' => $id,
    		'Event' => $event
    	));
    	 
    	$sub->setCallback( $callback );
    	
    	return $sub;
    }
   
    static public function setDefaultCallback( $url )
    {
	   	self::$defaultCallback = $url;
    }
    
    public function setCallback( $url = null )
    {
    	if( !$url )
    		$url = self::$defaultCallback;
    	
    	if( $url == null || !filter_var( $url, FILTER_VALIDATE_URL) )
    		throw new Exception("Invalid Callback URL");
    	
    	$this->ChannelTarget = $url;
    	
    	return $this;
    }
}