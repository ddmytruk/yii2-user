<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 13.06.17
 * Time: 16:12
 */

namespace ddmytruk\user\traits;

use ddmytruk\user\abstracts\RecoveryFormAbstract;
use ddmytruk\user\abstracts\UserAbstract;
use ddmytruk\user\events\ConnectEvent;
use ddmytruk\user\events\UserEvent;
use ddmytruk\user\events\AuthEvent;
use ddmytruk\user\models\orm\SocialAccount;
use ddmytruk\user\models\orm\Token;
use ddmytruk\user\events\ResetPasswordEvent;
use Yii;
use yii\base\Model;
use ddmytruk\user\events\FormEvent;
use yii\web\IdentityInterface;
use yii\authclient\ClientInterface;

trait EventTrait
{
    /**
     * @param  Model     $form
     * @return object the created object (FormEvent or InvalidConfigException)
     * @throws \yii\base\InvalidConfigException
     */
    protected function getFormEvent(Model $form)
    {
        return Yii::createObject(['class' => FormEvent::className(), 'form' => $form]);
    }

    /**
     * @param  UserAbstract|IdentityInterface $user
     * @return object the created object (UserEvent or InvalidConfigException)
     * @throws \yii\base\InvalidConfigException
     */
    protected function getUserEvent(UserAbstract $user)
    {
        return \Yii::createObject(['class' => UserEvent::className(), 'user' => $user]);
    }

    /**
     * @param  SocialAccount         $account
     * @param  ClientInterface $client
     * @return object the created object (AuthEvent or InvalidConfigException)
     * @throws \yii\base\InvalidConfigException
     */
    protected function getAuthEvent(SocialAccount $account, ClientInterface $client)
    {
        return \Yii::createObject(['class' => AuthEvent::className(), 'account' => $account, 'client' => $client]);
    }

    /**
     * @param  SocialAccount      $account
     * @param  UserAbstract         $user
     * @return object the created object (ConnectEvent or InvalidConfigException)
     * @throws \yii\base\InvalidConfigException
     */
    protected function getConnectEvent(SocialAccount $account, UserAbstract $user)
    {
        return \Yii::createObject(['class' => ConnectEvent::className(), 'account' => $account, 'user' => $user]);
    }

    /**
     * @param  Token        $token
     * @param  RecoveryFormAbstract $form
     * @return object the created object (ResetPasswordEvent or InvalidConfigException)
     * @throws \yii\base\InvalidConfigException
     */
    protected function getResetPasswordEvent(Token $token = null, RecoveryFormAbstract $form = null)
    {
        return \Yii::createObject(['class' => ResetPasswordEvent::className(), 'token' => $token, 'form' => $form]);
    }
}