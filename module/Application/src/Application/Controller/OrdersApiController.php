<?php

namespace Application\Controller {
    
    use Zend\Navigation\AbstractContainer;
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;
    use Worldpay\WorldpayException;
    use Application\API\Canonicals\Entity\OrderStatuses;
    use Application\API\Canonicals\Response\ResponseUtils;
    use Application\API\Repositories\Interfaces\IOrdersRepository;
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
        private $worldpayServiceKey;
        
        /**
         * @var string
         */
        private $settlementCurrency;
        
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer, IOrdersRepository $ordersRepo, IUsersRepository $usersRepository, ManagerInterface $sessionManager, $maxLoginTries, $worldpayServiceKey, $settlementCurrency) {
            parent::__construct($navService, $authService, $serializer);
            $this->ordersRepo = $ordersRepo;
            $this->usersRepository = $usersRepository;
            $this->sessionManager = $sessionManager;
            $this->maxLoginTries = $maxLoginTries;
            $this->worldpayServiceKey = $worldpayServiceKey;
            $this->settlementCurrency = $settlementCurrency;
        }
        
        public function searchorderheadersAction(){
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $jsonData = $this->getRequest()->getContent();
                $params = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\SearchParams", "json");
                
                $response = $this->ordersRepo->searchOrderHeaders($params->getCriteria(), $params->getOrderby(), $params->getPage(), $params->getPagesize());
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function getorderAction(){
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $groupKey = $this->getRequest()->getContent();
                $orderViewItems = $this->ordersRepo->getOrder($groupKey);
                
                $response = ResponseUtils::responseList($orderViewItems);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function getorderheaderAction(){
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $groupKey = $this->getRequest()->getContent();
                $orderHeader = $this->ordersRepo->getOrderHeader($groupKey);
                
                $response = ResponseUtils::responseItem($orderHeader);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function getordertotalAction(){
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $groupKey = $this->getRequest()->getContent();
                $order = $this->ordersRepo->getOrderTotalByGroup($groupKey);
                
                $response = ResponseUtils::responseItem($order);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function cancelorderitemAction() {
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\OrderItem", "json");
                
                $groupkey = $data->groupkey;
                $coffeeKey = $data->coffeekey;
                
                $orderViewItem = $this->ordersRepo->cancelItem($groupkey, $coffeeKey);
                $response = ResponseUtils::responseItem($orderViewItem);
                return $this->jsonResponse($response);
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function returnorderitemAction() {
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\OrderItem", "json");
                
                $groupkey = $data->groupkey;
                $coffeeKey = $data->coffeekey;
                
                $orderViewItem = $this->ordersRepo->returnItem($groupkey, $coffeeKey);
                $response = ResponseUtils::responseItem($orderViewItem);
                return $this->jsonResponse($response);
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function requestitemrefundAction() {
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\OrderItem", "json");
                
                $groupkey = $data->groupkey;
                $coffeeKey = $data->coffeekey;
                
                $orderViewItem = $this->ordersRepo->requestItemRefund($groupkey, $coffeeKey);
                $response = ResponseUtils::responseItem($orderViewItem);
                return $this->jsonResponse($response);
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function dispatchitemsAction() {
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\OrderItems", "json");
                
                $groupkey = $data->groupkey;
                $coffeeKeys = $data->coffeekeys;
                
                $orderViewItems = $this->ordersRepo->dispatchItems($groupkey, $coffeeKeys);
                $response = ResponseUtils::responseList($orderViewItems);
                return $this->jsonResponse($response);
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function cancelorderAction() {
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $groupKey = $this->getRequest()->getContent();
                
                $orderHeader = $this->ordersRepo->cancelOrder($groupKey);
                $response = ResponseUtils::responseItem($orderHeader);
                return $this->jsonResponse($response);
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function returnorderAction() {
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $groupKey = $this->getRequest()->getContent();
                
                $orderHeader = $this->ordersRepo->returnOrder($groupKey);
                $response = ResponseUtils::responseItem($orderHeader);
                return $this->jsonResponse($response);
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function dispatchorderAction() {
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $groupKey = $this->getRequest()->getContent();
                
                $orderHeader = $this->ordersRepo->dispatchOrder($groupKey);
                $response = ResponseUtils::responseItem($orderHeader);
                return $this->jsonResponse($response);
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }

        public function requestorderrefundAction() {
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $groupKey = $this->getRequest()->getContent();
                
                $orderHeader = $this->ordersRepo->requestOrderRefund($groupKey);
                $response = ResponseUtils::responseItem($orderHeader);
                return $this->jsonResponse($response);
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function updatecustomeraddressesAction() {
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\CustomerAddresses", "json");
                
                $this->ordersRepo->updateAddresses($data->deliveryaddress, $data->billingaddress);
                $response = ResponseUtils::response();

                return $this->jsonResponse($response);
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
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
                $amount = $this->ordersRepo->getOrderTotalByCookie($data->cookiekey, OrderStatuses::Creating);
                
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
                
                $worldpay = new Worldpay($this->worldpayServiceKey);
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

                if ($wpResponse["paymentStatus"] != "SUCCESS") {
                    throw new \Exception($wpResponse["paymentStatusReason"]);
                } else {
                    $this->ordersRepo->receiveOrder($groupKey, $wpResponse["orderCode"]);
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