<?php

namespace Application\Controller {
    
    use Zend\Navigation\AbstractContainer;
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;
    use Application\API\Repositories\Interfaces\ICartRepository;
    use Application\API\Canonicals\Response\ResponseUtils;

    class CartApiController extends BaseController {
        
        /**
         * @var ICartRepository
         */
        private $cartRepo;
        
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer, ICartRepository $cartRepo) {
            parent::__construct($navService, $authService, $serializer);
            $this->cartRepo = $cartRepo;
        }

        public function getcartsizeAction() {
            try {
                $cookieKey = $this->p1;
                $cartSize = $this->cartRepo->getCartSize($cookieKey);
                $response = ResponseUtils::createSingleFetchResponse($cartSize);
                return $this->jsonResponse($response);

            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function patchAction() {
            try {
                $jsonData = $this->getRequest()->getContent();
                $cart = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Entity\Shoppingcart", "json");
                
                $cart->setCreateddate(new \DateTime());
                $mergeErrors = $this->cartRepo->validateMergeCart($cart);
                
                if (count($mergeErrors) > 0) {
                    $response = ResponseUtils::createResponse($mergeErrors);
                    return $this->jsonResponse($response);
                } else {
                    $this->cartRepo->mergeCart($cart);
                    $response = ResponseUtils::createWriteResponse($cart);
                    return $this->jsonResponse($response);
                }
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function updateAction() {
            try {
                $jsonData = $this->getRequest()->getContent();
                $cart = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Entity\Shoppingcart", "json");
                
                $cart->setUpdateddate(new \DateTime());
                $this->cartRepo->updateCart($cart);
                
                $response = ResponseUtils::createResponse();
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function deleteAction() {
            try {
                $cookieKey = $this->p1;
                $coffeeKey = $this->p2;
                $this->cartRepo->deleteFromCart($cookieKey, $coffeeKey);
                $response = ResponseUtils::createResponse();
                return $this->jsonResponse($response);
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
   }
}