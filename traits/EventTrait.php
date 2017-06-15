<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 13.06.17
 * Time: 16:12
 */

namespace ddmytruk\user\traits;

use Yii;
use yii\base\Model;
use ddmytruk\user\events\FormEvent;

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
}