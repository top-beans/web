<?php

namespace Application\Controller {
    
    use Zend\Navigation\AbstractContainer;
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;
    use Application\API\Repositories\Interfaces\IUsersRepository;
    use Application\API\Canonicals\Response\ResponseUtils;

    class UsersApiController extends BaseController {
        
        /**
         * @var IUsersRepository
         */
        private $usersRepository;
        
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer, IUsersRepository $usersRepository) {
            parent::__construct($navService, $authService, $serializer);
            $this->usersRepository = $usersRepository;
        }
        
        public function getallAction() {
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $items = $this->usersRepository->findAll();
                $response = ResponseUtils::createFetchResponse($items);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function addAction() {
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $jsonData = $this->getRequest()->getContent();
                $user = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Entity\User", "json");
                
                $this->usersRepository->addUser($user);
                $response = ResponseUtils::createWriteResponse($user);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function updateAction() {
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $jsonData = $this->getRequest()->getContent();
                $user = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Entity\User", "json");
                
                $this->usersRepository->updateUser($user, $user->getPassword());
                $response = ResponseUtils::createWriteResponse($user);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function mergeAction() {
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $jsonData = $this->getRequest()->getContent();
                $user = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Entity\User", "json");
                
                $this->usersRepository->addOrUpdateUser($user);
                $response = ResponseUtils::createWriteResponse($user);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
    }
}