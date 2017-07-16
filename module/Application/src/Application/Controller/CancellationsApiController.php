<?php

namespace Application\Controller {
    
    use Zend\Navigation\AbstractContainer;
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;
    use Application\API\Canonicals\Response\ResponseUtils;
    use Application\API\Repositories\Interfaces\ICancellationsRepository;
    use Application\API\Repositories\Interfaces\IOrdersRepository;
    
    class CancellationsApiController extends BaseController {
        
        /**
         * @var ICancellationsRepository
         */
        private $cancelRepo;
        
        /**
         * @var IOrdersRepository
         */
        private $ordersRepo;
        
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer, ICancellationsRepository $cancelRepo, IOrdersRepository $ordersRepo) {
            parent::__construct($navService, $authService, $serializer);
            $this->cancelRepo = $cancelRepo;
            $this->ordersRepo = $ordersRepo;
        }
        
        public function confirmgroupkeyAction(){
            try {
                $groupKey = $this->getRequest()->getContent();
                $result = $this->cancelRepo->confirmGroupKey($groupKey);
                
                if (!$result) {
                    throw new \Exception("Invalid Order Number");
                }
                
                $this->cancelRepo->createAndSendCode($groupKey);
                $maskedEmail = $this->cancelRepo->getMaskedEmail($groupKey);
                
                $response = ResponseUtils::responseItem($maskedEmail);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function confirmcodeAction(){
            try {
                $code = $this->getRequest()->getContent();
                $result = $this->cancelRepo->confirmCode($code);
                
                if (!$result) {
                    throw new \Exception("Invalid Code");
                }
                
                $groupKey = $this->ordersRepo->getGroupByCode($code);
                $orderViewItems = $this->ordersRepo->getOrder($groupKey);
                $response = ResponseUtils::responseList($orderViewItems);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function getordertotalAction(){
            try {
                $code = $this->getRequest()->getContent();
                
                if (!$this->cancelRepo->isAuthenticated($code)) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $groupKey = $this->ordersRepo->getGroupByCode($code);
                $orderTotal = $this->ordersRepo->getOrderTotalByGroup($groupKey);
                
                $response = ResponseUtils::responseItem($orderTotal);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function cancelorderitemsAction() {
            try {
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\OrderItems", "json");
                
                $code = $data->groupkey;
                $coffeeKeys = $data->coffeekeys;
                
                if (!$this->cancelRepo->isAuthenticated($code)) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $groupKey = $this->ordersRepo->getGroupByCode($code);
                $orderViewItems = $this->ordersRepo->cancelItems($groupKey, $coffeeKeys);
                
                $refundables = [];
                
                foreach($orderViewItems as $orderViewItem) {
                    if ($orderViewItem->getIsrefundable()) {
                        $refundables[] = $orderViewItem->getCoffeekey();
                    }
                }
                
                if (count($refundables) > 0) {
                    $orderViewItems = $this->ordersRepo->requestItemsRefund($groupKey, $refundables);   
                }
                
                $response = ResponseUtils::responseList($orderViewItems);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
    }
}