<?php

namespace Art4\IsYouthwebStillOnline\Tools\Console\Command;

use Art4\IsYouthwebStillOnline\Model\StatsAccount;
use Doctrine\ORM\Mapping\MappingException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

/**
 * Task zum Updaten der Statistik
 */
class StatsUpdateCommand extends Command
{
	/**
	 * {@inheritdoc}
	 */
	protected function configure()
	{
		$this
			->setName('stats:update')
			->setDescription('Update stats about total and online user.')
			->setHelp(<<<EOT
The <info>%command.name%</info> runs a request against the Youthweb servers
and saves the stats into the database.
EOT
		);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		/* @var $entityManager \Doctrine\ORM\EntityManager */
		$entityManager = $this->getHelper('em')->getEntityManager();

		$resource = (new \Youthweb\Api\Client)->getResource('stats');

		try
		{
			$result = $resource->show('account');
		}
		catch ( \Exception $e )
		{
			throw new \Exception(
				'Something went wrong while request account stats: ' . $e->getMessage()
			);

			return 1;
		}

		$stats = new StatsAccount();
		$stats->setUserTotal($result->get('data.attributes.user_total'));
		$stats->setUserOnline($result->get('data.attributes.user_online'));
		$stats->setCreatedAt(time());

		$entityManager->persist($stats);
		$entityManager->flush();

		$output->writeln('Account stats was succesfully saved.');

		return 0;
	}
}
