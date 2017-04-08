<?php

namespace Application\Controller {
    
    use Zend\Navigation\AbstractContainer;
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;    
    use Application\API\Repositories\Interfaces\IUsersRepository;
    use Application\API\Canonicals\Response\ResponseUtils;

    class SecurityApiController extends BaseController {
        
        /**
         * @var IUsersRepository
         */
        private $usersRepository;
        
        /**
         * @var number
         */
        private $maxLoginTries;
        
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer, IUsersRepository $usersRepository, $maxLoginTries) {
            parent::__construct($navService, $authService, $serializer);
            $this->usersRepository = $usersRepository;
            $this->maxLoginTries = $maxLoginTries;
        }
        
        public function loginAction(){
            try {
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\Credentials", "json");
                
                $username = trim(strtolower($data->getUsername()));
                $password = $data->getPassword();
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
                    $response = ResponseUtils::createResponse($result->getMessages());
                    return $this->jsonResponse($response);
                } else {
                    $this->usersRepository->resetTriesAndLogin($username);
                    $this->authService->getStorage()->write($username);
                    $response = ResponseUtils::createResponse();
                    return $this->jsonResponse($response);
                }
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function logoutAction() {
            try {
                $this->authService->clearIdentity();
                $response = ResponseUtils::createResponse();
                return $this->jsonResponse($response);
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
    }
}