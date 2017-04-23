<?php

namespace Application\API\Repositories\Implementations {

    use Doctrine\ORM\EntityManager,
        Doctrine\ORM\EntityRepository,
        Doctrine\ORM\Mapping\ClassMetadata,
        Application\API\Repositories\Interfaces\ICountriesRepository,
        Application\API\Repositories\Base\IRepository,
        Application\API\Repositories\Base\Repository,
        Application\API\Canonicals\Entity\Country;

    class CountriesRepository implements ICountriesRepository {
        
        /**
         * @var EntityManager 
         */
        protected $em;
        
        /**
         * @var IRepository
         */
        protected $countryRepo;
        
        public function __construct(EntityManager $em) {
            $this->em = $em;
            $this->countryRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Country()))));
        }

        public function getCountries() {
            $this->countryRepo->fetchAll();
        }
    }
}
