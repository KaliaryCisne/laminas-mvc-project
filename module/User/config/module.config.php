<?php

declare(strict_types=1);

namespace User;

use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;
use User\Model\User;
use User\Model\UserTable;

return [
    'router' => [
        'routes' => [
            'user' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/user[/:controller[/:action]]',
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
            Controller\CreateController::class => InvokableFactory::class,
            Controller\DeleteController::class => InvokableFactory::class,
            Controller\IndexController::class  => function($sm) {
                $table = $sm->get(UserTable::class);
                return new Controller\IndexController($table);
            },
            Controller\SearchController::class => InvokableFactory::class,
            Controller\UpdateController::class => InvokableFactory::class,
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
                $table  = new UserTable($tableGateway);
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
];
