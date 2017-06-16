<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 16.06.17
 * Time: 11:42
 */

namespace ddmytruk\user\events;

use ddmytruk\user\abstracts\UserAbstract;
use yii\base\Event;
use ddmytruk\user\models\orm\User;

class UserEvent extends Event
{
    /**
     * @var UserAbstract
     */
    private $_user;

    /**
     * @return UserAbstract
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * @param UserAbstract $form
     */
    public function setUser(UserAbstract $form)
    {
        $this->_user = $form;
    }
}