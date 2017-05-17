<?php

namespace Application\Controller {
    
    use Zend\Navigation\AbstractContainer;
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;
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

        public function addanonymousorderAction() {
            try {
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\Checkout", "json");
                
                $requiresPayment = $this->ordersRepo->addAnonymousOrder($data->cookie, $data->deliveryaddress, $data->billingaddress);
                
                if (!$requiresPayment) {
                    $this->ordersRepo->createReceivedEmail($data->cookie);
                }
                
                $response = ResponseUtils::responseItem($requiresPayment);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function adduserorderAction() {
            try {
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\Checkout", "json");

                if ($data->user == null) {
                    throw new \Exception("Invalid credentials");
                }
                
                $username = trim(strtolower($data->user->getUsername()));
                $password = $data->user->getPassword();
                $user     = $this->usersRepository->find($username);
                
                if ($user == null) {
                    throw new \Exception("Invalid credentials");
                }
                
                $tries = $user->getTries();

                if ($tries >= $this->maxLoginTries) {
                    $this->authService->clearIdentity();
                    throw new \Exception("This account has been locked");
                }

                $this->authService->getAdapter()->setIdentity($username)->setCredential($password);
                $result = $this->authService->authenticate();
                
                if (!$result->isValid() && $tries + 1 == $this->maxLoginTries) {
                    $this->usersRepository->incrementTries($username);
                    $this->authService->clearIdentity();
                    throw new \Exception("This account has been locked");
                } else if (!$result->isValid()) {
                    $this->usersRepository->incrementTries($username);
                    $response = ResponseUtils::response($result->getMessages());
                    return $this->jsonResponse($response);
                } else {
                    $this->usersRepository->resetTriesAndLogin($username);
                    $this->authService->getStorage()->write($this->usersRepository->find($username));
                }
                
                $requiresPayment = $this->ordersRepo->addUserOrder($data->cookie, $data->user->getUsername(), $data->user->getPassword());

                if (!$requiresPayment) {
                    $this->ordersRepo->createReceivedEmail($data->cookie);
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