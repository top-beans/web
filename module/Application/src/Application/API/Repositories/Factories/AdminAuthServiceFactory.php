<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Zend\Authentication\AuthenticationService,
        Zend\Db\Adapter\Adapter,
        Zend\Authentication\Adapter\DbTable,
        Zend\Authentication\Storage\Session;
    
    class AdminAuthServiceFactory implements FactoryInterface {
        
        public function createService(ServiceLocatorInterface $serviceLocator) {
            $config      = $serviceLocator->get('Config');
            $dbAdapter   = new Adapter($config['doctrine']['connection']['orm_default']['params']);
            
            $authAdapter = new DbTable($dbAdapter, 'Users','username','password', '?');
            $authStorage = new Session($config['SessionGuid']);
            
            return new AuthenticationService($authStorage, $authAdapter);
        }
    }
}
