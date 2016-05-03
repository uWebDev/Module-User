<?php

namespace App\Module\User\Model;


use Dore\Core\Http\Request;
use Dore\Core\Language\Language;
use Dore\Core\Mail\Mail;

/**
 * Class UserMail
 * @package App\Module\User\Model
 */
class UserMail
{
    /** @var  \AltoRouter */
    protected $router;

    /** @var Mail */
    protected $mail;

    /** @var Language */
    protected $lng;

    /** @var string */
    protected $hometitle;

    /** @var string */
    protected $host;

    /**
     * UserMail constructor.
     *
     * @param \AltoRouter $router
     * @param Mail        $mail
     * @param Language    $lng
     */
    public function __construct(\AltoRouter $router, Mail $mail, Language $lng)
    {
        $this->router = $router;
        $this->mail = $mail;
        $this->lng = $lng;
    }

    public function setHometitle($hometitle)
    {
        $this->hometitle = $hometitle;
    }

    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @param $email
     * @param $code
     *
     * @return bool
     */
    public function sendRegistrationEmail($email, $code)
    {
        $subject = "Активация аккаунта для сайта " . $this->hometitle;
        $body = 'Пожалуйста, нажмите на эту ссылку, чтобы активировать свой аккаунт: ';
        $body .= $this->host . $this->router->generate('activation', ['hash' => urlencode($code)]);
        return $this->send($email, $subject, $body);
    }

    /**
     * @param $email
     * @param $code
     *
     * @return bool
     */
    public function sendChangePasswordEmail($email, $code)
    {
        $subject = "Смена пароля для сайта " . $this->hometitle;
        $body = 'Пожалуйста, нажмите на эту ссылку для подтверждения смены пароля: ';
        $body .= $this->host . $this->router->generate('changePassword', ['hash' => urlencode($code)]);
        return $this->send($email, $subject, $body);
    }

    /**
     * @param $email
     * @param $subject
     * @param $body
     *
     * @return bool
     */
    protected function send($email, $subject, $body)
    {
        return $this->mail->sendMail($email, $subject, $body);
    }

}