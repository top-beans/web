<?php

namespace Application\API\Repositories\Implementations {
    
    use Zend\Navigation\Service\DefaultNavigationFactory;
    use Interop\Container\ContainerInterface;
    use Application\API\Repositories\Interfaces\IWordPressRepository;
    
    class WordPressNavigation extends DefaultNavigationFactory {
        
        /**
         * @var IWordPressRepository
         */
        private $wpRepo;
        
        /**
         *
         * @var array
         */
        private $configNavigation;
        
        /**
         *
         * @var array
         */
        private $wp_root_node;
        
        public function __construct(IWordPressRepository $wpRepo, $configNavigation, $wordpress_root_node) {
            $this->wpRepo = $wpRepo;
            $this->configNavigation = $configNavigation;
            $this->wp_root_node = $wordpress_root_node;
        }        
        
        protected function getPages(ContainerInterface $serviceLocator) {
            
            if ($this->pages === null) {

                if (!isset($this->configNavigation)) {
                    throw new Exception\InvalidArgumentException('Could not find navigation configuration key');
                }
                
                if (!isset($this->configNavigation[$this->getName()])) {
                    throw new Exception\InvalidArgumentException(sprintf(
                        'Failed to find a navigation container by the name "%s"',
                        $this->getName()
                    ));
                }

                $controller    = $this->wp_root_node['controller'];
                $route         = $this->wp_root_node['route'];
                $action        = $this->wp_root_node['action'];
                $rootslug      = $this->wp_root_node['rootslug'];
                $requireslogin = $this->wp_root_node['requireslogin'];

                $cats = $this->wpRepo->fetchChildCategories($rootslug);

                foreach($cats as $rec) {

                    $id = $rec->getSlug().".$controller.$action";

                    $this->configNavigation[$this->getName()][$id] = array(
                        'id'            => $id,
                        'label'         => $rec->getName(),
                        'route'         => $route,
                        'controller'    => $controller,
                        'action'        => $action,
                        'requireslogin' => $requireslogin,
                        'params'        => array('p1' => $rec->getSlug()),
                    );

                    $subCats = $this->wpRepo->fetchChildCategories($rec->getSlug());

                    foreach($subCats as $subRec) {

                        $subId = $subRec->getSlug() . ".$id";
                        $this->configNavigation[$this->getName()][$id]['pages'][$subId] = array(
                            'id'            => $subId,
                            'label'         => $subRec->getName(),
                            'route'         => $route,
                            'controller'    => $controller,
                            'action'        => $action,
                            'requireslogin' => $requireslogin,
                            'params'        => array(
                                'p1' => $rec->getSlug(),
                                'p2' => $subRec->getSlug()
                            ),
                        );
                    }
                }

                $pages       = $this->getPagesFromConfig($this->configNavigation[$this->getName()]);
                $this->pages = $this->preparePages($serviceLocator, $pages);
            }
            
            return $this->pages;
        }
    }
}
