<?php

namespace Application\Controller {
    
    use Zend\Navigation\AbstractContainer;
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;
    use Application\API\Repositories\Interfaces\ICountryRepository;
    use Application\API\Canonicals\Response\ResponseUtils;

    class CountryApiController extends BaseController {
        
        /**
         * @var ICountryRepository
         */
        private $countryRepo;
        
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer, ICountryRepository $cartRepo) {
            parent::__construct($navService, $authService, $serializer);
            $this->countryRepo = $cartRepo;
        }

        public function getcountriesAction() {
            try {
                $countries = $this->countryRepo->getCountries();
                $response = ResponseUtils::responseList($countries);
                return $this->jsonResponse($response);

            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
    }
}