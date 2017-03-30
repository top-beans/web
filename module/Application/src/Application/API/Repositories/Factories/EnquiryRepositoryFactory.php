<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\API\Repositories\Implementations\EnquiryRepository;
    
    class EnquiryRepositoryFactory implements FactoryInterface {
        
        public function createService(ServiceLocatorInterface $serviceLocator) {
            $config = $serviceLocator->get('Config');
            return new EnquiryRepository($serviceLocator->get('doctrine.entitymanager.orm_default'), $config['SupportEmail']);
        }
    }
}
