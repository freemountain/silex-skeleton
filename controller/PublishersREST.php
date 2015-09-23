<?php
  namespace Controller;

  use Silex\Application;
  use Silex\ControllerProviderInterface;
  use Symfony\Component\HttpFoundation\Request as Req;
  use Symfony\Component\HttpFoundation\Response as Res;
  use Model\PublisherQuery;
  use Model\Publisher;

  class PublishersREST implements ControllerProviderInterface
  {
      public function connect(Application $app) {
          $factory = $app['controllers_factory'];
          $factory->get('/', 'Controller\PublishersREST::getAll');
          $factory->post('/', 'Controller\PublishersREST::add');
          $factory->delete('/{id}', 'Controller\PublishersREST::delete');

          return $factory;
      }

      public function getAll(Application $app) {
        $publishers = PublisherQuery::create()->find();
        $publishers = json_decode($publishers->toJSON(), true)['Publishers'];

        return $app->json($publishers);
      }

      public function add(Application $app, Req $request) {
        $publisher = new Publisher();
        $publisher->setName($request->request->get('name'));
        $publisher->save();

        return $publisher->toJSON();
      }

      public function delete(Application $app, $id) {
        $author = PublisherQuery::create()->findPK($id);
        if($author === NULL) return new Res("Could not delete ".$id, 404);
        $author->delete();
        return '{"id" : '.$id.' }';
      }
    }
