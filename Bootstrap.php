<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 12.06.17
 * Time: 17:45
 */

namespace ddmytruk\user;

use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface {

    private $_modelMap = [
        'SignUpForm' => [
            'class' => 'ddmytruk\user\models\form\SignUpForm'
        ]
    ];

    /**
     * @inheritdoc
     */
    public function bootstrap($app) {

        if ($app->hasModule('user') && ($module = $app->getModule('user')) instanceof Module) {
            $this->_modelMap = array_merge($this->_modelMap, $module->modelMap);

            foreach ($this->_modelMap as $name => $definition) {

                #$class = str_replace("\\", "/", $definition);

                $modelName = is_array($definition) ? $definition['class'] : $definition;

                $module->modelMap[$name] = $modelName;

                Yii::$container->set($name, function () use ($modelName) {
                    return Yii::createObject($modelName);
                });

                #var_dump($class);
                #var_dump($definition);
//                var_dump($modelName);
//                var_dump(Yii::$container->get('SignUpForm'));
//                die;

            }

            Yii::$container->setSingleton(DI::className(), [
                'signUpForm'    => Yii::$container->get('SignUpForm'),
            ]);

        }

    }

    private function setDefinition($definition) {



    }

}