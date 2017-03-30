<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\API\Repositories\Implementations\WordPressNavigation;

    class WordPressNavigationFactory implements FactoryInterface {

        public function createService(ServiceLocatorInterface $serviceLocator) {
            $wpRepo = $serviceLocator->get('WordPrRepo');
            $configuration = $serviceLocator->get('Config');
            $navigation = $configuration['navigation'];
            $wordpress_root_node = $configuration['wordpress_root_node'];
            
            $wpNavigation =  new WordPressNavigation($wpRepo, $navigation, $wordpress_root_node);
            return $wpNavigation->createService($serviceLocator);
        }
    }
}
