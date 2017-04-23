<?php

namespace Application\ControllerFactory {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\Controller\CustomerController,
        JMS\Serializer\SerializerBuilder;
    
    class CustomerControllerFactory implements FactoryInterface {
    
        public function createService(ServiceLocatorInterface $sli) {
            $serviceLocator = $sli->getServiceLocator();
            $navRepo = $serviceLocator->get('Navigation');
            $adminAuthService = $serviceLocator->get('AdminAuthService');
            $serializer = SerializerBuilder::create()->build();
            
            return new CustomerController($navRepo, $adminAuthService, $serializer);
        }        
    }
}
