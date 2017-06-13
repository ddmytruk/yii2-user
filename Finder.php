<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 12.06.17
 * Time: 17:50
 */

namespace ddmytruk\user;


use ddmytruk\user\abstracts\SignUpAbstract;
use yii\base\Object;

class Finder extends Object
{
    /**
     * @var SignUpAbstract
     */
    protected $signUpForm;

    /**
     * @return SignUpAbstract
     */
    public function getSignUpForm()
    {
        return $this->signUpForm;
    }

    /**
     * @param SignUpAbstract $userQuery
     */
    public function setSignUpForm(SignUpAbstract $signUpForm)
    {
        $this->signUpForm = $signUpForm;
    }

}