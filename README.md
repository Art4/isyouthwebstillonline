# Is Youthweb still online?

A simple website to save the user stats of Youthweb.

Build with Slim, Twig and Bootstrap.

## Requirements

- PHP >= 8.1
- Composer

## Configuration

Create the file `config/development/config.php` with this minimal configuration

```php
<?php

return [
	'settings' => [
		'displayErrorDetails' => true,
		'database' => [
			'active' => 'default',
			'default' => [
				'type'        => 'pdo',
				'connection'  => [
					'dsn'        => 'sqlite:'.ROOTPATH.'cache'.DS.'mydb.sq3',
					'username'   => '',
					'password'   => '',
					'persistent' => false,
				],
			],
		],
	],
];

```

## Installation

You need docker and docker compose.

Clone the repository or download and unzip the code into a folder. Run inside the folder:

```
docker compose up -d
docker compose exec -u 1000 php composer install
docker compose exec -u 1000 php php cli doctrine:migrations:migrate
```

This installs all dependencies.

Now point apache/nginx to `public/index.php` or use the PHP built in server:

```
docker compose exec -u 1000 php php -S 0.0.0.0:80 -t public/
```

You can now access the website under http://localhost:8200

## License

GPL3
