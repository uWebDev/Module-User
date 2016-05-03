<?php

namespace App\Module\User\Core;

use Dore\Core\Validator\Validator;
use Dore\Core\Foundation\App;

/**
 * Class ValidatorModule
 * @package App\Module\User\Core
 */
class ValidatorModule extends Validator
{

    /** @var \PDO */
    protected $db;
    protected $session;

    public function __construct(\PDO $db, $session)
    {
        parent::__construct();

        $this->db = $db;
        $this->session = $session;

        //Добавление сообщений правил оптом
        $this->addRuleMessages([
            'uniqueLogin' => 'error_username_busy',
            'uniqueEmail' => 'error_email_registered',
            'captcha' => 'error_incorrect_data',
            'resetCode' => 'error_resetCode'
        ]);

        //
        $this->addFieldMessages([
            'password_repeat' => [
                'matches' => 'error_passwords_do_not_match'
            ]
        ]);
    }

    /**
     * @param $value
     * @param $input
     * @param $args array Time through which the rotten reset code
     *
     * @return bool
     */
    public function validate_resetCode($value, $input, $args)
    {
        $stmt = $this->db->prepare("SELECT passwordResetTimestamp FROM `users` WHERE `passwordResetHash` = :code AND `provider` = 'native' LIMIT 1");
        $stmt->bindParam(':code', $value, \PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch();
        return (false !== $result && is_array($result) && $result['passwordResetTimestamp'] > (time() - $args[0]));
    }

    /**
     * @param $value
     * @param $input
     * @param $args
     *
     * @return bool
     */
    public function validate_captcha($value, $input, $args)
    {
        $name = App::config()->get('captcha.default.name');
        if ($this->session->has($name)
            && strtoupper($value) === strtoupper($this->session->get($name))
        ) {
            $this->session->remove($name);
            return true;
        }
        return false;
    }

    /**
     * Check whether another user is busy Niknaym
     *
     * @param $value
     * @param $input
     * @param $args
     *
     * @return bool
     */
    public function validate_uniqueLogin($value, $input, $args)
    {
        $sql = 'SELECT COUNT(*) AS count FROM users WHERE nickname= ?';
        $STH = $this->db->prepare($sql);
        $STH->execute(array($value));
        return !(bool)$STH->fetchObject()->count; // Имя пользователя существует, так вернуться false.
    }

    /**
     * Check whether another user is busy Email
     *
     * @param $value
     * @param $input
     * @param $args
     *
     * @return bool
     */
    public function validate_uniqueEmail($value, $input, $args)
    {
        $sql = 'SELECT COUNT(*) AS count FROM users WHERE email= ?';
        $STH = $this->db->prepare($sql);
        $STH->execute(array($value));
        return !(bool)$STH->fetchObject()->count; // Имя пользователя существует, так вернуться false.
    }

}
