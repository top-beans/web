<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\API\Repositories\Implementations\OrderEmailsService;
    
    class OrderEmailsServiceFactory implements FactoryInterface {
        
        public function createService(ServiceLocatorInterface $serviceLocator) {
            $config = $serviceLocator->get('Config');
            $domainName = $config['DomainName'];
            $isDevelopment = $config['ENV'] === 'development';
            $supportEmail = $config['SupportEmail'];
            return new OrderEmailsService($domainName, $isDevelopment, $supportEmail);
        }
    }
}
