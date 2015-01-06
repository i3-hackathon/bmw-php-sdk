**NOTICE:** This fork is modified to work in conjunction with the [BMW i3 Hackathon developer center](https://data.hackthedrive.com/) ONLY.  If you are not apart of the BMW i3 hackathon, please refer to [mojio-php-client](https://github.com/mojio/mojio-php-client) project instead.

Mojio.Client
============

As PHP is the language of choice for many of you developers out there, we have put together a simplified client built on top of [Guzzle](http://guzzlephp.org/) to help you get started.  This client is still very much in it's alpha stages, we appologize for any bugs and incomplete features.

Installation
============

Using Composer (recommended)
----------------------------

The client has been added to packagist under the name mojio/mojio and can be included in your project using [Composer](http://getcomposer.org/).

1. First you will need to add "mojio/mojio" as a dependency in your composer.json file (currently only the dev-master is available, more stable versioning coming soon):
    ```json
    {
        "require": {
          "mojio/mojio": "~1.0"
        }
    }
    ```

2. Next you will need to download an install Composer and dependancies:

    ```Batchfile
    curl -sS https://getcomposer.org/installer | php
    php composer.phar install
    ```

3. Lastly, you need to include the Composer autoloader in your bootstrap:

    ```php
    require '[/path/to/vendor]/autoload.php';
    ```


From Source (GitHub)
--------------------
If you do not want to use Composer, you can download or checkout the complete source off github.  You must also download or checkout [Guzzle](https://github.com/guzzle/guzzle)


Getting Started
===============

To begin developing with our PHP client, you will need your very own application ID and secret key.  First you will need to create an account and login to the [BMW developer center](https://data.hackthedrive.com/).

Once you have logged in, you can create a new Application.  From here, you will want to copy the Application ID and the Secret Key, these will be required to initialize the MOJIO client


Initializing the Client
-----------------------

To get started using the client, instantiate a new instance of the MOJIO client class.  This is where you will need to pass in the Application ID and Secret Key, as well as the developer environment you are using (Sandbox, or Live).

```php
use Mojio\Api\Client;

require '[path/to/vendor]/autoload.php';

$appId = "{APPID}";
$secretKey = "{SecretKey}";

$client = Client::factory(array(
        'base_url' => Client::SANDBOX,  // or Client::LIVE
        'app_id' => $appId,
        'secret_key' => $secretKey
));

// ...
```


Authenticate a Mojio User
-------------------------

Now that your MojioClient is associated with your app, you can get started making some API calls.  However, many of our API calls also require an authorized user to be associated with the client session.  A user can grant you access using our OAuth2 service, and client calls.

```php
// ...
<<<<<<< HEAD
// Authenticate specific user
$client->login(array(
    'userOrEmail' => 'demo@example.com',
    'password' => 'mypassword',
));
	
=======
// Set the redirect URI to point to this exact same script.
$redirectUri = (isset($_SERVER['HTTPS']) ? 'https' : 'http')
                   . '://' . $_SERVER['HTTP_HOST'] 
                   . strtok($_SERVER['REQUEST_URI'], '?');
                   
if(!isset($_GET['code'])) {
    // Initiate an OAuth request
    header('Location: '.$client->getAuthorizationUrl($redirectUri));
    exit;
} else {
    // Handle the OAuth response
    $client->authorize($redirectUri, $_GET['code']);
}

>>>>>>> da42bcc956e02bd4a8d9327ba5f8bee5528155a6
// ...
// Logout user.
$client->logout();
```

Please note, you must add the ***$redirectUri*** to the allowed ***Redirect URIs*** in your application settings on the [Developer Center](https://data.hackthedrive.com/account/apps).


Fetching Data
-------------

To retrieve a set of a particular MOJIO entities, you can use the "GET" method.  The returned results will depend on what user and application your client session is authorized as. Lists of data will be returned in a paginated form.  You are able to set the page size and request a particular page.  In order to keep response times fast, it is recommended to keep the page size low.

```php
// ...
// Fetch first page of 15 users
$results = $client->getTrips(array(
    'pageSize' => 15,
    'page' => 1
));

foreach( $results as $trip )
{
    // Do something with each trip
    // ...
}
```

Fetch a specific Entity
-----------------------

By passing in the ID of an entity (often a GUID), you can request a single MOJIO entity from our API.

```php
// ...
$mojioId = "0a5123a0-7e70-12d1-a5k6-28db18c10200";
	
// Fetch mojio from API
$mojio = $client->getMojio(array(
    "id" => $mojioId
));
	
// Do something with the mojio data
// ...
```

Update an Entity
----------------

If you want to update and save an entity, you need to first load the entity from the API, make your changes, and then post it back.  Typically only the owner of an entity will be authorized to save changes and not all properties of an entity will be editable (for example, for an App, only the Name and Description properties can be changed).

```php
// ...
$appId = "0a5123a0-7e70-12d1-a5k6-28db18c10200";
	
// Fetch app from API
$app = $client->getApp(array(
    'id' => $appId
));
	
// Make a change
$app->Name = "New Application Name";
	
// Save the changes
$client->saveEntity(array(
    'entity' => $app
));
```

Get a list of child entities
----------------------------

If you want to fetch all the entities associated with another entity, you can call the GetBy method.  For example, if you want to fetch all the events associated with a particular MOJIO device.

```php
use Mojio\Api\Model\MojioEntity;
use Mojio\Api\Model\EventEntity;

    // ...
    $mojioId = "0a5123a0-7e70-12d1-a5k6-28db18c10200";
	
    // Fetch mojio's events
    $events = $client->getList(array(
        "type" => MojioEntity::getType(),
        "id" => $mojioId,
        "action" => EventEntity::getType()
    ));
	
    // Or, alternatively
    $mojio = $client->getMojio(array( 'id' => $mojioId ));
    $events = $client->getList(array(
        'entity' => $mojio,
        'action' => EventEntity::getType()
    ));

    // ...
```

Using the Mojio Storage
-----------------------

With the MOJIO API, you are able to store your own private data on our servers as key value pairs.  These key value pairs will only be accessible by your application, and you can associate them with any MOJIO entities (ex: MOJIO Device, Application, User, Trip, Event, Invoice, Product).

```php
use Mojio\Api\Model\UserEntity;

    // ...
    $userId = "0a5453a0-7e70-16d1-a2w6-28dl98c10200";  // Some user's ID
    $key = "EyeColour";	// Key to store
    $value = "Brown"; 	// Value to store

    // Save user's eye colour
    $client.setStored( array(
        'type' => UserEntity::getType(),
        'id' => $userId,
        'key' => $key
        'value' => $value
    ));

    // ...
    // Retrieve user's eye colour
    $client.getStored( array(
        'type' => UserEntity::getType(),
        'id' => $userId,
        'key' => $key
    ));
```

Requesting Event Updates
------------------------

Instead of continuously polling the API to check for updates, you can request our API send a POST request to an endpoint of your choosing everytime certain events are received.

```php
    $mojioId = "0a5123a0-7e70-12d1-a5k6-28db18c10200";

    $sub = SubscriptionEntity::factory(
              'IgnitionOn', // Event Type to receive
              'Mojio',      // Subscription Type
              $mojioId,     // Entity ID
              "http://my-domain-example.com/receiver.php" // Location to send events
    );

    $client->newEntity( array('entity' =>$sub) );
```

And in your "receiver.php" file you will want to process any incoming events:
```php
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $raw = file_get_contents('php://input');
  $event = json_decode( $raw );
  
  // ... Do something with the event!
}
?>
```
