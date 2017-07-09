<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\API\Repositories\Implementations\OrdersRepository;
    
    class OrdersRepositoryFactory implements FactoryInterface {
        
        public function createService(ServiceLocatorInterface $serviceLocator) {
            $config = $serviceLocator->get('Config');
            $em = $serviceLocator->get('doctrine.entitymanager.orm_default');
            $emailSvc = $serviceLocator->get('EMailSvc');
            $worldpaySvc = $serviceLocator->get('WorldpaySvc');
            return new OrdersRepository($em, $emailSvc, $worldpaySvc, $config['SupportEmail'], $config['DomainName'], $config['ENV'] === 'development');
        }
    }
}
