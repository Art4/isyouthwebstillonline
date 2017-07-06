<?php
/**
 * Page-level DocBlock
 */

namespace Art4\IsYouthwebStillOnline\Model;

use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Model für die Account Statistiken
 */

class StatsAccount
{
	/**
	 * loadMetadata
	 *
	 * @param ClassMetadata $metadata
	 * @codeCoverageIgnore
	 */
	public static function loadMetadata(ClassMetadata $metadata)
	{
		$metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_AUTO);

		$metadata->setPrimaryTable([
			'name' => 'account_stats',
		]);

		$metadata->mapField([
			'id' => true,
			'fieldName' => 'id',
			'columnName' => 'id',
			'type' => 'integer',
			'length' => 11,
		]);

		$metadata->mapField([
			'fieldName' => 'user_total',
			'columnName' => 'user_total',
			'type' => 'integer',
			'length' => 11,
		]);

		$metadata->mapField([
			'fieldName' => 'user_online',
			'columnName' => 'user_online',
			'type' => 'integer',
			'length' => 11,
		]);

		$metadata->mapField([
			'fieldName' => 'created_at',
			'columnName' => 'created_at',
			'type' => 'integer',
			'length' => 11,
		]);
	}

	private $id;

	private $user_total;

	private $user_online;

	private $created_at;

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return int
	 */
	public function getUserTotal()
	{
		return $this->user_total;
	}

	/**
	 * @param int $user_total
	 * @return void
	 */
	public function setUserTotal($user_total)
	{
		$this->user_total = (int) $user_total;
	}

	/**
	 * @return int
	 */
	public function getUserOnline()
	{
		return $this->user_online;
	}

	/**
	 * @param int $user_online
	 * @return void
	 */
	public function setUserOnline($user_online)
	{
		$this->user_online = (int) $user_online;
	}

	/**
	 * @return int
	 */
	public function getCreatedAt()
	{
		return $this->created_at;
	}

	/**
	 * @param int $created_at
	 * @return void
	 */
	public function setCreatedAt($created_at)
	{
		$this->created_at = (int) $created_at;
	}
}
