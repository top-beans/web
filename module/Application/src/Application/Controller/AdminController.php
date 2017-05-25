<?php

namespace Application\Controller {
    
    use Zend\Navigation\AbstractContainer;
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;
    use Application\API\Canonicals\Constants\Navigation;
    
    class AdminController extends BaseController {
    
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer) {
            parent::__construct($navService, $authService, $serializer);
        }
        
        public function indexAction() {
            $this->navService->findOneById(Navigation::Admin)->setVisible(true);
            $this->navService->findOneById(Navigation::Admin)->setActive(true);
            
            if ($this->authService->hasIdentity()) {
                return $this->redirect()->toUrl("/Admin/enquiries");
            } else {
                return [];
            }
        }
        
        public function logoutAction() {
            $this->authService->clearIdentity();
            return $this->redirect()->toUrl("/Admin/index");
        }
        
        public function useradminAction() {
            return [];
        }
        
        public function enquiriesAction() {
            return [];
        }
        
        public function clientsAction() {
            return [];
        }
        
        public function coffeesAction() {
            return [];
        }
        
        public function ordersAction() {
            return [];
        }
    }
}
