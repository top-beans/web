<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\API\Repositories\Implementations\OrdersRepository;
    
    class OrdersRepositoryFactory implements FactoryInterface {
        
        public function createService(ServiceLocatorInterface $serviceLocator) {
            $em = $serviceLocator->get('doctrine.entitymanager.orm_default');
            $emailSvc = $serviceLocator->get('EMailSvc');
            $worldpaySvc = $serviceLocator->get('WorldpaySvc');
            $orderEmailsSvc = $serviceLocator->get('OrderEmailsSvc');
            return new OrdersRepository($em, $emailSvc, $worldpaySvc, $orderEmailsSvc);
        }
    }
}
