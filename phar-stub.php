<?php

require_once 'vendor/symfony/class-loader/Symfony/Component/ClassLoader/UniversalClassLoader.php';

if (!defined('MOJIO_FILE_PREFIX')) {
	define('MOJIO_FILE_PREFIX', 'phar://mojio.phar');
}

Phar::mapPhar('mojio.phar');

$classLoader = new Symfony\Component\ClassLoader\UniversalClassLoader();
$classLoader->registerNamespaces(array(
	'Mojio' => MOJIO_FILE_PREFIX . '/src',
    'Guzzle' => MOJIO_FILE_PREFIX . '/vendor/guzzle/guzzle/src',
    'Symfony\\Component\\EventDispatcher' => MOJIO_FILE_PREFIX . '/vendor/symfony/event-dispatcher',
    'Doctrine' => MOJIO_FILE_PREFIX . '/vendor/doctrine/common/lib',
    'Monolog' => MOJIO_FILE_PREFIX .'/vendor/monolog/monolog/src'
));
$classLoader->register();

__HALT_COMPILER();
