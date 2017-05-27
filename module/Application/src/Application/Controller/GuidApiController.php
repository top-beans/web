<?php

namespace Application\Controller {
    
    use Zend\Navigation\AbstractContainer;
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;    
    use Application\API\Canonicals\Response\ResponseUtils;

    class GuidApiController  extends BaseController {
        
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer) {
            parent::__construct($navService, $authService, $serializer);
        }
        
        public function getAction() {
            try {
                $response = ResponseUtils::responseItem(uniqid());
                return $this->jsonResponse($response);
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
    }
}