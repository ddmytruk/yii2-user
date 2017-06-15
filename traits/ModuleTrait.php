<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 15.06.17
 * Time: 11:34
 */

namespace ddmytruk\user\traits;

use ddmytruk\user\Module;
/**
 * Trait ModuleTrait
 * @property-read Module $module
 * @package ddmytruk\user\traits
 */
trait ModuleTrait
{

    /**
     * @return Module|null the module instance, `null` if the module does not exist.
     */
    public function getModule()
    {
        return \Yii::$app->getModule('user');
    }

}