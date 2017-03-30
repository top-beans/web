<?php

namespace Application\ControllerFactory {
    
    use JMS\Serializer\SerializerBuilder;
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\Controller\ClientsApiController;
    
    class ClientsApiControllerFactory implements FactoryInterface {

        public function createService(ServiceLocatorInterface $sli) {
            $serviceLocator = $sli->getServiceLocator();
            $navRepo = $serviceLocator->get('Navigation');
            $authService = $serviceLocator->get('AdminAuthService');
            $serializer = SerializerBuilder::create()->build();
            $clientsRepository = $serviceLocator->get('ClientsRepo');
            
            return new ClientsApiController($navRepo, $authService, $serializer, $clientsRepository);
        }
    }
}