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

$app->mount('/authors', new Controller\Authors());

$app->get('/add/author/{firstName}/{lastName}', function ($firstName, $lastName) use ($app) {
  $author = new Author();
  $author->setFirstName($app->escape($firstName));
  $author->setLastName($app->escape($lastName));
  $author->save();
  return 'saved';
});

$app->run();
