<?php

namespace Mojio\Test;

use Mojio\Api\Client;

ini_set('display_errors',true);

require '../vendor/autoload.php';

$appId = 'f201b929-d28c-415d-9b71-8112532301cb';
$secret =  'f0927a0a-386b-4148-be8d-5ffd7468ea6b';
$token = '';

if($appId && $secret) {
    try {
        $client = Client::factory(array(
            'host' => 'api.moj.io',
            'app_id' => $appId,
            'secret_key' => $secret,
            'token' => $token,
        ));
        
        if(!$token)
        {
            $client->login(array(
                'userOrEmail' => 'anonymous@moj.io',
                'password' => 'Password007',
            ));
        }
        // get some users.
        $results = $client->getList(array(
            'type'=> 'users'
        ));

        foreach( $results as $user )
        {
            $result = $client->getEntity( array(
                'type' => 'users',
                'id' => $user['_id']
            ));

            var_dump($result);
        }

        // get the mojio for the user.
        echo 'MOJIOS:   ';
        $results = $client->getMojios(array(
            'limit' => 15,
            'offset' => 0
        ));

        foreach( $results as $mojio )
        {
            $result = $client->getEntity( array(
                'type' => 'mojios',
                'id' => $mojio['_id']
            ));
            var_dump($result);
        }

        // get the vehicle for the user.
        echo 'VEHICLES:   ';
        $results = $client->getVehicles(array(
            'limit' => 15,
            'offset' => 0
        ));

        $id='';
        foreach( $results as $vehicle )
        {
            $result = $client->getEntity( array(
                'type' => 'vehicles',
                'id' => $vehicle['_id']
            ));
            $id = $result->data['Id'];
            var_dump($result);
        }

        echo 'TRIPS:   ';
        $results = $client->getTrips(array(
            'limit' => 15,
            'offset' => 0,
            'sortBy' => 'StartTime',
            'desc' => true,
            'criteria' => 'VehicleId='.$id
        ));
//
        foreach( $results as $trip )
        {
            $result = $client->getEntity( array(
                'type' => 'trips',
                'id' => $trip['_id']
            ));
            $id = $result->data['Id'];

            var_dump($result);
            break;
        }

        echo 'EVENTS:   for trip '.$id."    ";
        $results = $client->getList(array(
            'type' => 'events',
            'limit' => 15,
            'offset' => 0,
            'sortBy' => 'Time',
            'desc' => true,
            'criteria' => 'TripId='.$id
        ));
//
        foreach( $results as $event )
        {
            $result = $client->getEntity( array(
                'type' => 'trips',
                'id' => $event['_id']
            ));

            var_dump($result);
        }
    }
    catch( \Guzzle\Http\Exception\ServerErrorResponseException $r )
    {
    	var_dump( $r->getRequest() . "" );
    	var_dump( $r->getMessage() );
    	var_dump( $r->getResponse()->getMessage() );
    }
    catch( \Exception $e )
    {
    	var_dump($e);
    }
}

