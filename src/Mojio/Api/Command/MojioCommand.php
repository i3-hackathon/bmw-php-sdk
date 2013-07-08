<?php

namespace Mojio\Api\Command;

class MojioCommand extends \Guzzle\Service\Command\OperationCommand
{
	protected static function getEntityClass( $type )
	{
		switch( $type ){
			case 'user':
			case 'users':
				return "\\Mojio\\Api\\Model\\UserEntity";
			case 'app':
			case 'apps':
				return "\\Mojio\\Api\\Model\\AppEntity";
			case 'mojio':
			case 'mojios':
				return "\\Mojio\\Api\\Model\\MojioEntity";
			case 'token':
			case 'tokens':
			case 'login':
			case 'logins':
				return "\\Mojio\\Api\\Model\\TokenEntity";
			default:
				return "\\Mojio\\Api\\Model\\Entity";
		}
	}
	
	protected static function getController( $entity )
	{
		switch( get_class( $entity ) )
		{
			case "Mojio\\Api\\Model\\UserEntity":
				return 'users';
			case 'Mojio\\Api\\Model\\AppEntity':
				return 'apps';
			case 'Mojio\\Api\\Model\\MojioEntity':
				return 'mojios';
			case 'Mojio\\Api\\Model\\TripEntity':
				return 'trips';
			case 'Mojio\\Api\\Model\\ProductEntity':
				return 'products';
			case 'Mojio\\Api\\Model\\InvoiceEntity':
				return 'orders';
			case 'Mojio\\Api\\Model\\TokenEntity':
				return 'login';
			default: 
				throw new \Exception("Unkown Type");
		}
	}
}