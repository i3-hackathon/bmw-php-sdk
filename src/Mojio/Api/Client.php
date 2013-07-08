<?php

namespace Mojio\Api;

use Guzzle\Common\Exception\GuzzleException;
use Guzzle\Common\Collection;
use Guzzle\Service\Description\ServiceDescription;
use Guzzle\Common\Event;

class Client extends \Guzzle\Service\Client
{
	/**
	 * @var string AWS access key ID
	 */
	protected $appId;
	
	/**
	 * @var string AWS secret key
	 */
	protected $secretKey;
	
	/**
	 * @var string Service version
	 */
	protected $version;
	
	/**
	 * @var token Token
	 */
	public $token;
	
	/**
	 * Factory method to create a new S3 client
	 *
	 * @param array|Collection $config Configuration data. Array keys:
	 *    base_url - Base URL of web service.  Default: {{scheme}}://{{region}}/
	 *    scheme - Set to http or https.  Defaults to http
	 *    region - AWS region.  Defaults to s3.amazonaws.com
	 *    access_key - AWS access key ID.  Set to sign requests.
	 *    secret_key - AWS secret access key. Set to sign requests.
	 *
	 * @return S3Client
	 */
	public static function factory($config = array() )
	{
		$defaults = array(
				'base_url' => '{{scheme}}://developer.moj.io/{{version}}',
				'app_id' => null,
				'secret_key' => null,
				'version' => 'v1'
		);
		$required = array('base_url', 'app_id', 'secret_key','version');
		$config = Collection::fromConfig($config, $defaults, $required);
		
		$client = new self($config->get('base_url'), $config);
		
		// Attach a service description to the client
		$description = ServiceDescription::factory(__DIR__ . '/service.json');
		$client->setDescription($description);
		
		$tokenId = $config->get('token');
		
		if( $tokenId )
			try {
				$token = $client->getToken(array('id' => $tokenId) );

				$client->token = $token->get('_id');
			}catch( GuzzleException $e ){
			}
		
		if( !$client->token )
			try {
				$token = $client->Begin( array(
					'appId' => $config->get('app_id') , 
					'secretKey' => $config->get('secret_key')
				));
				
				var_dump( $token );
				
				$client->token = $token['_id'];
			}catch( GuzzleException $e ){
				var_dump( $e->getMessage() );
				die('could not connect');
			}
		
		$client->getEventDispatcher()->addListener('request.before_send', function( Event $event ) {
			$request = $event['request'];
			$token = $request->getClient()->token;
			
			$request->setHeader('MojioApiToken',$token);
		});
		
		return $client;
	}
}