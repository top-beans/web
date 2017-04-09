<?php

namespace Application {
    
    use Application\API\Canonicals\Constants\Navigation;
    use Application\API\Canonicals\WordPress\CategorySlugs;

    return [
        'controllers' => [
            'invokables' => [],
            'factories' => [
                'Admin'         => 'Application\ControllerFactory\AdminControllerFactory',
                'Index'         => 'Application\ControllerFactory\IndexControllerFactory',
                'SecurityApi'   => 'Application\ControllerFactory\SecurityApiControllerFactory',
                'UsersApi'      => 'Application\ControllerFactory\UsersApiControllerFactory',
                'EnquiryApi'    => 'Application\ControllerFactory\EnquiryApiControllerFactory',
                'ClientsApi'    => 'Application\ControllerFactory\ClientsApiControllerFactory',
                'CoffeeApi'     => 'Application\ControllerFactory\CoffeeApiControllerFactory',
                'CartApi'       => 'Application\ControllerFactory\CartApiControllerFactory',
                'WordPressApi'  => 'Application\ControllerFactory\WordPressApiControllerFactory',
                'BatchMail'     => 'Application\ControllerFactory\BatchMailControllerFactory',
            ],
        ],

        'router' => [
            'routes' => [
                'excelexport' => [
                    'type' => 'segment',
                    'options' => [
                        'route' => '/excelexport/:controller/:action/:p1',
                        'constraints' => [
                            'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'p1'         => '.*',
                        ],
                        'defaults' => [
                            'controller' => 'ClientsApi',
                            'action'     => 'exporttoexcel',
                        ],
                    ],
                ],
                'web' => [
                    'type' => 'segment',
                    'options' => [
                        'route' => '/[:controller[/:action[/:p1[/:p2[/:p3[/:p4]]]]]]',
                        'constraints' => [
                            'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'p1'         => '[a-zA-Z0-9_-]*',
                            'p2'         => '[a-zA-Z0-9_-]*',
                            'p3'         => '[a-zA-Z0-9_-]*', 
                            'p4'         => '[a-zA-Z0-9_-]*', 
                        ],
                        'defaults' => [
                            'controller' => 'Index',
                            'action'     => 'index',
                        ],
                    ],
                ],
                'api' => [
                    'type' => 'segment',
                    'options' => [
                        'route' => '/api/:controller/:action[/:p1[/:p2[/:p3[/:p4]]]]',
                        'constraints' => [
                            'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'p1'         => '[a-zA-Z0-9_-]*',
                            'p2'         => '[a-zA-Z0-9_-]*',
                            'p3'         => '[a-zA-Z0-9_-]*',
                            'p4'         => '[a-zA-Z0-9_-]*',
                        ],
                    ],
                ],
            ],
        ],

        'service_manager' => [
            'abstract_factories' => [
                'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
                'Zend\Log\LoggerAbstractServiceFactory',
            ],
            'factories' => [
                'Navigation'        => 'Zend\Navigation\Service\DefaultNavigationFactory',
                'WordPrRepo'        => 'Application\API\Repositories\Factories\WordPressRepositoryFactory',
                'EMailSvc'          => 'Application\API\Repositories\Factories\EMailServiceFactory',
                'UsersRepo'         => 'Application\API\Repositories\Factories\UsersRepositoryFactory',
                'AdminAuthService'  => 'Application\API\Repositories\Factories\AdminAuthServiceFactory',
                'ClientsRepo'       => 'Application\API\Repositories\Factories\ClientsRepositoryFactory',
                'CoffeeRepo'        => 'Application\API\Repositories\Factories\CoffeeRepositoryFactory',
                'CartRepo'          => 'Application\API\Repositories\Factories\CartRepositoryFactory',
                'EnquiryRepo'       => 'Application\API\Repositories\Factories\EnquiryRepositoryFactory',
            ],
        ],

        'doctrine' => [
            'driver' => [
                __NAMESPACE__ . '_driver' => [
                    'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                    'cache' => 'array',
                    'paths' => [__DIR__ . '/../src/' . __NAMESPACE__ . '/API/Canonicals/Entity']
                ],
                'orm_default' => [
                    'drivers' => [
                        __NAMESPACE__ . '\API\Canonicals\Entity' => __NAMESPACE__ . '_driver'
                    ]
                ]
            ]
        ],


        'view_manager' => [
            'display_not_found_reason' => true,
            'display_exceptions'       => true,
            'doctype'                  => 'HTML5',
            'not_found_template'       => 'error/404',
            'exception_template'       => 'error/index',
            'template_map' => [
                'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
                'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
                'error/404'               => __DIR__ . '/../view/error/404.phtml',
                'error/index'             => __DIR__ . '/../view/error/index.phtml',
                'partials/menu'           => __DIR__ . '/../view/partials/_menu.phtml',
                'layout/messages'         => __DIR__ . '/../view/partials/_messages.phtml',
            ],
            'template_path_stack' => [
                __DIR__ . '/../view',
            ],
        ],

        'ENV' => (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : (getenv('REDIRECT_APPLICATION_ENV') ? getenv('REDIRECT_APPLICATION_ENV') : 'development')),

        'navigation' => [
            'default' => [
                'Index.preview' => [
                    'id' => Navigation::Preview,
                    'label' => 'Preview',
                    'route' => 'web',
                    'controller' => 'Index',
                    'action' => 'preview',
                    'visible' => false,
                ],
                'Admin' => [
                    'id' => Navigation::Admin,
                    'label' => 'Admin',
                    'route' => 'web',
                    'controller' => 'Admin',
                    'action' => 'index',
                    'visible' => false,
                    'pages' => [
                        'enquiries' => [
                            'id' => 'Admin.enquiries',
                            'label' => 'Enquiries',
                            'route' => 'web',
                            'controller' => 'Admin',
                            'action' => 'enquiries',
                            'visible' => false,
                            'requireslogin' => true,
                        ],
                        'useradmin' => [
                            'id' => 'Admin.useradmin',
                            'label' => 'User Admin',
                            'route' => 'web',
                            'controller' => 'Admin',
                            'action' => 'useradmin',
                            'visible' => false,
                            'requireslogin' => true,
                        ],
                        'Clients' => [
                            'id' => 'Admin.Clients',
                            'label' => 'Clients',
                            'route' => 'web',
                            'controller' => 'Admin',
                            'action' => 'clients',
                            'visible' => false,
                            'requireslogin' => true,
                        ],
                        'logout' => [
                            'id' => 'Admin.logout',
                            'label' => 'Logout',
                            'route' => 'web',
                            'controller' => 'Admin',
                            'action' => 'logout',
                            'visible' => false,
                            'requireslogin' => true,
                        ],
                    ],
                ],
                'Index.index' => [
                    'id' => Navigation::Home,
                    'label' => 'Home',
                    'route' => 'web',
                    'controller' => 'Index',
                    'action' => 'index',
                    'requireslogin' => false,
                ],
                'Index.approach' => [
                    'id' => 'Approach',
                    'label' => 'What we do',
                    'route' => 'web',
                    'controller' => 'Index',
                    'action' => 'approach',
                    'requireslogin' => false,
                ],
                'Index.buyorsample' => [
                    'id' => 'Buy',
                    'label' => 'Buy or Sample',
                    'route' => 'web',
                    'controller' => 'Index',
                    'action' => 'buyorsample',
                    'requireslogin' => false,
                ],
                'Index.contacts' => [
                    'id' => 'Contacts',
                    'label' => 'Contacts',
                    'route' => 'web',
                    'controller' => 'Index',
                    'action' => 'contacts',
                    'requireslogin' => false,
                ],
            ],
        ],
    ];
}
