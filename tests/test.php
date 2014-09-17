<?php

namespace Mojio\Test;

use Mojio\Api\Client;
use Mojio\Api\Model\SubscriptionEntity;

ini_set('display_errors',true);

require '../vendor/autoload.php';

session_start();

if(isset($_POST['appid']))
{
    $_SESSION['appId'] = $_POST['appid'];
    $_SESSION['secret'] = $_POST['secret'];
}

$appId = isset($_SESSION['appId']) ? $_SESSION['appId'] : null;
$secret = isset($_SESSION['secret']) ? $_SESSION['secret'] : null;
$token = isset($_SESSION['token']) ? $_SESSION['token'] : null;

if($appId && $secret) {
    try {
        $client = Client::factory(array(
            'host' => 'staging.api.moj.io',
            'app_id' => $appId,
            'secret_key' => $secret,
            'token' => $token,
        ));
        
        if(!$token)
        {
            $scheme = isset($_SERVER['HTTPS']) ? 'https' : 'http';
            $redirect = $scheme . '://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?');
            if(!isset($_GET['code'])) {  
                header('Location: '.$client->getAuthorizationUrl($redirect));
                exit;
            } else {
                $client->authorize($redirect, $_GET['code']);
                
                $_SESSION['token'] = $client->getTokenId();
            }
        }
        
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

echo <<<END
    <form method="POST">
        <input type="text" name="appid" value="$appId">
        <input type="text" name="secret" value="">
        <input type="submit">
    </form>
END;
