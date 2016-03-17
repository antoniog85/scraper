<?php

namespace Scraper\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;

class CouncilController
{
    public function test()
    {
        return new JsonResponse([['ok' => 'test'], 'no']);
    }
}