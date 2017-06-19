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
        'SignInForm' => 'ddmytruk\user\models\form\SignInForm',
        'User' => 'ddmytruk\user\models\orm\User',
        'Token' => 'ddmytruk\user\models\orm\Token'
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

                if (in_array($name, ['User', 'Token'])) {

                    Yii::$container->set($name . 'Query', function () use ($modelName) {
                        return $modelName::find();
                    });

                }
            }

            Yii::$container->setSingleton(Finder::className(), [
                'userQuery'    => Yii::$container->get('UserQuery'),
                'tokenQuery'   => Yii::$container->get('TokenQuery'),
            ]);

            Yii::$container->setSingleton(DI::className(), [
                'signUpForm'    => Yii::$container->get('SignUpForm'),
                'signInForm'    => Yii::$container->get('SignInForm'),
                'user' => Yii::$container->get('User')
            ]);


            Yii::$container->set('ddmytruk\user\Mailer', $module->mailer);
        }

    }

    private function setDefinition($definition) {

    }

}