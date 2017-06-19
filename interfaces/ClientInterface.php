<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 19.06.17
 * Time: 20:51
 */

namespace ddmytruk\user\interfaces;

use yii\authclient\ClientInterface as BaseInterface;

interface ClientInterface extends BaseInterface
{
    /** @return string|null User's email */
    public function getEmail();

    /** @return string|null User's username */
    public function getUsername();
}