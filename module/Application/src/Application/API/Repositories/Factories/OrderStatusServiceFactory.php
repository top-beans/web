<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\API\Repositories\Implementations\OrderStatusService;
    
    class OrderStatusServiceFactory implements FactoryInterface {
        
        public function createService(ServiceLocatorInterface $serviceLocator) {
            $em = $serviceLocator->get('doctrine.entitymanager.orm_default');
            $emailSvc = $serviceLocator->get('EMailSvc');
            $orderEmailsSvc = $serviceLocator->get('OrderEmailsSvc');
            $worldpaySvc = $serviceLocator->get('WorldpaySvc');
            return new OrderStatusService($em, $emailSvc, $orderEmailsSvc, $worldpaySvc);
        }
    }
}
