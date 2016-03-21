<?php

namespace Scraper\Library;

use Silex\Application;

class Council extends AbstractScraper implements ScraperInterface
{
    public function retrieveData(string $url): array
    {
        $crawler = $this->crawler->request('GET', $url);
        return $crawler->filter('h2 > a')->each(function ($node) use ($crawler) {
            $title = trim($node->text());
            $link = $crawler->selectLink($node->text())->link();

            $result = [
                'title' => $title,
                'url' => $link->getUri(),
            ];
            $details = $this->scrapeDetails($link->getUri());
            return $result + $details;
        });
    }

    private function scrapeDetails(string $url): array
    {
        $result = [];
        $crawler = $this->crawler->request('GET', $url);
        $crawler->filter('.newTwoColumnData .multiTitle')->each(function ($node) use (&$result) {
            $label = trim(strtolower($node->text()));
            $value = trim($node->nextAll()->text());
            $result[$label] = $value;
        });
        return $result;
    }
}