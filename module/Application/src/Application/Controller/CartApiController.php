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
                $cookieKey = $this->getRequest()->getContent();
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
                $cookieKey = $this->getRequest()->getContent();
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
                $cookieKey = $this->getRequest()->getContent();
                $items = $this->cartRepo->getCart($cookieKey);
                $response = ResponseUtils::responseList($items);
                return $this->jsonResponse($response);
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }

        public function getcartbreakdownAction() {
            try {
                $cookieKey = $this->getRequest()->getContent();
                $item = $this->cartRepo->getCartBreakdown($cookieKey);
                $response = ResponseUtils::responseItem($item);
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
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\CartItem", "json");
                
                $cookieKey = $data->cookiekey;
                $coffeeKey = $data->coffeekey;
                
                $this->cartRepo->deleteFromCart($cookieKey, $coffeeKey);
                $response = ResponseUtils::response();
                return $this->jsonResponse($response);
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function incrementAction() {
            try {
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\CartItem", "json");
                
                $cookieKey = $data->cookiekey;
                $coffeeKey = $data->coffeekey;
                
                $updatedItem = $this->cartRepo->incrementCartItem($cookieKey, $coffeeKey);
                $response = ResponseUtils::responseItem($updatedItem);
                return $this->jsonResponse($response);
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function decrementAction() {
            try {
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\CartItem", "json");
                
                $cookieKey = $data->cookiekey;
                $coffeeKey = $data->coffeekey;
                
                $updatedItem = $this->cartRepo->decrementCartItem($cookieKey, $coffeeKey);
                $response = ResponseUtils::responseItem($updatedItem);
                return $this->jsonResponse($response);
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
   }
}