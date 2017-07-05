<?php
/**
 * Page-level DocBlock
 */

namespace Model;

use Orm\Model as Model;

/**
 * Model für die Account Statistiken
 *
 * @package     App
 * @author      Artur Weigandt <art4@wlabs.de>
 * @since       2014-10-17
 */

class StatsAccount extends Model
{

	protected static $_table_name = 'account_stats';

	protected static $_primary_key = array('id');

	protected static $_properties = array(
		'id',
		'user_total',
		'user_online',
		'created_at'
	);

	protected static $_conditions = array(
		//'order_by' => array('created_at' => 'desc'),
	);

}
