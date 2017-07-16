<?php

namespace Application\Controller {
    
    use Zend\Navigation\AbstractContainer;
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;
    use Application\API\Canonicals\Entity\Orderview;
    use Application\API\Canonicals\Entity\OrderStatuses;
    use Application\API\Canonicals\Response\ResponseUtils;
    use Application\API\Repositories\Interfaces\IOrdersRepository;
    use Application\API\Repositories\Interfaces\IUsersRepository;
    use Application\API\Canonicals\Constants\FlashMessages;
    use Worldpay\Worldpay;
    use Zend\Http\PhpEnvironment\RemoteAddress;
    use Zend\Session\ManagerInterface;
    
    class CancellationsApiController extends BaseController {
        
        /**
         * @var IOrdersRepository
         */
        private $ordersRepo;
        
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer, IOrdersRepository $ordersRepo) {
            parent::__construct($navService, $authService, $serializer);
            $this->ordersRepo = $ordersRepo;
        }
        
        public function getorderAction(){
            try {
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\CancellationDetails", "json");
                
                $orderViewItems = $this->ordersRepo->getOrderByCancellationDetails($data);
                $response = ResponseUtils::responseList($orderViewItems);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function cancelorderitemAction() {
            try {
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\CancellationDetails", "json");
                
                $orderViewItem = $this->ordersRepo->cancelItemByCancellationDetails($data);
                $response = ResponseUtils::responseItem($orderViewItem);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function cancelorderAction() {
            try {
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\CancellationDetails", "json");
                
                $orderHeader = $this->ordersRepo->cancelOrderByCancellationDetails($data);
                $response = ResponseUtils::responseItem($orderHeader);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
    }
}