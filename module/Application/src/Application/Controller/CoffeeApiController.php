<?php

namespace Application\Controller {
    
    use Zend\Navigation\AbstractContainer;
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;
    use Application\API\Repositories\Interfaces\ICoffeeRepository;
    use Application\API\Canonicals\Response\ResponseUtils;

    class CoffeeApiController extends BaseController {
        
        /**
         * @var ICoffeeRepository
         */
        private $coffeeRepo;
        
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer, ICoffeeRepository $coffeeRepo) {
            parent::__construct($navService, $authService, $serializer);
            $this->coffeeRepo = $coffeeRepo;
        }
   }
}