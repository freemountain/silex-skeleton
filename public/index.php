<?php

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

$app->mount('/publishers', new App\Controller\Publishers());
$app->mount('/api/publishers', new App\Controller\PublishersREST());

$app->mount('/authors', new App\Controller\Authors());
$app->mount('/api/authors', new App\Controller\AuthorsREST());

$app->mount('/books', new App\Controller\Books());
$app->mount('/api/books', new App\Controller\BooksREST());

$app->run();
