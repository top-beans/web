<?php

namespace Application\ControllerFactory {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\Controller\IndexController,
        JMS\Serializer\SerializerBuilder;
    
    class IndexControllerFactory implements FactoryInterface {

        public function createService(ServiceLocatorInterface $sli) {
            $serviceLocator = $sli->getServiceLocator();
            
            $navService = $serviceLocator->get('Navigation');
            $authService = $serviceLocator->get('AdminAuthService');
            $serializer = SerializerBuilder::create()->build();
            $wpRepo = $serviceLocator->get('WordPrRepo');
            $config = $serviceLocator->get('Config');
            $worldpayClientKey = $config["WorldpayClientKey"];
            
            return new IndexController($navService, $authService, $serializer, $wpRepo, $worldpayClientKey);
        }
    }
}
