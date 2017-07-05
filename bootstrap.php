<?php

require 'vendor/autoload.php';

define('DS', DIRECTORY_SEPARATOR);
define('PUBLICPATH', __DIR__.DS.'public'.DS);
define('ROOTPATH', realpath(__DIR__).DS);

$config_path = ROOTPATH . 'config';
$env = getenv('SLIM_ENV') ?: 'development';

$config = new Art4\IsYouthwebStillOnline\Config($config_path, $env);

$app = new \Slim\App($config->getAll());

$container = $app->getContainer();

// Register entity manager on container
$container['em'] = function ($container)
{
	$db_settings = $container['settings']['database'];

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

	$is_dev_mode = ($container['settings']['environment'] !== 'production');

	$cache = null;

	if ( ! $is_dev_mode )
	{
		$pool = new $container['cachepool'];
		$cache = new DoctrineCacheBridge($pool);
	}

	$config = \Doctrine\ORM\Tools\Setup::createConfiguration($is_dev_mode, $settings['proxy_dir'], $cache);
	$config->setMetadataDriverImpl(new \Doctrine\Common\Persistence\Mapping\Driver\StaticPHPDriver($settings['entity_pathes']));

	return \Doctrine\ORM\EntityManager::create($settings['connection'], $config);
};

// Register component on container
$container['view'] = function ($container)
{
	$view = new \Slim\Views\Twig(
		$container['settings']['views']['twig']['template_path'],
		$container['settings']['views']['twig']['environment']
	);

	// Instantiate and add Slim specific extension
	$basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
	$view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

	return $view;
};

// Register cachepool on container
$container['cachepool'] = function ($container)
{
	$filesystemAdapter = new \League\Flysystem\Adapter\Local(
		$container['settings']['cachepool']['cache_path']
	);

	$filesystem = new \League\Flysystem\Filesystem($filesystemAdapter);

	$pool = new \Cache\Adapter\Filesystem\FilesystemCachePool($filesystem, '/');

	return $pool;
};

// Add routes to app
foreach ($container['settings']['routes'] as $pattern => $target)
{
	foreach ($target as $method => $callable)
	{
		$app->map([$method], $pattern, $callable);
	}
}

return $app;
