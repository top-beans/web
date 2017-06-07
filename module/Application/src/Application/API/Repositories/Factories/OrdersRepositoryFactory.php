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
            return new OrdersRepository($em, $emailSvc, $config['SupportEmail'], $config['DomainName'], $config['ENV'] === 'development');
        }
    }
}
