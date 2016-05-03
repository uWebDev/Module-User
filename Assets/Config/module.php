<?php

return [
        'closedRegistration' => false, // Регистрация (true - закрыта / false -открыта)
        'activationByMail'   => true, // Аутивация по email (false - без подтверждения  / true - c подтверждением)
        'redirect'           => [ // Настройки переадресации для модуля
            'user'  => 'user', // Для авторизованных
            'guest' => 'login', //Для не авторизованных
        ]
];
