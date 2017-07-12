<?php

namespace Application\Controller {
    
    use Zend\Mvc\Controller\AbstractActionController;
    use JMS\Serializer\SerializerInterface;
    use Zend\Http\Response;
    use Application\API\Repositories\Interfaces\IOrdersRepository;
    
    class WorldpayApiController extends AbstractActionController {
        
        /**
         * @var IOrdersRepository
         */
        private $ordersRepo;
        
        /**
         * @var SerializerInterface
         */
        private $serializer;
        
        public function __construct(SerializerInterface $serializer, IOrdersRepository $ordersRepo) {
            $this->serializer = $serializer;
            $this->ordersRepo = $ordersRepo;
        }
        
        public function refundupdateAction() {
            try {
                $jsonData = $this->getRequest()->getContent();
                $webhook = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\Webhook", "json");
                $this->ordersRepo->refundUpdate($webhook);
                
                $this->response->setStatusCode(Response::STATUS_CODE_200);
                return $this->response;
                
            } catch (\Exception $ex) {
                $this->response->setStatusCode(Response::STATUS_CODE_500);
                return $this->response;
            }
        }
    }
}