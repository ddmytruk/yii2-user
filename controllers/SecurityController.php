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
use ddmytruk\user\abstracts\UserAbstract;
use ddmytruk\user\traits\EventTrait;
use yii\web\NotFoundHttpException;

use ddmytruk\user\components\CommonController;

class SecurityController extends CommonController
{
    use AjaxValidationTrait;
    use EventTrait;

    /**
     * Event is triggered before logging user in.
     * Triggered with \ddmytruk\user\events\FormEvent.
     */
    const EVENT_BEFORE_SIGN_IN = 'beforeSignIn';

    /**
     * Event is triggered after logging user in.
     * Triggered with \ddmytruk\user\events\FormEvent.
     */
    const EVENT_AFTER_SIGN_IN = 'afterSignIn';

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

    /**
     * Event is triggered before confirming user.
     * Triggered with \ddmytruk\user\events\UserEvent.
     */
    const EVENT_BEFORE_CONFIRM = 'beforeConfirm';

    /**
     * Event is triggered before confirming user.
     * Triggered with \ddmytruk\user\events\UserEvent.
     */
    const EVENT_AFTER_CONFIRM = 'afterConfirm';


    public function actionSignIn() {

        /** @var $model SignUpFormAbstract */
        $model = $this->di->getSignUpForm();

        $event = $this->getFormEvent($model);

        $this->trigger(self::EVENT_BEFORE_SIGN_UP, $event);

        $model->setScenario($this->module->signUpScenario);

        $this->performAjaxValidation($model);

        if ($model->load(\Yii::$app->request->post()) && $model->perform()) {

            $this->trigger(self::EVENT_AFTER_SIGN_UP, $event);

        }

        $view = $model->getViewPath() ? $model->getViewPath() : 'sign-up';

        return $this->render($view, [
            'model' => $model
        ]);

    }

    /**
     * Confirms user's account. If confirmation was successful logs the user and shows success message. Otherwise
     * shows error message.
     *
     * @param int    $id
     * @param string $code
     * @throws \yii\web\HttpException
     */
    public function actionConfirm($id, $code)
    {
        /** @var $user UserAbstract */
        $user = $this->finder->findUserById($id);

        if($user === null) {
            throw new NotFoundHttpException();
        }

        $event = $this->getUserEvent($user);

        $this->trigger(self::EVENT_BEFORE_CONFIRM, $event);

        if(!$user->attemptConfirmation($code)) {
            throw new NotFoundHttpException();
        }

        $this->trigger(self::EVENT_AFTER_CONFIRM, $event);

        return $this->render('/message', [
            'title'  => 'Account confirmation',
        ]);
    }
}