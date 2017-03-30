<?php

namespace Application\ControllerFactory {
    
    use JMS\Serializer\SerializerBuilder;
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\Controller\EnquiryApiController;
    
    class EnquiryApiControllerFactory implements FactoryInterface {

        public function createService(ServiceLocatorInterface $sli) {
            $serviceLocator = $sli->getServiceLocator();
            $navRepo = $serviceLocator->get('Navigation');
            $authService = $serviceLocator->get('AdminAuthService');
            $serializer = SerializerBuilder::create()->build();
            $enquiryRepo = $serviceLocator->get('EnquiryRepo');
            $emailSvc = $serviceLocator->get('EMailSvc');
            
            return new EnquiryApiController($navRepo, $authService, $serializer, $enquiryRepo, $emailSvc);
        }
    }
}