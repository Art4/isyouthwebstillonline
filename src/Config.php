<?php

namespace Art4\IsYouthwebStillOnline;

/**
 * Config class
 */
class Config
{
	private $base_path;

	private $env = 'development';

	public function __construct($path, $env = null)
	{
		$this->base_path = strval($path);

		if ( $env !== null )
		{
			$this->env = strval($env);
		}
	}

	public function getAll()
	{
		$base_config_path = rtrim($this->base_path, \DIRECTORY_SEPARATOR);
		$base_config_path .= \DIRECTORY_SEPARATOR;

		$base_config = require($base_config_path . 'config.php');
		$base_config['settings']['environment'] = $this->env;

		$env_config_path = $base_config_path . $this->env . \DIRECTORY_SEPARATOR . 'config.php';
		$env_config = [];

		if ( file_exists($env_config_path) )
		{
			$env_config = require($env_config_path);
		}

		return array_replace_recursive($base_config, $env_config);
	}
}
