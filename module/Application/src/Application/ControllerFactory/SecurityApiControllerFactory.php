<?php

namespace Application\ControllerFactory {
    
    use JMS\Serializer\SerializerBuilder;
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\Controller\SecurityApiController;
    
    class SecurityApiControllerFactory implements FactoryInterface {

        public function createService(ServiceLocatorInterface $sli) {
            $serviceLocator = $sli->getServiceLocator();

            $navRepo = $serviceLocator->get('Navigation');
            $adminAuthService = $serviceLocator->get('AdminAuthService');
            $serializer = SerializerBuilder::create()->build();
            $usersRepo = $serviceLocator->get('UsersRepo');
            $config = $serviceLocator->get('Config');
            $maxLoginTries = $config["MaxLoginTries"];

            return new SecurityApiController($navRepo, $adminAuthService, $serializer, $usersRepo, $maxLoginTries);
        }
    }
}