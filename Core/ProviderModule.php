<?php

namespace App\Module\User\Core;

use Dore\Core\Foundation\App;
use App\Module\User\Model\Online;
use App\Module\User\Model\Social\Adapter\AbstractAdapter;
use App\Module\User\Model\UserMail;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use App\Module\User\Model\Authentication;
use App\Module\User\Model\Social\Adapter\Vk;

/**
 * Class ProviderModule
 * @package App\Module\User\Core
 */
class ProviderModule implements ServiceProviderInterface
{

    public function register(Container $container)
    {

        $container['validateModule'] = function ($c) {
            return new ValidatorModule($c['db'], $c['session']);
        };

        $container['userMail'] = function ($c) {
            $mail = new UserMail($c['router'], $c['mail'], $c['lng']);
            $mail->setHometitle(App::config()->get('system.default.hometitle'));
            $mail->setHost($c['request']->getSchemeAndHttpHost());
            return $mail;
        };

        $container['online'] = function ($c) {
            return new Online($c['db']);
        };

        $container['authen'] = function ($c) {
            return new Authentication($c['user'], $c['validateModule']);
        };

        $container['component'] = function ($c) {
            return [
                'vk' => [
                    'authorize' => [
                        'id' => '',
                        'secret' => '',
                        'redirect' => $c['request']->getSchemeAndHttpHost() . '/auth/vk/',
                        'scope' => 'sex', //notify,email,bdate,sex,photo_max_orig
                    ],
                    'version' => '5.44',

                ],
                'mailru' => [
                    'authorize' => [
                        'id' => '',
                        'secret' => '',
                        'redirect' => $c['request']->getSchemeAndHttpHost() . '/auth/mailru/',
                    ],
                    'format' => 'json',
                ],
                'yandex' => [
                    'authorize' => [
                        'id' => '',
                        'secret' => '',
                        'redirect' => $c['request']->getSchemeAndHttpHost() . '/auth/yandex/',
                        'display' => 'popup',
                    ],
                    'format' => 'json',
                ],
                'okru' => [
                    'authorize' => [
                        'id' => '',
                        'secret' => '
                        ',
                        'redirect' => $c['request']->getSchemeAndHttpHost() . '/auth/okru/',
                    ],
                    'appkey' => '',
                    'format' => 'json',
                ],
                'facebook' => [
                    'authorize' => [
                        'id' => '',
                        'secret' => '',
                        'redirect' => $c['request']->getSchemeAndHttpHost() . '/auth/facebook/',
                        'scope' => 'email',
                    ],
                    'version' => '2.5',
                ],
                'google' => [
                    'authorize' => [
                        'id' => '',
                        'secret' => '',
                        'redirect' => $c['request']->getSchemeAndHttpHost() . '/auth/google/',
                        'scope' => 'profile',
                    ],
                ],
            ];
        };
    }

}
