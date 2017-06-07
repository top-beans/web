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
            $usersRepo = $serviceLocator->get('UsersRepo');
            $sessionManager = $serviceLocator->get('SessionManager');
            $config = $serviceLocator->get('Config');
            $maxLoginTries = $config["MaxLoginTries"];
            $worldPayServiceKey = $config["WorldPayServiceKey"];
            $settlementCurrency = $config["SettlementCurrency"];
            
            return new OrdersApiController($navRepo, $authService, $serializer, $ordersRepo, $usersRepo, $sessionManager, $maxLoginTries, $worldPayServiceKey, $settlementCurrency);
        }
    }
}