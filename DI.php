<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 13.06.17
 * Time: 12:08
 */

namespace ddmytruk\user;


use ddmytruk\user\abstracts\SignUpFormAbstract;
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
}