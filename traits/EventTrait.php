<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 13.06.17
 * Time: 16:12
 */

namespace ddmytruk\user\traits;

use ddmytruk\user\abstracts\UserAbstract;
use ddmytruk\user\events\UserEvent;
use Yii;
use yii\base\Model;
use ddmytruk\user\events\FormEvent;
use yii\web\IdentityInterface;

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
}