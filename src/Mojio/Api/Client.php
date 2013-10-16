<?php

namespace Mojio\Api;

use Guzzle\Common\Exception\GuzzleException;
use Guzzle\Common\Collection;
use Guzzle\Service\Description\ServiceDescription;
use Guzzle\Common\Event;

class Client extends \Guzzle\Service\Client
{
	const LIVE = "https://developer.moj.io/v1";
	const SANDBOX = "http://sandbox.developer.moj.io/v1";
	
	/**
	 * @var string Mojio App ID
	 */
	protected $appId;
	
	/**
	 * @var string Mojio App secret key
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
	 * Factory method to create a new Mojio client
	 *
	 * @param array|Collection $config Configuration data. Array keys:
	 *    base_url - Base URL of web service.  Default: {{scheme}}://developer.moj.io/{{version}}
	 *    app_id - Mojio App ID
	 *    secret_key - Mojio App Secret Key
	 *    token - Optional Token ID
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

				$client->token;
			}catch( GuzzleException $e ){
			}
		
		if( !$client->token )
			try {
				$token = $client->begin( array(
					'appId' => $config->get('app_id') , 
					'secretKey' => $config->get('secret_key')
				));
				
				$client->token = $token;
			}catch( GuzzleException $e ){
				throw $e;
			}
		
		$client->getEventDispatcher()->addListener('request.before_send', function( Event $event ) {
			$request = $event['request'];
			$token = $request->getClient()->getTokenId();
			
			$request->setHeader('MojioApiToken',$token);
		});
		
		return $client;
	}
	
	public function getTokenId()
	{
		return $this->token ? $this->token->getId() : null;
	}
	
	public function isAuthenticated()
	{
		return $this->token && $this->token->UserId;
	}
	
	public function currentUser()
	{
		return $this->isAuthenticated() ? $this->getUser( $this->token->UserId ) :  null;
	}
}