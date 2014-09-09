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
		$this->validateSortOrder();
		
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
	 * Check if page/pageSize is being used, and convert to limit/offset.
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
	
	/**
	 * Make sure we use the string values for true and false when defining sort order.
	 */
	protected function validateSortOrder()
	{
		$desc = $this->get('desc');
		$this->set('desc', $desc ? 'true' : 'false');
	}
}