<?php

namespace Application\ControllerFactory {
    
    use JMS\Serializer\SerializerBuilder;
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\Controller\WorldpayApiController;
    
    class WorldpayApiControllerFactory implements FactoryInterface {

        public function createService(ServiceLocatorInterface $sli) {
            $serviceLocator = $sli->getServiceLocator();
            $serializer = SerializerBuilder::create()->build();
            $ordersRepo = $serviceLocator->get('OrdersRepo');
            $config = $serviceLocator->get('Config');
            $env = $config["ENV"];
            return new WorldpayApiController($serializer, $ordersRepo, $env);
        }
    }
}