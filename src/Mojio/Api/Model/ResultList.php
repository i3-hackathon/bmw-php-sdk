<?php

namespace Mojio\Api\Model;

class ResultList implements \ArrayAccess, \Iterator {
	public $data = array();
	public $entities = array();
	public $_pos = 0;
	
	public function __construct( $data , $class )
	{
		foreach( $data['Data'] as $entity )
		{
			$this->entities[] = new $class( $entity );
		}
		
		$this->totalRows = $data['TotalRows'];
		$this->offset = $data['Offset'];
		$this->pageSize = $data['PageSize'];
	}
	
	public function toArray()
	{
		return $this->entities;
	}
	
	public function current (){
		return $this->entities[ $this->_pos ];
	}
	
	public function key (){
		return $this->_pos;
	}
	
	public function next (){
		++$this->_pos;
	}
	
	public function rewind (){
		$this->_pos = 0;
	}
	
	public function valid (){
		return isset( $this->entities[ $this->_pos ] );
	}
	
	public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			$this->entities[] = $value;
		} else {
			$this->entities[$offset] = $value;
		}
	}
	public function offsetExists($offset) {
		return isset($this->entities[$offset]);
	}
	public function offsetUnset($offset) {
		unset($this->entities[$offset]);
	}
	public function offsetGet($offset) {
		return isset($this->entities[$offset]) ? $this->entities[$offset] : null;
	}
} 