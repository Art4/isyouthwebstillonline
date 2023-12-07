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
					'dsn'        => 'sqlite:'.ROOTPATH.'cache'.DS.'db-dev.sq3', // or mysql:host=localhost;dbname=db-name
					'username'   => '',
					'password'   => '',
					'persistent' => false,
				],
				'identifier'   => '`',
				'table_prefix' => '',
				'charset'      => 'utf8mb4',
				'collation'    => 'utf8mb4_unicode_ci',
				'enable_cache' => true,
			],
			'doctrine2' => [
				'proxy_dir'       => ROOTPATH.'cache'.DS.'doctrine2_proxies',
				'entity_pathes'   => [
					ROOTPATH.'src'.DS.'Model',
				],
				'migrations'      => [
					'table_storage' => [
						'table_name' => 'doctrine_migration_versions',
					],
					'migrations_paths' => [
						'Art4\IsYouthwebStillOnline\DoctrineMigrations' => ROOTPATH.'migrations'.DS.'doctrine2'.DS,
					],
					'check_database_platform' => true,
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
