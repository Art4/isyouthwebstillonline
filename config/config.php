<?php

return [
	'settings' => [
		'httpVersion' => '1.1',
		'responseChunkSize' => 4096,
		'outputBuffering' => 'append',
		'determineRouteBeforeAppMiddleware' => false,
		'displayErrorDetails' => false,
		'addContentLengthHeader' => true,
		'routerCacheFile' => false,
		'environment' => 'development', // production|development
		'database' => [
			'active' => 'default',
			'default' => [
				'type'        => 'pdo',
				'connection'  => [
					'dsn'        => 'sqlite::memory:',
					'username'   => '',
					'password'   => '',
					'persistent' => false,
				],
				'identifier'   => '`',
				'table_prefix' => '',
				'charset'      => 'utf8',
				'collation'    => 'utf8_unicode_ci',
				'enable_cache' => true,
			],
			'doctrine2' => [
				'proxy_dir'       => ROOTPATH.'cache'.DS.'doctrine2_proxies',
				'entity_pathes'   => [
					ROOTPATH.'src'.DS.'Model',
				],
				'migrations'      => [
					'migrations_namespace' => 'Art4\IsYouthwebStillOnline\DoctrineMigrations',
					'table_name' => 'doctrine_migration_versions',
					'migrations_directory' => ROOTPATH.'migrations'.DS.'doctrine2'.DS,
				],
			],
		],
		'views' => [
			'twig' => [
				'template_path' => ROOTPATH.'templates'.DS,
				'environment' => [
					'auto_reload' => false,
					'cache_path' => ROOTPATH.'cache'.DS.'twig'.DS,
				],
			],
		],
		'routes' => [
			'/' => [
				'GET' => '\Art4\IsYouthwebStillOnline\Controller:getIndex',
			],
		],
	],
];
