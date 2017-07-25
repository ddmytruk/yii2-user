<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 29.06.17
 * Time: 15:34
 */

namespace ddmytruk\user\clients;

use ddmytruk\user\interfaces\ClientInterface;
use yii\authclient\clients\Google as BaseGoogle;

/**
 * Class Google
 * @package ddmytruk\user\clients
 */
class Google extends BaseGoogle implements ClientInterface {

    public $buttonContent;

    /** @inheritdoc */
    public function getEmail()
    {
        return isset($this->getUserAttributes()['emails'][0]['value'])
            ? $this->getUserAttributes()['emails'][0]['value']
            : null;
    }

    /** @inheritdoc */
    public function getUsername()
    {
        return;
    }

}