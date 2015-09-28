<?php
namespace App\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request as Req;
use Symfony\Component\HttpFoundation\Response;

use Model\AuthorQuery;
use Model\Author;
use App\Response\ApiMessage;

class Authors implements ControllerProviderInterface {
  public function connect(Application $app) {
    $factory = $app['controllers_factory'];
    $factory->get('/', 'App\Controller\Authors::getAll');
    $factory->get('/{id}', 'App\Controller\Authors::get');

    return $factory;
  }

  public function getAll(Application $app) {
    $authors = AuthorQuery::create()->find()->toArray();
    return $app['twig']->render('authors.twig', array(
        'authors' => $authors,
    ));
  }

  public function get(Application $app, $id) {
      $response = new Response();
      $author = AuthorQuery::create()->findPK($id);

      if($author == NULL) return ApiMessage::fromType('entity_not_found');

      $var = array(
          'author' => $author,
          'foo' => 'bar'^
      );
      return $app['twig']->render('author.twig', $var);
  }
}
