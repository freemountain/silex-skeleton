<?php
namespace App\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request as Req;
use Symfony\Component\HttpFoundation\Response as Res;
use Symfony\Component\HttpFoundation\JsonResponse;

use Model\PublisherQuery;
use Model\Publisher;
use App\Response\ApiMessage;


class PublishersREST implements ControllerProviderInterface {
    public function connect(Application $app) {
        $factory = $app['controllers_factory'];
        $factory->get('/', 'App\Controller\PublishersREST::getAll');
        $factory->post('/', 'App\Controller\PublishersREST::add');
        $factory->delete('/{id}', 'App\Controller\PublishersREST::delete');
        $factory->get('/{id}', 'App\Controller\PublishersREST::get');

        return $factory;
    }

    public function getAll(Application $app) {
        $publishers = PublisherQuery::create()->find();
        $publishers = json_decode($publishers->toJSON(), true)['Publishers'];

        return $app->json($publishers);
    }

    public function get(Application $app, $id) {
        $publisher = PublisherQuery::create()->findPK($id);
        if($publisher === NULL) return ApiMessage::fromType('entity_not_found');
        $response = $publisher->toArray();
        $response['Books'] = array_map(function($book) {
            return array('Title' => $book['Title'], 'Id' => $book['Id']);
        },$publisher->getBooks()->toArray());
        return new JsonResponse($response);
    }

    public function add(Application $app, Req $request) {
        $publisher = new Publisher();
        $publisher->setName($request->request->get('name'));
        $publisher->save();

        return $publisher->toJSON();
    }

    public function delete(Application $app, $id) {
        $publisher = PublisherQuery::create()->findPK($id);
        if($publisher === NULL)
            return ApiMessage::fromType('entity_not_found');

        if($publisher->countBooks() > 0)
            return ApiMessage::fromType('entity_referenced');

        $publisher->delete();
        return new JsonResponse(array('message' => 'success'));
    }
}
