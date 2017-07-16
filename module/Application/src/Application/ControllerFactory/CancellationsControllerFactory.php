<?php

namespace Application\ControllerFactory {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\Controller\CancellationsController,
        JMS\Serializer\SerializerBuilder;
    
    class CancellationsControllerFactory implements FactoryInterface {

        public function createService(ServiceLocatorInterface $sli) {
            $serviceLocator = $sli->getServiceLocator();
            
            $navService = $serviceLocator->get('Navigation');
            $authService = $serviceLocator->get('AdminAuthService');
            $serializer = SerializerBuilder::create()->build();
            
            return new CancellationsController($navService, $authService, $serializer);
        }
    }
}
