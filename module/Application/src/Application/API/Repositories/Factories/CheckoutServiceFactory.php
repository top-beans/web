<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\API\Repositories\Implementations\CoffeeRepository;
    
    class CheckoutServiceFactory implements FactoryInterface {
        
        public function createService(ServiceLocatorInterface $serviceLocator) {
            $cartRepo = $serviceLocator->get('CartRepo');
            return new CoffeeRepository($serviceLocator->get('doctrine.entitymanager.orm_default'), $cartRepo);
        }
    }
}
