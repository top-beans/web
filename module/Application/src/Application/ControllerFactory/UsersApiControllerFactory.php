<?php

namespace Application\ControllerFactory {
    
    use JMS\Serializer\SerializerBuilder;
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\Controller\UsersApiController;
    
    class UsersApiControllerFactory implements FactoryInterface {

        public function createService(ServiceLocatorInterface $sli) {
            $serviceLocator = $sli->getServiceLocator();
            $navRepo = $serviceLocator->get('Navigation');
            $authService = $serviceLocator->get('AdminAuthService');
            $serializer = SerializerBuilder::create()->build();
            $usersRepository = $serviceLocator->get('UsersRepo');
            
            return new UsersApiController($navRepo, $authService, $serializer, $usersRepository);
        }
    }
}