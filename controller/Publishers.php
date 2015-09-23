<?php
  namespace Controller;

  use Silex\Application;
  use Silex\ControllerProviderInterface;
  use Symfony\Component\HttpFoundation\Request as Req;
  use Model\PublisherQuery;
  use Model\Publisher;

  class Publishers implements ControllerProviderInterface
  {
      public function connect(Application $app) {
          $factory = $app['controllers_factory'];
          $factory->get('/', 'Controller\Publishers::getAll');

          return $factory;
      }

      public function getAll(Application $app) {
        $publishers = PublisherQuery::create()->find()->toArray();
        return $app['twig']->render('publishers.twig', array(
            'publishers' => $publishers,
        ));
      }
    }
