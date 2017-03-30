<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\API\Repositories\Implementations\ClientsRepository;
    
    class ClientsRepositoryFactory implements FactoryInterface {
        
        public function createService(ServiceLocatorInterface $serviceLocator) {
            return new ClientsRepository($serviceLocator->get('doctrine.entitymanager.orm_default'));
        }
    }
}
