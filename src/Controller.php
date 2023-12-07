<?php

namespace Art4\IsYouthwebStillOnline;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;

/**
 * Controller class
 */
class Controller
{
	/**
	 * @var EntityManager
	 */
	private $em;

	/**
	 * @var Twig
	 */
	private $twig;

	public function __construct(EntityManager $em, Twig $twig)
	{
		$this->em = $em;
		$this->twig = $twig;
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
		$stats = $this->em->getRepository(Model\StatsAccount::class)->findOneBy(
			[],
			['created_at' => 'desc']
		);

		$this->twig->render($response, 'index.twig', [
			'user_total' => ( is_object($stats) ) ? $stats->getUserTotal() : 0,
			'user_online' => ( is_object($stats) ) ? $stats->getUserOnline() : 0,
		]);

		return $response;
	}
}
