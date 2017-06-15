<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 13.06.17
 * Time: 12:08
 */

namespace ddmytruk\user;


use ddmytruk\user\abstracts\SignUpFormAbstract;
use ddmytruk\user\abstracts\UserAbstract;
use yii\base\Object;

class DI extends Object
{
    /**
     * @var SignUpFormAbstract
     */
    protected $signUpForm;

    /**
     * @return SignUpFormAbstract
     */
    public function getSignUpForm()
    {
        return $this->signUpForm;
    }

    /**
     * @param SignUpFormAbstract $sugnUpForm
     */
    public function setSignUpForm(SignUpFormAbstract $signUpForm)
    {
        $this->signUpForm = $signUpForm;
    }

    /**
     * @var UserAbstract
     */
    protected $user;

    /**
     * @return UserAbstract
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param UserAbstract $sugnUpForm
     */
    public function setUser(UserAbstract $user)
    {
        $this->user = $user;
    }

}