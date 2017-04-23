<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\API\Repositories\Implementations\CountriesRepository;
    
    class CountriesRepositoryFactory implements FactoryInterface {
        
        public function createService(ServiceLocatorInterface $serviceLocator) {
            return new CountriesRepository($serviceLocator->get('doctrine.entitymanager.orm_default'));
        }
    }
}
