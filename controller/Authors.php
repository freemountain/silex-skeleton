<?php
  namespace Controller;

  use Silex\Application;
  use Silex\ControllerProviderInterface;
  use Symfony\Component\HttpFoundation\Request as Req;
  use Model\AuthorQuery;
  use Model\Author;

  class Authors implements ControllerProviderInterface
  {
      public function connect(Application $app) {
          $factory = $app['controllers_factory'];
          $factory->get('/', 'Controller\Authors::getAll');
          $factory->post('/', 'Controller\Authors::add');
          $factory->delete('/{id}', 'Controller\Authors::delete');

          return $factory;
      }

      public function getAll(Application $app) {
        $authors = AuthorQuery::create()->find()->toArray();
        return $app['twig']->render('author.twig', array(
            'authors' => $authors,
        ));
      }

      public function add(Application $app, Req $request) {
        $author = new Author();
        $author->setFirstName($request->request->get('firstName'));
        $author->setLastName($request->request->get('lastName'));
        $author->save();

        return $author->toJSON();
      }

      public function delete(Application $app, $id) {
        $author = AuthorQuery::create()->findPK($id)->delete();
        return 'deleted '.$id;
      }
    }
