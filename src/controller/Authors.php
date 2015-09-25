<?php
namespace App\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request as Req;
use Model\AuthorQuery;
use Model\Author;

class Authors implements ControllerProviderInterface {
  public function connect(Application $app) {
    $factory = $app['controllers_factory'];
    $factory->get('/', 'App\Controller\Authors::getAll');

    return $factory;
  }

  public function getAll(Application $app) {
    $authors = AuthorQuery::create()->find()->toArray();
    return $app['twig']->render('authors.twig', array(
        'authors' => $authors,
    ));
  }
}
