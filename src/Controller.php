<?php

namespace Art4\IsYouthwebStillOnline;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Controller class
 */
class Controller
{
	/**
	 * Interop\Container\ContainerInterface $container
	 */
	private $container;

	/**
	 * Constructor
	 *
	 * @param Interop\Container\ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	/**
	 * Zeigt die Startseite an
	 *
	 * @param ServerRequestInterface $request
	 * @param ResponseInterface $response
	 * @param array $args
	 *
	 * @return ResponseInterface $response
	 */
	public function getIndex(ServerRequestInterface $request, ResponseInterface $response, $args)
	{
		$em = $this->container['em'];

		$stats = $em->getRepository(Model\StatsAccount::class)->findOneBy(
			[],
			['created_at' => 'desc']
		);

		$this->container->view->render($response, 'index.twig', [
			'user_total' => ( is_object($stats) ) ? $stats->getUserTotal() : 0,
			'user_online' => ( is_object($stats) ) ? $stats->getUserOnline() : 0,
		]);

		return $response;
	}
}
