<?php

namespace Application\Controller {
    
    use Zend\Navigation\AbstractContainer;
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;
    use Application\API\Repositories\Interfaces\ICoffeeRepository;
    use Application\API\Canonicals\Response\ResponseUtils;

    class CoffeeApiController extends BaseController {
        
        /**
         * @var ICoffeeRepository
         */
        private $coffeeRepo;
        
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer, ICoffeeRepository $coffeeRepo) {
            parent::__construct($navService, $authService, $serializer);
            $this->coffeeRepo = $coffeeRepo;
        }
        
        public function getallactiveAction() {
            try {
                $items = $this->coffeeRepo->findAllActive();
                $response = ResponseUtils::responseList($items);
                return $this->jsonResponse($response);
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function addAction() {
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $jsonData = $this->getRequest()->getContent();
                $coffee = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Entity\Coffee", "json");
                
                $this->coffeeRepo->addCoffee($coffee);
                $response = ResponseUtils::responseItem($coffee);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function updateAction() {
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $jsonData = $this->getRequest()->getContent();
                $coffee = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Entity\Coffee", "json");
                
                $this->coffeeRepo->updateCoffee($coffee);
                $response = ResponseUtils::responseItem($coffee);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function deactivateAction() {
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $coffeeKey = $this->p1;
                $this->coffeeRepo->deactivateCoffee($coffeeKey);
                $response = ResponseUtils::response();
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
    }
}