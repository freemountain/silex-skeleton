<?php
namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request as Req;
use Symfony\Component\HttpFoundation\Response as Res;
use Model\AuthorQuery;
use Model\Author;

class AuthorsREST implements ControllerProviderInterface {
    public function connect(Application $app) {
        $factory = $app['controllers_factory'];
        $factory->get('/', 'Controller\AuthorsREST::getAll');
        $factory->post('/', 'Controller\AuthorsREST::add');
        $factory->delete('/{id}', 'Controller\AuthorsREST::delete');

        return $factory;
    }

    public function getAll(Application $app) {
        $authors = AuthorQuery::create()->find();
        $authors = json_decode($authors->toJSON(), true)['Authors'];

        return $app->json($authors);
    }

    public function add(Application $app, Req $request) {
        $author = new Author();
        $author->setFirstName($request->request->get('firstName'));
        $author->setLastName($request->request->get('lastName'));
        $author->save();

        return $author->toJSON();
    }

    public function delete(Application $app, $id) {
        $author = AuthorQuery::create()->findPK($id);
        //var_dump($app);
        if($author === NULL) return new Res("Could not delete ".$id, 404);
        $author->delete();
        return '{"id" : '.$id.' }';
    }
}
