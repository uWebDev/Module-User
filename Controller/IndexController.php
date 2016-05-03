<?php

namespace App\Module\User\Controller;

use App\Module\User\Core\ControllerModule;
use App\Module\User\Model\OAuth\OAuth;
use Dore\Core\Foundation\App;
use Dore\Core\Exception\SemanticExceptions\InvalidArgumentException;
use Dore\Core\Traits\Security;
use App\Module\User\Model\Social\SocialAuther;

//use Dore\Core\Exception\SemanticExceptions\InvalidArgumentException;
//use Dore\Core\Exception\EnvironmentExceptions\NotExistsException;
use Dore\Core\User\Exception\IUserException;

/**
 * Class IndexController
 * @package App\Module\User\Controller
 */
class IndexController extends ControllerModule
{
    use Security;

    /**
     * The main user page
     * @return string
     */
    public function actionIndex()
    {
        return $this->container['response']->setContent(
            $this->container['view']->render('module::user',
                [
                    'username' => $this->container['user']->get()->nickname
                ])
        );
    }

    /**
     *  User authorization
     */
    public function actionLogin()
    {
// TODO       var_dump(PHP_INT_SIZE); если = 4 то 32bit OS
        if ($this->container['request']->isPost()) {
            $auth = $this->container['authen'];
            $auth->scenario = 'login';
            $auth->nickname = $this->checkin($this->container['request']->post('login'));
            $auth->password = $this->checkin($this->container['request']->post('password', false));
            $auth->rememberme = $this->container['request']->post('rememberme', false, true);

            try {
                if ($auth->validate()) {
                    $auth->login();

                    $this->container->redirect(
                        $this->container['router']->generate(App::config()->get('module.redirect.user'))
                    );
                    return;
                }
                $this->error = $auth->errors();
                $this->error['error'] = 'error_there_are_errors';
            } catch (IUserException $e) {
                $this->error['error'] = $e->getMessage();
            }
//            catch (NotExistsException $e) {
//                $this->error['error'] = 'error_authorization';
//            }
            $this->container['session']->setFlash('error', $this->error);

            $this->container->redirect(
                $this->container['router']->generate(App::config()->get('module.redirect.guest'))
            );
            return;
        }

        return $this->container['response']->setContent(
            $this->container['view']->render('module::login',
                [
                    'closed' => App::config()->get('module.closedRegistration'),
                    'socialUrl' => $this->container['socialUrl'],
                    'message' => $this->container['session']->getFlash('message'),
                    'error' => $this->container['session']->getFlash('error'),
                ])
        );
    }

    /**
     * Authorization through third-party services
     */
    public function actionULogin($param)
    {
        try {
            $provider = $param['site'];
            $error = $this->container['request']->get('error');
            $errorCode = $this->container['request']->get('error_code');
            if (empty($error)
                && empty($errorCode)
                && array_key_exists($provider, $this->container['component'])
            ) {
                $oauth = new OAuth($provider, $this->container['component'][$provider]);
                // аутентификация и вывод данных пользователя
                if ($oauth->authenticate()) {
                    $auth = $this->container['authen'];
                    $auth->scenario = 'ulogin';
                    $auth->id = $oauth->getId();
                    $auth->provider = $oauth->getNameProvider();
                    if ($auth->validate()) {
                        $auth->uLogin(App::config()->get('module.closedRegistration'));

                        $this->container->redirect($this->container['router']->generate(
                            App::config()->get('module.redirect.user')
                        ));
                        return;
                    }
                }
                $this->error['error'] = 'error_authorization_social';
            }
        } catch (IUserException $e) {
            $this->error['error'] = $e->getMessage();
        } catch (InvalidArgumentException $e) {
            $this->error['error'] = 'error_authorization_social';
//            $this->error['error'] = $e->getMessage();
        }
        $this->container['session']->setFlash('error', $this->error);

        $this->container->redirect($this->container['router']->generate(
            App::config()->get('module.redirect.guest')
        ));
        return;
    }

