<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 12.06.17
 * Time: 15:09
 */

namespace ddmytruk\user\controllers;

use ddmytruk\traits\AjaxValidationTrait;
use ddmytruk\user\abstracts\SignUpFormAbstract;
use ddmytruk\user\traits\EventTrait;
use Yii;

use ddmytruk\user\components\CommonController;

class SecurityController extends CommonController
{
    use AjaxValidationTrait;
    use EventTrait;

    /**
     * Event is triggered after creating SignUpForm class.
     * Triggered with \ddmytruk\user\events\FormEvent.
     */
    const EVENT_BEFORE_SIGN_UP = 'beforeSignUp';

    /**
     * Event is triggered after successful sign up
     * Triggered with \ddmytruk\user\events\FormEvent.
     */
    const EVENT_AFTER_SIGN_UP = 'afterSignUp';


    public function actionSignIn() {

        #$this->layout = false;

        /** @var $model SignUpFormAbstract */
        $model = $this->di->getSignUpForm();

        $event = $this->getFormEvent($model);

        $this->trigger(self::EVENT_BEFORE_SIGN_UP, $event);

        $model->setScenario($this->module->signUpScenario);

        $this->performAjaxValidation($model);

        if ($model->load(\Yii::$app->request->post()) && $model->perform()) {

//            var_dump($model);
//            die;

            $this->trigger(self::EVENT_AFTER_SIGN_UP, $event);

        }

        $view = $model->getViewPath() ? $model->getViewPath() : 'sign-up';

        return $this->render($view, [
            'model' => $model
        ]);

    }
}