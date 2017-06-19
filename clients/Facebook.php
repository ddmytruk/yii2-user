<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 19.06.17
 * Time: 20:51
 */

namespace ddmytruk\user\clients;


use ddmytruk\user\interfaces\ClientInterface;
use yii\authclient\clients\Facebook as BaseFacebook;

class Facebook extends BaseFacebook implements ClientInterface
{

    public $buttonContent;

    /** @inheritdoc */
    public function getEmail()
    {
        return isset($this->getUserAttributes()['email'])
            ? $this->getUserAttributes()['email']
            : null;
    }

    /** @inheritdoc */
    public function getUsername()
    {
        return;
    }
}