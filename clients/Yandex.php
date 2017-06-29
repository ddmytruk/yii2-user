<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 27.06.17
 * Time: 16:52
 */

namespace ddmytruk\user\clients;

use Yii;
use ddmytruk\user\interfaces\ClientInterface;
use yii\authclient\clients\Yandex as BaseYandex;

/**
 * Class Yandex
 * @package ddmytruk\user\clients
 */
class Yandex extends BaseYandex implements ClientInterface {

    /** @inheritdoc */
    public function getEmail()
    {
        $emails = isset($this->getUserAttributes()['emails'])
            ? $this->getUserAttributes()['emails']
            : null;
        if ($emails !== null && isset($emails[0])) {
            return $emails[0];
        } else {
            return null;
        }
    }
    /** @inheritdoc */
    public function getUsername()
    {
        return isset($this->getUserAttributes()['login'])
            ? $this->getUserAttributes()['login']
            : null;
    }
    /** @inheritdoc */
    protected function defaultTitle()
    {
        return Yii::t('user', 'Yandex');
    }

}