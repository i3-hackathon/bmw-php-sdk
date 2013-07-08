<?php

namespace Mojio\Api\Model;

use Mojio\Api\Exception\ResponseException;

class Entity implements \ArrayAccess
{
	public $data = array();
	
	public function __construct( $data )
	{
		$this->data = $data;
		
		if( !isset( $this->data['Id'] ) && isset( $this->data['_id'] ))
			$this->data['Id'] = $this->data['_id'];
	}
	
	public function __set($name, $value)
	{
		$this->data[$name] = $value;
	}
	
	public function __get($name)
	{
		return isset($this->data[$name] ) ? $this->data[$name] : null;
	}
	
	public function toArray()
	{
		return $this->data;
	}
	
	public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }
    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }
    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }
    public function offsetGet($offset) {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }
    
    public function getId()
    {
    	return $this->_id;
    }
}