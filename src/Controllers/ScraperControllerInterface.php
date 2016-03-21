<?php

namespace Scraper\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

interface ScraperControllerInterface
{
    public function scrape(Application $app, Request $request);
}