    /**
     * The logout user page
     * @return string
     */
    public function actionLogout()
    {
        if ($this->container['request']->isPost()) {
            $token = $this->container['request']->post('form_token', false);
//            $clear = $this->container['request']->post('clear');
            if ($this->container['token']->check($token)) {
                $this->container['user']->logout(true);

                $this->container->redirect($this->container['router']->generate('home'));
                return;
            }
        }

        return $this->container['response']->setContent(
            $this->container['view']->render('module::logout',
                [
                    'token' => $this->container['token']->get(),
                    'siteTitle' => App::config()->get('system.default.hometitle')
                ])
        );
    }

    /**
     * User registration third-party services
     */
    public function actionURegister()
    {
        if ($this->container['request']->isPost()) {
            $token = $this->container['request']->post('form_token', false);
            if ($this->container['token']->check($token)) {
                $auth = $this->container['authen'];
                $auth->scenario = 'uregister';
                $auth->nickname = $this->checkin($this->container['request']->post('nickname'));
                try {
                    if ($auth->validate()) {
                        $this->container['user']->get()->offsetSet('nickname', $auth->nickname);
                        $this->container['user']->get()->save();

                        $this->container->redirect(
                            $this->container['router']->generate(App::config()->get('module.redirect.user'))
                        );
                        return;
                    } else {
                        $this->error = $auth->errors();
                        $this->error['error'] = 'error_there_are_errors';
                    }
                } catch (IUserException $e) {
                    $this->error['error'] = $e->getMessage();
                }

                $this->container['session']->setFlash('formData',
                    [
                        'nickname' => $auth->nickname
                    ]);
                $this->container['session']->setFlash('error', $this->error);

                $this->container->redirect($this->container['router']->generate('uregister'));
                return;
            }
        }

        return $this->container['response']->setContent(
            $this->container['view']->render('module::register_social',
                [
                    'token' => $this->container['token']->get(),
                    'formData' => $this->container['session']->getFlash('formData'),
                    'error' => $this->container['session']->getFlash('error')
                ]
            ));
    }

    /**
     * User registration
     */
    public function actionRegister()
    {
        if ($this->container['request']->isPost()) {
            $auth = $this->container['authen'];
            $auth->scenario = 'register';
            $auth->nickname = $this->checkin($this->container['request']->post('login'));
            $auth->email = $this->checkin($this->container['request']->post('email'));
            $auth->password = $this->checkin($this->container['request']->post('password', false));
            $auth->captcha = $this->checkin($this->container['request']->post('captcha'));
            $auth->active = App::config()->get('module.activationByMail');
            $auth->rememberme = true;
            try {
                if ($auth->validate()) {
                    $uid = $auth->registration($this->container['userMail']);
                    if ($auth->active) {
                        if ($this->container['userMail']->sendRegistrationEmail($auth->email, $auth->activationCode)) {
                            $this->container['session']->setFlash('message',
                                ['message' => 'message_created_successfully_email']
                            );
                            $this->container->redirect(
                                $this->container['router']->generate(App::config()->get('module.redirect.user'))
                            );
                            return;
                        } else {
                            $auth->delete($uid);
                            $this->error['error'] = 'error_account_removed';
                        }
                    } else {
                        $auth->login();
                        $this->container->redirect(
                            $this->container['router']->generate(App::config()->get('module.redirect.user'))
                        );
                        return;
                    }
                } else {
                    $this->error = $auth->errors();
                    $this->error['error'] = 'error_there_are_errors';
                }
            } catch (IUserException $e) {
                $this->error['error'] = $e->getMessage();
            }
//            catch (NotExistsException $e) {
//                $this->error['error'] = 'error_сreate_failed';
//            }
            $this->container['session']->setFlash('formData',
                ['login' => $auth->nickname, 'email' => $auth->email]
            );
            $this->container['session']->setFlash('message', $this->message);
            $this->container['session']->setFlash('error', $this->error);

            $this->container->redirect($this->container['router']->generate('register'));
            return;
        }

        $error = $this->container['session']->getFlash('error');

        return $this->container['response']->setContent(
            $this->container['view']->render('module::register',
                [
                    'formData' => $this->container['session']->getFlash('formData'),
                    'closed' => App::config()->get('module.closedRegistration'),
                    'message' => $this->container['session']->getFlash('message'),
                    'error' => $error,
                    'captcha' => [
                        'config' => App::config()->get('captcha.default'),
                        'error' => $error,
                    ],
                ]
            )
        );
    }

