<?php
namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request as Req;
use Symfony\Component\HttpFoundation\Response as Res;
use Model\BookQuery;
use Model\Book;

class BooksREST implements ControllerProviderInterface {
    public function connect(Application $app) {
        $factory = $app['controllers_factory'];
        $factory->get('/', 'Controller\BooksREST::getAll');
        $factory->post('/', 'Controller\BooksREST::add');
        $factory->delete('/{id}', 'Controller\BooksREST::delete');

        return $factory;
    }

    public function getAll(Application $app) {
        $books = BookQuery::create()->find();
        $books = json_decode($books->toJSON(), true)['Books'];

        return $app->json($books);
    }

    public function add(Application $app, Req $request) {
        $book = new Book();
        $book->setTitle($request->request->get('title'));
        $book->setISBN($request->request->get('isbn'));
        $book->setPublisherId($request->request->get('publisherId'));
        $book->setAuthorId($request->request->get('authorId'));

        $book->save();

        return $book->toJSON();
    }

    public function delete(Application $app, $id) {
        $book = BookQuery::create()->findPK($id);

        if($book === NULL) return new Res("Could not delete ".$id, 404);
        $book->delete();
        return '{"id" : '.$id.' }';
    }
}
