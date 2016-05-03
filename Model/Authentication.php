<?php

namespace App\Module\User\Model;

use Dore\Core\Foundation\Model;
use Dore\Core\User\Exception\InvalidInputException;
use Dore\Core\User\Exception\IUserException;
use Dore\Core\User\Exception\UserNotFoundException;
use Dore\Core\User\Facade;
use Dore\Core\Validator\Validator;

/**
 * Class Authentication
 * @package App\Module\User\Model
 */
class Authentication extends Model
{

    /**
     * @var string
     */
    protected $nickname;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $captcha;

    /**
     * @var boolean
     */
    protected $active;

    /**
     * @var boolean
     */
    protected $rememberme;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $provider;

    /**
     * @var string
     */
    protected $passwordRepeat;

    /**
     * @var string
     */
    protected $activationCode;

    /**
     * @var string
     */
    protected $resetCode;

    /**
     * @var Facade
     */
    protected $facade;

    /**
     * Authentication constructor.
     *
     * @param Facade    $facade
     * @param Validator $validate
     */
    public function __construct(Facade $facade, Validator $validate)
    {
        parent::__construct($validate);
        $this->facade = $facade;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getResetCode()
    {
        return $this->resetCode;
    }

    public function getActivationCode()
    {
        return $this->activationCode;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function getNickname()
    {
        return $this->nickname;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setProvider($provider)
    {
        $this->provider = $provider;
    }

    public function setActive($active)
    {
        $this->active = (bool)$active;
    }

    public function setRememberme($rememberme)
    {
        $this->rememberme = (bool)$rememberme;
    }

    public function setActivationCode($value)
    {
        $this->activationCode = $value;
    }

    public function setCaptcha($captcha)
    {
        $this->captcha = $captcha;
    }

    public function setPasswordRepeat($passwordRepeat)
    {
        $this->passwordRepeat = $passwordRepeat;
    }

    public function setResetCode($value)
    {
        $this->resetCode = $value;
    }


    public function scenarios()
    {
        return [
            'login' => [(filter_var($this->nickname, FILTER_VALIDATE_EMAIL)) ? 'email' : 'nickname', 'password'],
            'register' => ['uniqueNickname', 'uniqueEmail', 'password', 'captcha'],
            'ulogin' => ['id', 'provider'],
            'reset' => ['email', 'captcha'],
            'activate' => ['activationCode'],
            'uregister' => ['uniqueNickname'],
            'link' => ['resetCode'],
            'password' => ['password', 'passwordRepeat']
        ];
    }

    public function rules()
    {
        return [
            'id' => ['id', 'required|int|min(1, number)'],
//            'id' => ['id', 'required|min(1, number)'],
            'nickname' => ['nickname', 'required|notNumeric|latinNumeric|min(3)|max(20)'],
            'email' => ['email', 'required|email|max(64)'],
            'provider' => ['provider', 'required'],
            'uniqueNickname' => ['nickname', 'required|notNumeric|latinNumeric|min(3)|max(20)|uniqueLogin'],
            'uniqueEmail' => ['email', 'required|email|max(64)|uniqueEmail'],
            'password' => ['password', 'required|min(8)'],
            'captcha' => ['captcha', 'required|captcha'],
            'passwordRepeat' => ['passwordRepeat', 'required|matches(password)'],
            'resetCode' => ['resetCode', 'required|min(40)|max(40)|latinNumeric|resetCode(86400)'],
            'activationCode' => ['activationCode', 'required|min(40)|max(40)|latinNumeric'],
        ];
    }

    /**
     * @throws IUserException
     */
    public function login()
    {
        $userInstance = $this->facade->findByLogin($this->nickname);
        $this->facade->login()->authenticate($userInstance, $this->password, $this->rememberme);
    }

    /**
     * @return int
     */
    public function registration()
    {
        $userInstance = $this->facade->addUser();
        $uid = $userInstance->add($this->nickname, $this->password, $this->email, $this->active);
        $this->setActivationCode($userInstance->getCodeActivation());
        return $uid;
    }

    /**
     * Authorization by social networks
     *
     * @param bool $closedRegistration
     *
     * @throws IUserException
     * @throws InvalidInputException
     */
    public function uLogin($closedRegistration = false)
    {
        try {
            $userInstance = $this->facade->findBySocialIdAndProvider($this->id, $this->provider);
        } catch (UserNotFoundException $e) {
            if ($closedRegistration) {
                throw new InvalidInputException('registration_closed');
            }
            $uid = $this->facade->addUser()->addSocial($this->id, $this->provider);
            $userInstance = $this->facade->findById($uid);
        }
        $this->facade->login()->authenticate($userInstance, null, true);
    }

    /**
     * @throws IUserException
     */
    public function resetPassword()
    {
        $userInstance = $this->facade->findByLogin($this->email);
        $this->setResetCode($this->facade->login()->resetPassword($userInstance));

    }

    /**
     * @throws IUserException
     */
    public function changePassword()
    {
        $userInstance = $this->facade->findByResetCode($this->resetCode);
        $this->facade->login()->changePassword($userInstance, $this->password);
    }

    /**
     * Enabling user registration
     * @throws IUserException
     */
    public function activation()
    {
        $userInstance = $this->facade->findByActivationCode($this->activationCode);
        $this->facade->login()->activation($userInstance);
    }

    /**
     * @param int $id User ID
     */
    public function delete($id)
    {
        $this->facade->delete($id);
    }
}
