<?php

namespace App\Module\User;

use Dore\Core\Foundation\Module;

/**
 * Class UserModule
 * @package App\Module\User
 */
class UserModule extends Module
{
    /**
     * UserModule constructor.
     */
    public function __construct()
    {
        $this->setRoutes(__DIR__ . DS . 'Assets' . DS . 'Config' . DS . 'routes.php');
    }
}
