<?php

namespace Application\API\Repositories\Implementations {

    use Application\API\Repositories\Base\IRepository;
    use Application\API\Repositories\Interfaces\ICountryRepository;

    class CountryRepository implements ICountryRepository {
        
        /**
         * @var IRepository
         */
        protected $repository;
        
        public function __construct(IRepository $repository) {
            $this->repository = $repository;
        }

        public function getCountries() {
            return $this->repository->fetchAll();
        }
    }
}
