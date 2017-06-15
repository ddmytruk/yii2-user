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
        'SignUpForm' => 'ddmytruk\user\models\form\SignUpForm',
        'User' => 'ddmytruk\user\models\orm\User',
    ];

    /**
     * @inheritdoc
     */
    public function bootstrap($app) {

        if ($app->hasModule('user') && ($module = $app->getModule('user')) instanceof Module) {
            $this->_modelMap = array_merge($this->_modelMap, $module->modelMap);

            foreach ($this->_modelMap as $name => $definition) {

                $modelName = is_array($definition) ? $definition['class'] : $definition;

                $module->modelMap[$name] = $modelName;

                Yii::$container->set($name, function () use ($modelName) {
                    return Yii::createObject($modelName);
                });
            }

            Yii::$container->setSingleton(DI::className(), [
                'signUpForm'    => Yii::$container->get('SignUpForm'),
                'user' => Yii::$container->get('User')
            ]);

            #Yii::$container->set('dektrium\user\Mailer', $module->mailer);
        }

    }

    private function setDefinition($definition) {

    }

}