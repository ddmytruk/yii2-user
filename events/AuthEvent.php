<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 19.06.17
 * Time: 22:16
 */

namespace ddmytruk\user\events;


use ddmytruk\user\models\orm\SocialAccount;
use yii\authclient\ClientInterface;
use yii\base\Event;

/**
 * @property SocialAccount         $account
 * @property ClientInterface $client
 */
class AuthEvent extends Event
{
    /**
     * @var ClientInterface
     */
    private $_client;

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
     * @return ClientInterface
     */
    public function getClient()
    {
        return $this->_client;
    }

    /**
     * @param ClientInterface $client
     */
    public function setClient(ClientInterface $client)
    {
        $this->_client = $client;
    }
}