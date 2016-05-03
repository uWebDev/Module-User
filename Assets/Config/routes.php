<?php

use App\Module\User\Controller\IndexController;

return [
    [
        'GET|POST',
        '/login/',
        [
            'controller' => IndexController::class,
            'action'     => 'Login',
            function ($c) {
                if (!$c['user']->isGuest()) {
                    $c->redirect(
                            $c['router']->generate('user')
                    );
                }
            }
        ],
        'login'
    ],
    [
        'GET|POST',
        '/logout/',
        [
            'controller' => IndexController::class,
            'action'     => 'Logout',
            function ($c) {
                if ($c['user']->isGuest()) {
                    $c->redirect(
                                    $c['router']->generate('login')
                    );
                }
            }
        ],
        'logout'
    ],
    [
        'GET|POST',
        '/register/',
        [
            'controller' => IndexController::class,
            'action'     => 'Register',
            function ($c) {
                if (!$c['user']->isGuest()) {
                    $c->redirect(
                                    $c['router']->generate('home')
                    );
                }
            }
        ],
        'register'
    ],
    [
        'GET|POST',
        '/reset/',
        [
            'controller' => IndexController::class,
            'action'     => 'ResetPassword',
            function ($c) {
                if (!$c['user']->isGuest()) {
                    $c->redirect(
                                    $c['router']->generate('home')
                    );
                }
            }
        ],
        'resetPassword'
    ],
    [
        'GET',
        '/activation/[a:hash]/',
        [
            'controller' => IndexController::class,
            'action'     => 'Activation',
            function ($c) {
                if (!$c['user']->isGuest()) {
                    $c->redirect(
                                    $c['router']->generate('home')
                    );
                }
            }
        ],
        'activation'
    ],
    [
        'GET|POST',
        '/verification/[a:hash]/',
        [
            'controller' => IndexController::class,
            'action'     => 'ChangePassword',
            function ($c) {
                if (!$c['user']->isGuest()) {
                    $c->redirect($c['router']->generate('home'));
                }
            }
        ],
        'changePassword'
    ],
    [
        'GET',
        '/auth/[a:site]/',
        [
            'controller' => IndexController::class,
            'action'     => 'ULogin',
            function ($c) {
                if (!$c['user']->isGuest()) {
                    $c->redirect($c['router']->generate('user'));
                }
            }
        ],
        'oauth'
    ],
    [
        'GET|POST',
        '/auth/register/add/',
        [
            'controller' => IndexController::class,
            'action'     => 'URegister',
            function ($c) {
                if ($c['user']->isGuest()) {
                    $c->redirect(
                                    $c['router']->generate('login')
                    );
                } elseif (!is_null($c['user']->get()->nickname)) {
                    $c->redirect(
                                    $c['router']->generate('home')
                    );
                }
            }
        ],
        'uregister'
    ],
    [
        'GET|POST',
        '/user/',
        [
            'controller' => IndexController::class,
            'action'     => 'Index',
            function ($c) {
                if ($c['user']->isGuest()) {
                    $c->redirect(
                                    $c['router']->generate('login')
                    );
                }
            }
        ],
        'user'
    ],
    [
        'GET',
        '/online/',
        [
            'controller' => IndexController::class,
            'action'     => 'Online',
            function ($c) {
                if ($c['user']->isGuest()) {
                    $c->redirect(
                                    $c['router']->generate('login')
                    );
                }
            }
        ],
        'online'
    ],
];
