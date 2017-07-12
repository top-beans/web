<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\API\Repositories\Implementations\OrderService;
    
    class OrderServiceFactory implements FactoryInterface {
        
        public function createService(ServiceLocatorInterface $serviceLocator) {
            $em = $serviceLocator->get('doctrine.entitymanager.orm_default');
            $emailSvc = $serviceLocator->get('EMailSvc');
            $worldpaySvc = $serviceLocator->get('WorldpaySvc');
            $orderStatusSvc = $serviceLocator->get('OrderStatusSvc');
            return new OrderService($em, $emailSvc, $worldpaySvc, $orderStatusSvc);
        }
    }
}
