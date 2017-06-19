<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 15.06.17
 * Time: 17:27
 */

namespace ddmytruk\user;

use ddmytruk\mailer\abstracts\MailerAbstract;
use ddmytruk\user\abstracts\UserAbstract;
use ddmytruk\user\models\orm\Token;

class Mailer extends MailerAbstract
{
    /** @var string */
    public $viewPath = '@ddmytruk/user/views/mail';

    /** @var string */
    protected $welcomeSubject;

    /** @var string */
    protected $confirmationSubject;

    /**
     * @return string
     */
    public function getWelcomeSubject()
    {
        if ($this->welcomeSubject == null) {
            $this->setWelcomeSubject(\Yii::t('user', 'Welcome to {0}', \Yii::$app->name));
        }

        return $this->welcomeSubject;
    }

    /**
     * @param string $welcomeSubject
     */
    public function setWelcomeSubject($welcomeSubject)
    {
        $this->welcomeSubject = $welcomeSubject;
    }

    /**
     * Sends an email to a user with confirmation link.
     *
     * @param UserAbstract  $user
     * @param Token $token
     *
     * @return bool
     */
    public function sendConfirmationMessage(UserAbstract $user, Token $token)
    {
        return $this->sendMessage(
            $user->email,
            $this->getConfirmationSubject(),
            'confirmation',
            ['user' => $user, 'token' => $token]
        );
    }

    /**
     * @return string
     */
    public function getConfirmationSubject()
    {
        if ($this->confirmationSubject == null) {
            $this->setConfirmationSubject(\Yii::t('user', 'Confirm account on {0}', \Yii::$app->name));
        }

        return $this->confirmationSubject;
    }

    /**
     * @param string $confirmationSubject
     */
    public function setConfirmationSubject($confirmationSubject)
    {
        $this->confirmationSubject = $confirmationSubject;
    }

    /**
     * Sends an email to a user after sign up.
     *
     * @param UserAbstract  $user
     * @param Token $token
     * @param bool  $showPassword
     *
     * @return bool
     */
    public function sendWelcomeMessage(UserAbstract $user, Token $token = null, $showPassword = false)
    {
        return $this->sendMessage(
            $user->email,
            $this->getWelcomeSubject(),
            'welcome', [
                'user' => $user,
                'token' => $token,
                'showPassword' => $showPassword
            ]
        );
    }
}