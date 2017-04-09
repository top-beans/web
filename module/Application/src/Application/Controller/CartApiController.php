<?php

namespace Application\Controller {
    
    use Zend\Navigation\AbstractContainer;
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;
    use Application\API\Repositories\Interfaces\ICartRepository;
    use Application\API\Canonicals\Response\ResponseUtils;

    class CartApiController extends BaseController {
        
        /**
         * @var ICartRepository
         */
        private $cartRepo;
        
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer, ICartRepository $cartRepo) {
            parent::__construct($navService, $authService, $serializer);
            $this->cartRepo = $cartRepo;
        }
   }
}