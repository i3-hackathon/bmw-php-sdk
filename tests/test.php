<?php

namespace Mojio\Test;

use Mojio\Api\Client;
use Mojio\Api\Model\SubscriptionEntity;

ini_set('display_errors',true);

require '../vendor/autoload.php';

$client = Client::factory(array(
			'base_url' => "http://localhost:2006/v1",
			'app_id' => $_GET['appid'],
			'secret_key' => $_GET['secret']
		));

try {
	$client->login(array(
			'username' => $_GET['user'],
			'password' => $_GET['pass'],
			));
	$results = $client->getList(array(
		'type'=> 'users'
	));
	$userId = null;

	foreach( $results as $user )
	{
		$result = $client->getEntity( array(
				'type' => 'users', 
				'id' => $user['_id']
		));
		
		$user->FirstName = "NewName";
		
		$userId = $user['_id'];
	}
	
	$test = $client->getAppAdmins( array('id' => $_GET['appid']) );

	$subscription = $client->newEntity(array(
		'entity' => SubscriptionEntity::factory('GPS','User',$userId,"http://mojio.local/tests/receive.php")
	));
}
catch( \Guzzle\Http\Exception\ServerErrorResponseException $r )
{
	var_dump( $r->getRequest() . "" );
	var_dump( $r->getMessage() );
	var_dump( $r->getResponse()->getMessage() );
}
catch( \Exception $e )
{
	var_dump( $e->getMessage() );
}