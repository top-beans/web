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
    use Application\API\Canonicals\Constants\FlashMessages;
    use Worldpay\Worldpay;
    use Zend\Http\PhpEnvironment\RemoteAddress;
    use Zend\Session\ManagerInterface;
    
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
        
        /**
         * @var ManagerInterface
         */
        private $sessionManager;
        
        /**
         * @var string
         */
        private $worldPayServiceKey;
        
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer, IOrdersRepository $ordersRepo, IEMailService $emailSvc, IUsersRepository $usersRepository, ManagerInterface $sessionManager, $maxLoginTries, $worldPayServiceKey, $settlementCurrency) {
            parent::__construct($navService, $authService, $serializer);
            $this->ordersRepo = $ordersRepo;
            $this->emailSvc = $emailSvc;
            $this->usersRepository = $usersRepository;
            $this->sessionManager = $sessionManager;
            $this->maxLoginTries = $maxLoginTries;
            $this->worldPayServiceKey = $worldPayServiceKey;
            $this->settlementCurrency = $settlementCurrency;
        }

        public function getcustomeraddressesAction() {
            try {
                $cookieKey = $this->getRequest()->getContent();
                $groupKey = $this->ordersRepo->getGroupByCookie($cookieKey);
                
                if ($groupKey == null) {
                    $deliveryAddress = null;
                    $billingAddress = null;
                    $billingDifferent = false;
                } else {
                    $customer = $this->ordersRepo->getCustomerByGroup($groupKey);
                    $deliveryAddress = $this->ordersRepo->getAddress($customer->getDeliveryaddresskey());
                    $billingAddress = $this->ordersRepo->getAddress($customer->getBillingaddresskey());
                    $billingDifferent = (
                        $deliveryAddress->getFirstname() != $billingAddress->getFirstname() ||
                        $deliveryAddress->getLastname() != $billingAddress->getLastname() ||
                        $deliveryAddress->getAddress1() != $billingAddress->getAddress1() ||
                        $deliveryAddress->getAddress2() != $billingAddress->getAddress2() ||
                        $deliveryAddress->getAddress3() != $billingAddress->getAddress3() ||
                        $deliveryAddress->getPostcode() != $billingAddress->getPostcode() ||
                        $deliveryAddress->getCity() != $billingAddress->getCity() ||
                        $deliveryAddress->getState() != $billingAddress->getState() ||
                        $deliveryAddress->getEmail() != $billingAddress->getEmail() ||
                        $deliveryAddress->getPhone() != $billingAddress->getPhone() ||
                        $deliveryAddress->getCountrykey() != $billingAddress->getCountrykey()
                    );
                }
                
                $response = ResponseUtils::responseItem([
                    'deliveryaddress' => $deliveryAddress, 
                    'billingaddress' => $billingAddress,
                    'billingDifferent' => $billingDifferent
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
                
                $orderResult = $this->ordersRepo->addAnonymousOrder($data->cookie, $data->deliveryaddress, $data->billingaddress);
                
                if (!$orderResult->requirespayment) {
                    $shoppersCopy = $this->ordersRepo->createReceivedEmail($orderResult->groupkey);
                    $topbeansCopy = $this->ordersRepo->createNewOrderAlertEmail($orderResult->groupkey);
                    $this->emailSvc->sendMail($shoppersCopy);
                    $this->emailSvc->sendMail($topbeansCopy);
                    $this->addFlashSuccessMsgs([FlashMessages::OrderComplete]);
                }
                
                $response = ResponseUtils::responseItem($orderResult);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function takepaymentAction() {
            try {
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\PaymentDetails", "json");
                
                $groupKey = $this->ordersRepo->getGroupByCookie($data->cookiekey);
                $amount = $this->ordersRepo->getOrderTotalByCookie($data->cookiekey);
                
                if ($amount == 0 || $groupKey == null) {
                    throw new \Exception("Could find Order");
                }
                
                $remote = new RemoteAddress();
                $shopperIpAddress = $remote->setUseProxy()->getIpAddress();
                $shopperSessionId = $this->sessionManager->getId();

                $currencyCode = "GBP";
                $customer = $this->ordersRepo->getCustomerByGroup($groupKey);
                $dl = $this->ordersRepo->getAddress($customer->getDeliveryaddresskey());
                $bl = $this->ordersRepo->getAddress($customer->getBillingaddresskey());
                
                $billingAddress = [
                    "address1" => $bl->getAddress1(),
                    "address2" => $bl->getAddress2(),
                    "address3" => $bl->getAddress3(),
                    "postalCode" => $bl->getPostcode(),
                    "city" => $bl->getCity(),
                    "state" => $bl->getState(),
                    "countryCode" => $bl->getCountrycode(),
                ];
                
                $deliveryAddress = [
                    "firstName" => $dl->getFirstname(),
                    "lastName" => $dl->getLastname(),
                    "address1" => $dl->getAddress1(),
                    "address2" => $dl->getAddress2(),
                    "address3" => $dl->getAddress3(),
                    "postalCode" => $dl->getPostcode(),
                    "city" => $dl->getCity(),
                    "state" => $dl->getState(),
                    "countryCode" => $dl->getCountrycode(),
                    "telephoneNumber" => $dl->getPhone(),
                ];
                
                $worldpay = new Worldpay($this->worldPayServiceKey);
                $wpResponse = $worldpay->createOrder([
                    'token' => $data->token,
                    'amount' => round($amount, 2) * 100, // Amount is only transacted in cents/pennies
                    'currencyCode' => $currencyCode,
                    'settlementCurrency' => $this->settlementCurrency,
                    'name' => $dl->getLastname() . " " . $dl->getFirstname(),
                    'billingAddress' => $billingAddress,
                    'deliveryAddress' => $deliveryAddress,
                    'shopperEmailAddress' => $dl->getEmail(),
                    'shopperIpAddress' => $shopperIpAddress,
                    'shopperSessionId' => $shopperSessionId,
                    'orderDescription' => 'Top Beans Coffee',
                    'customerOrderCode' => $groupKey
                ]);

                if ($wpResponse["paymentStatus"] == "SUCCESS") {
                    $this->ordersRepo->receiveOrderByCookie($data->cookiekey);
                    $shoppersCopy = $this->ordersRepo->createReceivedEmail($groupKey);
                    $topbeansCopy = $this->ordersRepo->createNewOrderAlertEmail($groupKey);
                    $this->emailSvc->sendMail($shoppersCopy);
                    $this->emailSvc->sendMail($topbeansCopy);
                    $this->addFlashSuccessMsgs([FlashMessages::OrderComplete]);
                }
                
                $response = ResponseUtils::responseItem($wpResponse);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
    }
}