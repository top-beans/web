<?php

namespace Application\Controller {
    
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;
    use Zend\Navigation\AbstractContainer;
    
    class CancellationsController extends BaseController {
        
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer) {
            parent::__construct($navService, $authService, $serializer);
        }        
        
        public function indexAction() {
            return [];
        }
    }
}
