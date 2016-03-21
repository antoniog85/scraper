<?php

namespace Scraper\Library;

use Silex\Application;

interface ScraperInterface
{
    public function retrieveData(string $url): array;
}