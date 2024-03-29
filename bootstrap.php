<?php

require 'vendor/autoload.php';

define('DS', DIRECTORY_SEPARATOR);
define('PUBLICPATH', __DIR__.DS.'public'.DS);
define('ROOTPATH', realpath(__DIR__).DS);

error_reporting(E_ALL);
ini_set('display_errors', 1);

$config_path = ROOTPATH . 'config';
$env = getenv('SLIM_ENV') ?: 'development';

$config = (new Art4\IsYouthwebStillOnline\Config($config_path, $env))->getAll();

// Register entity manager on container
$em = (function ($config)
{
	$db_settings = $config['settings']['database'];

	$connection = $db_settings['active'];

	if ( ! isset($db_settings['doctrine2']) )
	{
		throw new \Exception('Missing "doctrine2" key in DB settings');
	}

	if ( ! isset($db_settings[$connection]) or ! isset($db_settings[$connection]['connection']) )
	{
		throw new \Exception("No connection configuration for '$connection'");
	}

	$connection_settings = $db_settings[$connection];

	$settings = $db_settings['doctrine2'];

	// Required settings
	foreach ( ['proxy_dir', 'entity_pathes', 'migrations'] as $key )
	{
		if ( ! array_key_exists($key, $settings) )
		{
			throw new \Exception("'$key' not configured for connection '$connection'");
		}
	}

	// Translate DB config for doctrine
	$options = [];

	if ( isset($connection_settings['type']) )
	{
		$options['user'] = $connection_settings['connection']['username'];

		switch ($connection_settings['type'])
		{
			case 'pdo':
				$parts = explode(':', $connection_settings['connection']['dsn']);

				if ( ! in_array($parts[0], array('mysql', 'sqlite', 'pgsql', 'oci', 'sqlsrv')) )
				{
					throw new \Exception('Unsupported driver ' . $parts[0]);
				}

				$options['driver'] = 'pdo_' . $parts[0];

				// SQLite DSN has only a path
				if ( $parts[0] === 'sqlite' )
				{
					$path_parts = $parts;
					array_shift($path_parts);
					$options['path'] = implode(':', $path_parts);

					break;
				}

				$conf = explode(';', $parts[1]);

				foreach ( $conf as $opt )
				{
					$v = explode('=', $opt);
					$options[$v[0]] = $v[1];
				}

				break;

			default:
				throw new \Exception('Unsupported connection type ' . $connection_settings['type']);
		}
	}

	if ( isset($connection_settings['charset']) )
	{
		$options['charset'] = $connection_settings['charset'];
	}

	$options = array_filter($options);

	$settings['connection'] = array_merge($options, $connection_settings['connection']);

	$is_dev_mode = ($config['settings']['environment'] !== 'production');

	$pool = new \Symfony\Component\Cache\Adapter\FilesystemAdapter();
	$cache = \Doctrine\Common\Cache\Psr6\DoctrineProvider::wrap($pool);

	$em_configuration = \Doctrine\ORM\Tools\Setup::createConfiguration($is_dev_mode, $settings['proxy_dir'], $cache);
	$em_configuration->setMetadataDriverImpl(new \Doctrine\Persistence\Mapping\Driver\StaticPHPDriver($settings['entity_pathes']));

	return \Doctrine\ORM\EntityManager::create($settings['connection'], $em_configuration);
})($config);

// Register component on container
$twig = (function (array $config)
{
	$twig = \Slim\Views\Twig::create(
		$config['settings']['views']['twig']['template_path'],
		$config['settings']['views']['twig']['environment']
	);

	// Instantiate and add Slim specific extension
	// $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
	// $twig->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

	return $twig;
})($config);

$app = \Slim\Factory\AppFactory::create();

// Add routes to app
$controller = new \Art4\IsYouthwebStillOnline\Controller($em, $twig);

$app->get('/', [$controller, 'getIndex']);

return [
	'app' => $app,
	'config' => $config,
	'em' => $em,
];
