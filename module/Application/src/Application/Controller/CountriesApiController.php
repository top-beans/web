<?php

namespace Application\Controller {
    
    use Zend\Navigation\AbstractContainer;
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;
    use Application\API\Repositories\Interfaces\ICountriesRepository;
    use Application\API\Canonicals\Response\ResponseUtils;

    class CountriesApiController extends BaseController {
        
        /**
         * @var ICoffeeRepository
         */
        private $countriesRepo;
        
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer, ICountriesRepository $countriesRepo) {
            parent::__construct($navService, $authService, $serializer);
            $this->countriesRepo = $countriesRepo;
        }
        
        public function getcountriesAction() {
            try {
                $items = $this->countriesRepo->getCountries();
                $response = ResponseUtils::responseList($items);
                return $this->jsonResponse($response);
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
    }
}