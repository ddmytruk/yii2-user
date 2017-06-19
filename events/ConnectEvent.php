<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 19.06.17
 * Time: 23:24
 */

namespace ddmytruk\user\events;

use ddmytruk\user\abstracts\UserAbstract;
use ddmytruk\user\models\orm\SocialAccount;
use yii\base\Event;

class ConnectEvent extends Event {

    /**
     * @var UserAbstract
     */
    private $_user;

    /**
     * @var SocialAccount
     */
    private $_account;

    /**
     * @return SocialAccount
     */
    public function getAccount()
    {
        return $this->_account;
    }

    /**
     * @param SocialAccount $account
     */
    public function setAccount(SocialAccount $account)
    {
        $this->_account = $account;
    }

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
    public function setUser(UserAbstract $user)
    {
        $this->_user = $user;
    }

}