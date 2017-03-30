<?php

namespace Application\API\Repositories\Implementations {

    use Doctrine\ORM\EntityManager,
        Doctrine\ORM\EntityRepository,
        Doctrine\ORM\Mapping\ClassMetadata,
        Application\API\Canonicals\Entity,
        Application\API\Repositories\Base\Repository;

    class BaseRepository {
        
        /**
         * @var EntityManager 
         */
        protected $em;
        
        /**
         * @var Repository
         */
        protected $enquiryRepo;
        /**
         * @var Repository
         */
        protected $emailsRepo;
        /**
         * @var Repository
         */
        protected $usersRepo;
        /**
         * @var Repository
         */
        protected $clientsRepo;
        
        public function __construct(EntityManager $em) {
            $this->em = $em;
            
            $this->enquiryRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Entity\Enquiry()))));
            $this->emailsRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Entity\Email()))));
            $this->usersRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Entity\User()))));
            $this->clientsRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Entity\Client()))));
        }
    }
}