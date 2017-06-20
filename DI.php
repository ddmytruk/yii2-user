<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 13.06.17
 * Time: 12:08
 */

namespace ddmytruk\user;


use ddmytruk\user\abstracts\SignInFormAbstract;
use ddmytruk\user\abstracts\SignUpFormAbstract;
use ddmytruk\user\abstracts\UserAbstract;
use ddmytruk\user\models\form\RecoveryForm;
use ddmytruk\user\models\form\ResendForm;
use ddmytruk\user\models\form\ResetPasswordForm;
use yii\base\Object;

class DI extends Object
{
    /**
     * @var SignUpFormAbstract
     */
    protected $signUpForm;

    /**
     * @var SignInFormAbstract
     */
    protected $signInForm;

    /**
     * @var  RecoveryForm
     */
    protected $recoveryForm;

    /**
     * @var  ResendForm
     */
    protected $resendForm;

    /**
     * @var  ResetPasswordForm
     */
    protected $resetPasswordForm;

    /**
     * @return SignUpFormAbstract
     */
    public function getSignUpForm()
    {
        return $this->signUpForm;
    }

    /**
     * @return SignInFormAbstract
     */
    public function getSignInForm()
    {
        return $this->signInForm;
    }

    /**
     * @param SignInFormAbstract $sugnUpForm
     */
    public function setSignInForm(SignInFormAbstract $signInForm)
    {
        $this->signInForm = $signInForm;
    }

    /**
     * @param SignUpFormAbstract $sugnUpForm
     */
    public function setSignUpForm(SignUpFormAbstract $signUpForm)
    {
        $this->signUpForm = $signUpForm;
    }

    /**
     * @param RecoveryForm $recoveryForm
     */
    public function setRecoveryForm(RecoveryForm $recoveryForm)
    {
        $this->recoveryForm = $recoveryForm;
    }

    /**
     * @param ResendForm $resendForm
     */
    public function setResendForm(ResendForm $resendForm)
    {
        $this->resendForm = $resendForm;
    }

    /**
     * @param ResetPasswordForm $resetPasswordForm
     */
    public function setResetPasswordForm(ResetPasswordForm $resetPasswordForm)
    {
        $this->resetPasswordForm = $resetPasswordForm;
    }

    /**
     * @return RecoveryForm
     */
    public function getRecoveryForm()
    {
        return $this->recoveryForm;
    }

    /**
     * @return ResendForm
     */
    public function getResendForm()
    {
        return $this->resendForm;
    }

    /**
     * @return ResetPasswordForm
     */
    public function getResetPasswordForm()
    {
        return $this->resetPasswordForm;
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