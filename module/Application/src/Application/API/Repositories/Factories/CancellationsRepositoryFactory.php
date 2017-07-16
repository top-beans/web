<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\API\Repositories\Implementations\CancellationsRepository;
    
    class CancellationsRepositoryFactory implements FactoryInterface {
        
        public function createService(ServiceLocatorInterface $serviceLocator) {
            $em = $serviceLocator->get('doctrine.entitymanager.orm_default');
            $config = $serviceLocator->get('Config');
            $emailSvc = $serviceLocator->get('EMailSvc');
            $orderEmailsSvc = $serviceLocator->get('OrderEmailsSvc');
            return new CancellationsRepository($em, $emailSvc, $orderEmailsSvc, $config['ConfirmWindow'], $config['CancelWindow']);
        }
    }
}
