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
	
	/**
	 * {@inheritdoc}
	 */
	protected function validate()
	{
		$this->validateCriteria();
	
		parent::validate();
	}
	
	/**
	 * Check if criteria is an array, and convert to string if so.
	 */
	protected function validateCriteria() 
	{
		$criteria = $this->get('criteria');
		if( $criteria && is_array($criteria))
		{
			$str = "";
			foreach($criteria as $key => $value) {
				if(is_array($value))
					$value = implode(",", $value);
				
				$str .= $key . "=" . $value;
			}
			
			$this->set('criteria', $str);
		}
	}
}