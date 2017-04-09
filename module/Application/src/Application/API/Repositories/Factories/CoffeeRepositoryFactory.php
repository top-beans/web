<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\API\Repositories\Implementations\CoffeeRepository;
    
    class CoffeeRepositoryFactory implements FactoryInterface {
        
        public function createService(ServiceLocatorInterface $serviceLocator) {
            return new CoffeeRepository($serviceLocator->get('doctrine.entitymanager.orm_default'));
        }
    }
}
