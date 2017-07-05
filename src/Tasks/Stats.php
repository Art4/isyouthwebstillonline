<?php
/**
 * Page-level DocBlock
 */

namespace Fuel\Tasks;

/**
 * Task zum Updaten der Statistik
 *
 * @package     App
 * @author      Artur Weigandt <art4@wlabs.de>
 * @since       2014-10-17
 */

class Stats
{

	/**
	 * This method gets the last stats
	 */
	public static function update()
	{
		$url = 'https://youthweb.net/index.php?action=account&cat=stats';
		//$url = 'http://localhost/youthweb/index.php?action=account&cat=stats';

		$curl = \Request::forge($url, 'curl');
		$curl->set_method('get');
		$curl->set_auto_format(true);

		try
		{
			$result = $curl->execute();
		}
		catch ( \RequestStatusException $e )
		{
			\Log::error('The request via CURL to ' . $url . ' gives me a ' . $e->getCode() . ' status code.');
			return;
		}
		catch ( \RequestException $e )
		{
			\Log::error('The request via CURL to ' . $url . ' gives me a ' . $e->getCode() . ' status code.');
			return;
		}

		$response = $result->response();

		$data = $response->body();

		$stats = new \Model\Account_Stats();
		$stats->user_total = $data['user_total'];
		$stats->user_online = $data['user_online'];
		$stats->created_at = time();
		$stats->save();

		\Log::info('CURL-Request to ' . $url . ' was succesfully processed.');
	}

}
