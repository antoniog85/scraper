<?php

namespace Scraper\Library;

use Goutte\Client;

abstract class AbstractScraper
{
    public $crawler;
    
    public function __construct(Client $crawler)
    {
        $this->crawler = $crawler;
    }
}