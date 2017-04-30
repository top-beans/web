<?php

namespace Application\Controller {
    
    use Zend\Navigation\AbstractContainer;
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;
    use Application\API\Repositories\Interfaces\IEMailService;
    use Application\API\Repositories\Interfaces\ICheckoutService;
    use Application\API\Canonicals\Response\ResponseUtils;

    class CheckoutApiController extends BaseController {
        
        /**
         * @var ICheckoutService
         */
        private $checkoutService;
        
        /**
         * @var IEMailService
         */
        private $emailSvc;
        
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer, ICheckoutService $checkoutService, IEMailService $emailSvc) {
            parent::__construct($navService, $authService, $serializer);
            $this->checkoutService = $checkoutService;
            $this->emailSvc = $emailSvc;
        }

        public function addanonymousorderAction() {
            try {
                $jsonData = $this->getRequest()->getContent();
                $checkout = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\Checkout", "json");
                
                $orderGroupKey = $this->checkoutService->addAnonymousOrder($checkout->cookie, $checkout->deliveryaddress, $checkout->billingaddress);
                $email = $this->checkoutService->createCheckoutEmail($orderGroupKey);
                $this->emailSvc->sendMail($email);                

                $response = ResponseUtils::response();
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
    }
}