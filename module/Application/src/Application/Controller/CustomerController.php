<?php

namespace Application\Controller {
    
    use Zend\Navigation\AbstractContainer;
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;
    use Application\API\Canonicals\Constants\Navigation;
    
    class CustomerController extends BaseController {
    
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer) {
            parent::__construct($navService, $authService, $serializer);
        }
        
        public function indexAction() {
            $this->navService->findOneById(Navigation::Customer)->setVisible(true);
            $this->navService->findOneById(Navigation::Customer)->setActive(true);
            
            if ($this->authService->hasIdentity()) {
                return $this->redirect()->toUrl("/Customer/orders");
            } else {
                return [];
            }
        }
        
        public function logoutAction() {
            $this->authService->clearIdentity();
            return $this->redirect()->toUrl("/Customer/index");
        }
        
        public function ordersAction() {
            return [];
        }
    }
}
