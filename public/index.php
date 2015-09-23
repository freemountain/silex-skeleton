<?php
// web/index.php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../generated-conf/config.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

$app = new Silex\Application();
$app['debug'] = true;
$app['asset_path'] = '/assets';
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});
$app->mount('/publishers', new Controller\Publishers());
$app->mount('/api/publishers', new Controller\PublishersREST());

$app->mount('/authors', new Controller\Authors());
$app->mount('/api/authors', new Controller\AuthorsREST());


$app->run();
