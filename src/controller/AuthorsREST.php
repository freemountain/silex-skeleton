<?php
namespace App\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request as Req;
use Symfony\Component\HttpFoundation\Response as Res;
use Symfony\Component\HttpFoundation\JsonResponse;

use Model\AuthorQuery;
use Model\Author;
use App\Response\ApiMessage;

class AuthorsREST implements ControllerProviderInterface {
    public function connect(Application $app) {
        $factory = $app['controllers_factory'];
        $factory->get('/', 'App\Controller\AuthorsREST::getAll');
        $factory->post('/', 'App\Controller\AuthorsREST::add');
        $factory->delete('/{id}', 'App\Controller\AuthorsREST::delete');
        $factory->get('/{id}', 'App\Controller\AuthorsREST::get');
        return $factory;
    }

    public function getAll(Application $app) {
        $authors = AuthorQuery::create()->find();
        $authors = json_decode($authors->toJSON(), true)['Authors'];

        return $app->json($authors);
    }

    public function get(Application $app, $id) {
        $author = AuthorQuery::create()->findPK($id);
        if($author === NULL) return ApiMessage::fromType('entity_not_found');
        $response = $author->toArray();
        $response['Books'] = array_map(function($book) {
            return array('Title' => $book['Title'], 'Id' => $book['Id']);
        },$author->getBooks()->toArray());
        return new JsonResponse($response);
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
        if($author === NULL)
            return ApiMessage::fromType('entity_not_found');

        if($author->countBooks() > 0)
            return ApiMessage::fromType('entity_referenced');

        $author->delete();
        return new JsonResponse(array('message' => 'success'));
    }
}
