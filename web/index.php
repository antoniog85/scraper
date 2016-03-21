<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__.'/../vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();
$dotenv->required(['API_VERSION', 'DB_HOST', 'DB_DATABASE', 'DB_USER', 'DB_PASSWORD']);
$dotenv->required('APPLICATION_ENV')->allowedValues(['dev', 'prod']);

$app = new Silex\Application();

getenv('APPLICATION_ENV') == 'dev' && $app['debug'] = true;

$app->before(function (Request $request) {
    // accept json
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

// format the output
$app->after(function (Request $request, Response $response) use ($app) {
    $collectionJson = new \Scraper\Library\MediaTypes\CollectionJson();
    $collectionJson->setVersion(getenv('API_VERSION'));
    $collectionJson->setHref($request->getUri());
    $collectionJson->setError($response);
    $collectionJson->setItems($response);

    return $app->json(
        $collectionJson->render(),
        $response->getStatusCode(),
        [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET,POST,PUT,DELETE,OPTIONS',
            'Content-Type' => 'application/vnd.collection+json',
        ]
    );
});

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
        'host'      => getenv('DB_HOST'),
        'dbname'    => getenv('DB_DATABASE'),
        'user'      => getenv('DB_USER'),
        'password'  => getenv('DB_PASSWORD'),
    ),
));

// error handler
$app->error(function (\Exception $e, $code) use ($app) {
    return $app->json($e->getMessage(), $code);
});

$app['goutte'] = $app->share(function () {
    return new \Goutte\Client();
});

// routes
$app->get('/scraper/council', 'Scraper\Controllers\CouncilController::scrape');
$app->get('/scraper/council/persist', 'Scraper\Controllers\CouncilController::persist');

$app->run();