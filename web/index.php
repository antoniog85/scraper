<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

// config
$app['content_type'] = 'application/vnd.collection+json';
$app['api_version'] = '0.1';

$app->before(function (Request $request) {
    // accept json
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

// error handler
$app->error(function (\Exception $e, $code) use ($app) {
    return $app->json($e->getMessage(), $code);
});

$app->after(function (Request $request, Response $response) use ($app) {
    $collectionJson = new \Scraper\Library\MediaTypes\CollectionJson();
    $collectionJson->setVersion($app['api_version']);
    $collectionJson->setHref($request->getUri());
    $collectionJson->setError($response);
    $collectionJson->setItems($response);

//    $contentData = json_decode($response->getContent());
//    $responseFormatted['version'] = $app['api_version'];
//    $responseFormatted['href'] = $request->getUri();
//    if ($response->getStatusCode() > 299) {
//        $responseFormatted['error'] = [
//            'data' => $contentData,
//            'code' =>  $response->getStatusCode(),
//        ];
//    } else {
//        $responseFormatted['items'] = [$contentData];
//    }
    return $app->json(
        $collectionJson->render(),
        $response->getStatusCode(),
        [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET,POST,PUT,DELETE,OPTIONS',
            'Content-Type' => $app['content_type'],
        ]
    );
});

// data provider
//$app['recipe.dataProvider'] = $app->share(function ($app) {
//    return new Gousto\RecipeDataProvider($app);
//});

// routes
$app->get('/scraper/council','Scraper\Controllers\CouncilController::test');

$app['debug'] = true;
$app->run();