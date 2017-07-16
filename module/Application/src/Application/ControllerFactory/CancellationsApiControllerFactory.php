<?php

namespace Application\ControllerFactory {
    
    use JMS\Serializer\SerializerBuilder;
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\Controller\CancellationsApiController;
    
    class CancellationsApiControllerFactory implements FactoryInterface {

        public function createService(ServiceLocatorInterface $sli) {
            $serviceLocator = $sli->getServiceLocator();
            $navRepo = $serviceLocator->get('Navigation');
            $authService = $serviceLocator->get('AdminAuthService');
            $serializer = SerializerBuilder::create()->build();
            $cancelRepo = $serviceLocator->get('CancelRepo');
            $ordersRepo = $serviceLocator->get('OrdersRepo');
            
            return new CancellationsApiController($navRepo, $authService, $serializer, $cancelRepo, $ordersRepo);
        }
    }
}