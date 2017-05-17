<?php

namespace Application\ControllerFactory {
    
    use JMS\Serializer\SerializerBuilder;
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\Controller\OrdersApiController;
    
    class OrdersApiControllerFactory implements FactoryInterface {

        public function createService(ServiceLocatorInterface $sli) {
            $serviceLocator = $sli->getServiceLocator();
            $navRepo = $serviceLocator->get('Navigation');
            $authService = $serviceLocator->get('AdminAuthService');
            $serializer = SerializerBuilder::create()->build();
            $ordersRepo = $serviceLocator->get('OrdersRepo');
            $emailSvc = $serviceLocator->get('EMailSvc');
            
            return new OrdersApiController($navRepo, $authService, $serializer, $ordersRepo, $emailSvc);
        }
    }
}