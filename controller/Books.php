<?php
namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request as Req;
use Model\BookQuery;
use Model\Book;

class Books implements ControllerProviderInterface {
  public function connect(Application $app) {
    $factory = $app['controllers_factory'];
    $factory->get('/', 'Controller\Books::getAll');

    return $factory;
  }

  public function getAll(Application $app) {
    $books = BookQuery::create()->find()->toArray();
    return $app['twig']->render('books.twig', array(
        'books' => $books,
    ));
  }
}
