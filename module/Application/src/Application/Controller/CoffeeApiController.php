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
        
        public function getallAction() {
            try {
                $items = $this->coffeeRepo->findAll();
                $response = ResponseUtils::responseList($items);
                return $this->jsonResponse($response);
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function patchAction() {
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $jsonData = $this->getRequest()->getContent();
                $coffee = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Entity\Coffee", "json");
                
                $this->coffeeRepo->addOrUpdateCoffee($coffee);
                $response = ResponseUtils::responseItem($coffee);
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
        
        public function toggleactiveAction() {
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $coffeeKey = $jsonData = $this->getRequest()->getContent();
                $updatedItem = $this->coffeeRepo->toggleActive($coffeeKey);
                $response = ResponseUtils::responseItem($updatedItem);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function incrementAction() {
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $coffeeKey = $jsonData = $this->getRequest()->getContent();
                $updatedItem = $this->coffeeRepo->incrementCoffee($coffeeKey);
                $response = ResponseUtils::responseItem($updatedItem);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function decrementAction() {
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $coffeeKey = $jsonData = $this->getRequest()->getContent();
                $updatedItem = $this->coffeeRepo->decrementCoffee($coffeeKey);
                $response = ResponseUtils::responseItem($updatedItem);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
    }
}