<?php

namespace Application\Controller {
    
    use Zend\Navigation\AbstractContainer;
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;
    use Application\API\Repositories\Interfaces\IClientsRepository;
    use Application\API\Canonicals\Response\ResponseUtils;
    use Application\API\Canonicals\Dto\SearchParams;

    class ClientsApiController extends BaseController {
        
        /**
         * @var IClientsRepository
         */
        private $clientsRepository;
        
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer, IClientsRepository $clientsRepository) {
            parent::__construct($navService, $authService, $serializer);
            $this->clientsRepository = $clientsRepository;
        }
        
        public function searchclientsAction(){
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $jsonData = $this->getRequest()->getContent();
                $params = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\SearchParams", "json");
                
                $response = $this->clientsRepository->searchClients($params->getCriteria(), $params->getOrderby(), $params->getPage(), $params->getPagesize());
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function exportalltoexcelAction() {
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $objPHPExcel = $this->clientsRepository->exportAllClientsToExcel();
                $date = (new \DateTime())->format("Y-d-m");
                $name = "All-Clients-$date.xlsx";
                $this->clientsRepository->doExport($name, $objPHPExcel);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function exporttoexcelAction () {
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $result = [];
                parse_str($this->p1, $result);
                $params = new SearchParams();
                $params->setPage($result['page']);
                $params->setPagesize($result['pagesize']);
                $params->setCriteria($result['criteria']);
                $params->setOrderby($result['orderby']);
                
                $objPHPExcel = $this->clientsRepository->exportClientsToExcel($params->getCriteria(), $params->getOrderby(), $params->getPage(), $params->getPagesize());
                $date = (new \DateTime())->format("Y-d-m");
                $name = "Search-Clients-$date.xlsx";
                $this->clientsRepository->doExport($name, $objPHPExcel);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function addorupdateclientAction(){
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $jsonData = $this->getRequest()->getContent();
                $client = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Entity\Client", "json");
                
                $this->clientsRepository->addOrUpdateClient($client);
                
                $response = ResponseUtils::createWriteResponse($client);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
    }
}