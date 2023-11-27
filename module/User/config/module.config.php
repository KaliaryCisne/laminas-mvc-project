<?php

declare(strict_types=1);

namespace User;

use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Router\Http\Segment;
use Laminas\Session\SessionManager;
use User\Model\User;
use User\Model\UserTable;
use User\Validator\UniqueEmailValidator;

return [
    'router' => [
        'routes' => [
            'user' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/user[/:controller[/:action[/:key]]]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'aliases' => [
            'create' => Controller\CreateController::class,
            'delete' => Controller\DeleteController::class,
            'search' => Controller\SearchController::class,
            'update' => Controller\UpdateController::class,
        ],
        'factories' => [
            Controller\CreateController::class => function($sm) {
                $table = $sm->get(UserTable::class);
                $sessionManager = new SessionManager();
                return new Controller\CreateController($table, $sessionManager);
            },
            Controller\DeleteController::class => function($sm) {
                $table = $sm->get(UserTable::class);
                return new Controller\DeleteController($table);
            },
            Controller\IndexController::class  => function($sm) {
                $table = $sm->get(UserTable::class);
                return new Controller\IndexController($table);
            },
            Controller\SearchController::class => function($sm) {
                $table = $sm->get(UserTable::class);
                return new Controller\SearchController($table);
            },
            Controller\UpdateController::class => function($sm) {
                $table = $sm->get(UserTable::class);
                $sesionManager = new SessionManager();
                return new Controller\UpdateController($table, $sesionManager);
            },
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'    => __DIR__ . '/../view/layout/layout.phtml',
            'user/index/index' => __DIR__ . '/../view/user/index/index.phtml',
            'error/404'        => __DIR__ . '/../view/error/404.phtml',
            'error/index'      => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'service_manager' => [
        'factories' => [
            UserTable::class => function($sm) {
                $tableGateway = $sm->get('UserTableGateway');
                $table = new UserTable($tableGateway);
                return $table;
            },
            'UserTableGateway' => function($sm) {
                $dbAdapter = $sm->get('Laminas\Db\Adapter');
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new User());
                return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
            }
        ],
    ],
//    'validators' => [
//        'factories' => [
//            UniqueEmailValidator::class => function ($sm) {
//                $tableGateway = $sm->get('UserTableGateway');
//                $table  = new UserTable($tableGateway);
//                return new UniqueEmailValidator($table);
//            }
//        ]
//    ]
];
