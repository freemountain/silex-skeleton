<?php
namespace App\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request as Req;
use Model\PublisherQuery;
use Model\AuthorQuery;
use Model\BookQuery;
use Model\Book;

class Books implements ControllerProviderInterface {
  public function connect(Application $app) {
    $factory = $app['controllers_factory'];
    $factory->get('/', 'App\Controller\Books::getAll');

    return $factory;
  }

  public function getAll(Application $app) {
    $books = BookQuery::create()
        ->joinWith('Book.Publisher')
        ->joinWith('Book.Author')
        ->find()
        ->toArray();
    $publisher = PublisherQuery::create()->find()->toArray();
    $authors = AuthorQuery::create()->find()->toArray();

    return $app['twig']->render('books.twig', array(
        'books' => $books,
        'publisher' => $publisher,
        'authors' => $authors
    ));
  }
}
