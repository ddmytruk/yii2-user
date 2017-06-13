<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 12.06.17
 * Time: 17:45
 */

namespace ddmytruk\user;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface {

    private $_modelMap = [

    ];

    /**
     * @inheritdoc
     */
    public function bootstrap($app) {

        if ($app->hasModule('user') && ($module = $app->getModule('user')) instanceof Module) {
            $this->_modelMap = array_merge($this->_modelMap, $module->modelMap);
        }

    }

    private function setDefinition($definition) {



    }

}