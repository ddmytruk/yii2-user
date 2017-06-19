<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 19.06.17
 * Time: 23:55
 */

namespace ddmytruk\user\clients;

use ddmytruk\user\interfaces\ClientInterface;
use Yii;
use yii\authclient\clients\VKontakte as BaseVKontakte;

/**
 * Class VKontakte
 * @package ddmytruk\user\clients
 */

class VKontakte extends BaseVKontakte implements ClientInterface
{
    /** @inheritdoc */
    public $scope = 'email';

    /** @inheritdoc */
    public function getEmail()
    {
        return $this->getAccessToken()->getParam('email');
    }

    /** @inheritdoc */
    public function getUsername()
    {
        return isset($this->getUserAttributes()['screen_name'])
            ? $this->getUserAttributes()['screen_name']
            : null;
    }

    /** @inheritdoc */
    protected function defaultTitle()
    {
        return Yii::t('user', 'VKontakte');
    }
}