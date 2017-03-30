<?php

namespace Application\Controller {
    
    use Zend\Navigation\AbstractContainer;
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;
    use Application\API\Repositories\Interfaces\IEnquiryRepository;
    use Application\API\Repositories\Interfaces\IEMailService;
    use Application\API\Canonicals\Response\ResponseUtils;

    class EnquiryApiController extends BaseController {
        
        /**
         * @var IEnquiryRepository
         */
        private $enquiryRepo;
        
        /**
         * @var IEMailService
         */
        private $emailSvc;
        
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer, IEnquiryRepository $usersRepository, IEMailService $emailSvc) {
            parent::__construct($navService, $authService, $serializer);
            $this->enquiryRepo = $usersRepository;
            $this->emailSvc = $emailSvc;
        }
        
        public function searchAction(){
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $jsonData = $this->getRequest()->getContent();
                $params = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\SearchParams", "json");
                
                $response = $this->enquiryRepo->search($params->getPage(), $params->getPagesize(), $params->getOrderby());
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function addAction() {
            try {
                $jsonData = $this->getRequest()->getContent();
                $enquiry = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Entity\Enquiry", "json");
                
                $enquiry->setCreateddate(new \DateTime());
                $this->enquiryRepo->add($enquiry);
                $email = $this->enquiryRepo->createEmail($enquiry);
                $this->emailSvc->sendMail($email);
                
                $response = ResponseUtils::createWriteResponse($enquiry);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
    }
}