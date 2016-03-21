<?php

namespace Scraper\Controllers;

use Scraper\Library\Council;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

class CouncilController implements ScraperControllerInterface
{
    public function scrape(Application $app, Request $request)
    {
        $url = $request->get('url');
        if (empty($url)) {
            throw new MissingMandatoryParametersException('You must specify the url parameter');
        }
        $councilScraper = new Council($app['goutte']);
        return new JsonResponse($councilScraper->retrieveData($url));
    }

    public function persist(Application $app, Request $request)
    {
        $url = $request->get('url');
        if (empty($url)) {
            throw new MissingMandatoryParametersException('You must specify the url parameter');
        }
        $app['db']->executeQuery('TRUNCATE ' . getenv('MYSQL_DATABASE') . '.associations');
        /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
        $queryBuilder = $app['db']->createQueryBuilder();
        $councilScraper = new Council($app['goutte']);
        $data = $councilScraper->retrieveData($url);
        foreach ($data as $item) {
            //@todo create model
            $queryBuilder
                ->insert(getenv('MYSQL_DATABASE') . '.associations')
                ->values(
                    [
                        'title' => '?',
                        'url' => '?',
                        'contact' => '?',
                        'address' => '?',
                        'telephone' => '?',
                        'mobile' => '?',
                        'email' => '?',
                        'website' => '?',
                        'description' => '?',
                        'ward' => '?',
                    ]
                )
                ->setParameter(0, isset($item['title']) ? $item['title'] : null)
                ->setParameter(1, isset($item['url']) ? $item['url'] : null)
                ->setParameter(2, isset($item['contact']) ? $item['contact'] : null)
                ->setParameter(3, isset($item['telephone']) ? $item['telephone'] : null)
                ->setParameter(4, isset($item['mobile']) ? $item['mobile'] : null)
                ->setParameter(5, isset($item['address']) ? $item['address'] : null)
                ->setParameter(6, isset($item['email']) ? $item['email'] : null)
                ->setParameter(7, isset($item['website']) ? $item['website'] : null)
                ->setParameter(8, isset($item['description']) ? $item['description'] : null)
                ->setParameter(9, isset($item['ward']) ? $item['ward'] : null)
                ->execute()
            ;
        }
        return new JsonResponse($data);
    }
}