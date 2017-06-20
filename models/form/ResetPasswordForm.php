<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 20.06.17
 * Time: 14:16
 */

namespace ddmytruk\user\models\form;


use ddmytruk\user\abstracts\RecoveryFormAbstract;
use ddmytruk\user\models\orm\Token;

class ResetPasswordForm extends RecoveryFormAbstract
{
    /** @var Token */
    public $token;
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email'    => \Yii::t('user', 'Email'),
            'password' => \Yii::t('user', 'Password'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'passwordRequired' => ['password', 'required'],
            'passwordLength' => ['password', 'string', 'max' => 72, 'min' => 6],
        ];
    }

    /**
     * Registers a new user account.
     *
     * @return bool
     */
    public function perform() {

        if (!$this->validate() || $this->token->user === null) {
            return false;
        }

        if ($this->token->user->resetPassword($this->password)) {
            \Yii::$app->session->setFlash('success', \Yii::t('user', 'Your password has been changed successfully.'));
            $this->token->delete();
        } else {
            return false;
        }

        return true;

    }
}