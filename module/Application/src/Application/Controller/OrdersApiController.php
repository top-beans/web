<?php

namespace Application\Controller {
    
    use Zend\Navigation\AbstractContainer;
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;
    use Application\API\Canonicals\Entity\Address;
    use Application\API\Canonicals\Response\ResponseUtils;
    use Application\API\Repositories\Interfaces\IOrdersRepository;
    use Application\API\Repositories\Interfaces\IEMailService;
    use Application\API\Repositories\Interfaces\IUsersRepository;
    
    class OrdersApiController extends BaseController {
        
        /**
         * @var IOrdersRepository
         */
        private $ordersRepo;
        
        /**
         * @var IEMailService
         */
        private $emailSvc;
        
        /**
         * @var IUsersRepository
         */
        private $usersRepository;
        
        /**
         * @var number
         */
        private $maxLoginTries;
        
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer, IOrdersRepository $ordersRepo, IEMailService $emailSvc, IUsersRepository $usersRepository, $maxLoginTries) {
            parent::__construct($navService, $authService, $serializer);
            $this->ordersRepo = $ordersRepo;
            $this->emailSvc = $emailSvc;
            $this->usersRepository = $usersRepository;
            $this->maxLoginTries = $maxLoginTries;
        }

        public function getcustomeraddressesAction() {
            try {
                $cookieKey = $this->p1;
                $groupKey = $this->ordersRepo->getGroupByCookie($cookieKey);
                
                if ($groupKey == null) {
                    $deliveryAddress = null;
                    $billingAddress = null;
                } else {
                    $customer = $this->ordersRepo->getCustomerByGroup($groupKey);
                    $deliveryAddress = $this->ordersRepo->getAddress($customer->getDeliveryaddresskey());
                    $billingAddress = $this->ordersRepo->getAddress($customer->getBillingaddresskey());
                }
                
                $response = ResponseUtils::responseItem([
                    'deliveryaddress' => $deliveryAddress, 
                    'billingaddress' => $billingAddress,
                    'billingDifferent' => (
                        $deliveryAddress->getFullname()   != $billingAddress->getFullname() ||
                        $deliveryAddress->getAddress1()   != $billingAddress->getAddress1() ||
                        $deliveryAddress->getAddress2()   != $billingAddress->getAddress2() ||
                        $deliveryAddress->getPostcode()   != $billingAddress->getPostcode() ||
                        $deliveryAddress->getCity()       != $billingAddress->getCity() ||
                        $deliveryAddress->getEmail()      != $billingAddress->getEmail() ||
                        $deliveryAddress->getPhone()      != $billingAddress->getPhone() ||
                        $deliveryAddress->getCountrykey() != $billingAddress->getCountrykey()
                    )
                ]);
                
                return $this->jsonResponse($response);

            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function addanonymousorderAction() {
            try {
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\Checkout", "json");
                
                $requiresPayment = $this->ordersRepo->addAnonymousOrder($data->cookie, $data->deliveryaddress, $data->billingaddress);
                
                if (!$requiresPayment) {
                    $emailRequest = $this->ordersRepo->createReceivedEmail($data->cookie);
                    $this->emailSvc->sendMail($emailRequest);
                }
                
                $response = ResponseUtils::responseItem($requiresPayment);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
    }
}