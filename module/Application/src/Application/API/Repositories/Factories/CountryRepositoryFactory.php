<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Doctrine\ORM\EntityRepository,
        Doctrine\ORM\Mapping\ClassMetadata,
        Application\API\Canonicals\Entity\Country,
        Application\API\Repositories\Base\Repository,
        Application\API\Repositories\Implementations\CountryRepository;
    
    class CountryRepositoryFactory implements FactoryInterface {
        
        public function createService(ServiceLocatorInterface $serviceLocator) {
            $em = $serviceLocator->get('doctrine.entitymanager.orm_default');
            $rp = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Country()))));
            
            return new CountryRepository($rp);
        }
    }
}