    /**
     * Request password reset
     */
    public function actionResetPassword()
    {
        if ($this->container['request']->isPost()) {
            $auth = $this->container['authen'];
            $auth->scenario = 'reset';
            $auth->email = $this->checkin($this->container['request']->post('email'));
            $auth->captcha = $this->checkin($this->container['request']->post('captcha'));

            try {
                if ($auth->validate()) {
                    $auth->resetPassword();
                    if ($this->container['userMail']->sendChangePasswordEmail($auth->email, $auth->resetCode)) {
                        $this->container['session']->setFlash('message', ['message' => 'message_email_sent']);

                        $this->container->redirect($this->container['router']->generate('login'));
                        return;
                    } else {
                        $this->error['error'] = 'error_send_failed';
//                        $this->error['error'] = $this->container['mail']->error();
                    }
                } else {
                    $this->error = $auth->errors();
                    $this->error['error'] = 'error_there_are_errors';
                }
            } catch (IUserException $e) {
                $this->error['error'] = $e->getMessage();
            }
//            catch (NotExistsException $e) {
//                $this->error['error'] = 'error_reset';
//            }

            $this->container['session']->setFlash('error', $this->error);

            $this->container->redirect($this->container['router']->generate('resetPassword'));
            return;
        }

        $error = $this->container['session']->getFlash('error');

        return $this->container['response']->setContent(
            $this->container['view']->render('module::reset_password',
                [
                    'error' => $error,
                    'message' => $this->container['session']->getFlash('message'),
                    'captcha' => [
                        'config' => App::config()->get('captcha.default'),
                        'error' => $error
                    ],
                ])
        );
    }

    /**
     * Change Password
     *
     * @param $param
     */
    public function actionChangePassword($param)
    {
        try {
            $auth = $this->container['authen'];
            $auth->scenario = 'link';
            $auth->resetCode = $this->checkin($param['hash']);

            if ($auth->validate()) {
                if ($this->container['request']->isPost()) {
                    $auth->scenario = 'password';
                    $auth->password = $this->checkin(
                        $this->container['request']->post('password', false)
                    );
                    $auth->passwordRepeat = $this->checkin(
                        $this->container['request']->post('password_repeat', false)
                    );

                    if ($auth->validate()) {
                        $auth->changePassword();
                        $this->container['session']->setFlash('message',
                            ['message' => 'message_password_successfully']
                        );
                        $this->container->redirect($this->container['router']->generate('login'));
                        return;
                    }
                    $this->error = $auth->errors();
                    $this->error['error'] = 'error_there_are_errors';
                    $this->container['session']->setFlash('error', $this->error);
                    $this->container->redirect(
                        $this->container['router']->generate('changePassword',
                            [
                                'hash' => $auth->resetCode
                            ])
                    );
                    return;
                }

                // выводим форму смены пароля
                return $this->container['response']->setContent(
                    $this->container['view']->render('module::change_password',
                        [
                            'error' => $this->container['session']->getFlash('error'),
                        ])
                );
            }
            $this->error['error'] = 'error_link_password_not_valid';
        } catch (IUserException $e) {
            $this->error['error'] = 'error_password_change_failed';
        }
        $this->container['session']->setFlash('error', $this->error);

        $this->container->redirect($this->container['router']->generate('login'));
        return;
    }

    /**
     * Activation of registration by e-mail
     *
     * @param $params
     */
    public function actionActivation($params)
    {
        try {
            $auth = $this->container['authen'];
            $auth->scenario = 'activate';
            $auth->activationCode = $this->checkin($params['hash']);

            if ($auth->validate()) {
                $auth->activation();
                $this->message['message'] = 'message_activation_successfully';
            } else {
                $this->error['error'] = 'error_link_account_not_valid';
            }
        } catch (IUserException $e) {
            $this->error['error'] = $e->getMessage();
        }

        $this->container['session']->setFlash('message', $this->message);
        $this->container['session']->setFlash('error', $this->error);

        $this->container->redirect($this->container['router']->generate('login'));
        return;
    }

    /**
     * The users online page
     * @return mixed
     */
    public function actionOnline()
    {
        return $this->container['response']->setContent(
            $this->container['view']->render('module::users_online',
                [
                    'list' => $this->container['online']->listOnlineUsers()
                ])
        );
    }

}
