<?php

declare(strict_types=1);

namespace Auth;

use Auth\Form\LoginForm;
use Auth\Service\AuthService;
use Laminas\Authentication\Adapter\DbTable\CallbackCheckAdapter as DbTableAuthAdapter;
use Laminas\Router\Http\Literal;
use User\Model\UserTable;

return [
    'router' => [
        'routes' => [
            'login' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/login',
                    'defaults' => [
                        'controller' => Controller\LoginController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'logout' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => Controller\LogoutController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\LoginController::class => function($sm) {
                $authService = $sm->get(AuthService::class);
                $form = $sm->get(LoginForm::class);
                return new Controller\LoginController($authService, $form);
            },
            Controller\LogoutController::class => function($sm) {
                $authService = $sm->get(AuthService::class);
                return new Controller\LogoutController($authService);
            },
        ],
    ],
    'service_manager' => [
        'factories' => [
            AuthService::class => function ($sm) {
                $authAdapter = $sm->get(DbTableAuthAdapter::class);
                $userTable = $sm->get(UserTable::class);
                return new AuthService($authAdapter, $userTable);
            },
            DbTableAuthAdapter::class => function ($sm) {
                $dbAdapter = $sm->get('Laminas\Db\Adapter\Adapter');
                $dbTableAuthAdapter = new DbTableAuthAdapter(
                    $dbAdapter,
                    'users',
                    'email',
                    'password'
                );
                return $dbTableAuthAdapter;
            },
            LoginForm::class => function ($sm) {
                return new LoginForm();
            },
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
            'auth/login/index' => __DIR__ . '/../view/auth/login/index.phtml',
            'auth/logout/index' => __DIR__ . '/../view/auth/logout/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
