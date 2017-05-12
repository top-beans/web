<?php

namespace Application\Controller {
    
    use Zend\Navigation\AbstractContainer;
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;
    use Application\API\Repositories\Interfaces\IOrdersRepository;
    use Application\API\Repositories\Interfaces\ICartRepository;
    use Application\API\Repositories\Interfaces\ICoffeeRepository;
    use Application\API\Canonicals\Response\ResponseUtils;
    use Application\API\Canonicals\Entity\RequestTypes;
    
    class OrdersApiController extends BaseController {
        
        /**
         * @var IOrdersRepository
         */
        private $ordersRepo;
        
        /**
         * @var ICartRepository
         */
        private $cartRepo;
        
        /**
         * @var ICoffeeRepository
         */
        private $coffeeRepo;
        
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer, IOrdersRepository $ordersRepo, ICartRepository $cartRepo, ICoffeeRepository $coffeeRepo) {
            parent::__construct($navService, $authService, $serializer);
            $this->ordersRepo = $ordersRepo;
            $this->cartRepo = $cartRepo;
            $this->coffeeRepo = $coffeeRepo;
        }

        public function addAction() {
            try {
                $jsonData = $this->getRequest()->getContent();
                $checkout = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\Checkout", "json");
                
                $cart = $this->cartRepo->getCart($checkout->cookie);
                $coffee = $this->coffeeRepo->find($cart->getCoffeekey());
                
                if ($cart->getRequesttypekey() == RequestTypes::Purchase) {
                    
                } else if ($cart->getQuantity() < $coffee->getMaxfreesamplequantity()) {
                    
                } else {
                    
                }
                
                return $this->jsonResponse($response);

            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }

        public function patchAction() {
            try {
                $countries = $this->ordersRepo->getCountries();
                $response = ResponseUtils::responseList($countries);
                return $this->jsonResponse($response);

            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
    }
}