<?php

namespace App\Module\User\Core;

use App\Core\Controller;

/**
 * Class ControllerModule
 * @package App\Module\User\Core
 */
class ControllerModule extends Controller
{

    public function before()
    {
        parent::before();
        $this->container->register(new ProviderModule());
    }

}
