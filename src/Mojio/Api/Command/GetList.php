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
		$this->validatePage();
		
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
	
	/**
	 * Check if criteria is an array, and convert to string if so.
	 */
	protected function validatePage()
	{
		$page = $this->get('page');
		$pageSize = $this->get('pageSize');
		
		if($page > 0)
		{
			$pageSize = $this->get('pageSize');
			
			$limit = $pageSize ?  $pageSize : 10;
			$offset = ($page-1) * $limit;
			
			$this->set('limit', $limit);
			$this->set('offset', $offset);
		}
	}
}