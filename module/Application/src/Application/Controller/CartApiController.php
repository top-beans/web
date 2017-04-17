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
                $response = ResponseUtils::responseItem($cartSize);
                return $this->jsonResponse($response);

            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }

        public function getcarttotalAction() {
            try {
                $cookieKey = $this->p1;
                $total = $this->cartRepo->getCartTotal($cookieKey);
                $response = ResponseUtils::responseItem($total);
                return $this->jsonResponse($response);

            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }

        public function getcartAction() {
            try {
                $cookieKey = $this->p1;
                $items = $this->cartRepo->getCart($cookieKey);
                $response = ResponseUtils::responseList($items);
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
                $response = $this->cartRepo->validateMergeCart($cart);
                
                if (!$response->success) {
                    return $this->jsonResponse($response);
                } else {
                    $this->cartRepo->mergeCart($cart);
                    $response = ResponseUtils::responseItem($cart, [], $response->warnings);
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
                
                $response = ResponseUtils::response();
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
                $response = ResponseUtils::response();
                return $this->jsonResponse($response);
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
   }
}