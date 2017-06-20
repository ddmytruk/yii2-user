<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 19.06.17
 * Time: 17:52
 */

namespace ddmytruk\user\controllers;

use ddmytruk\traits\AjaxValidationTrait;
use ddmytruk\user\abstracts\RecoveryFormAbstract;
use ddmytruk\user\models\form\RecoveryForm;
use ddmytruk\user\models\form\ResetPasswordForm;
use ddmytruk\user\models\orm\Token;
use ddmytruk\user\traits\EventTrait;

use ddmytruk\user\components\CommonController;
use ddmytruk\user\events\ResetPasswordEvent;

/**
 * @property \ddmytruk\user\Module $module
 */

class RecoveryController extends CommonController {

    use AjaxValidationTrait;
    use EventTrait;

    /**
     * Event is triggered before requesting password reset.
     * Triggered with \ddmytruk\user\events\FormEvent.
     */
    const EVENT_BEFORE_REQUEST = 'beforeRequest';

    /**
     * Event is triggered after requesting password reset.
     * Triggered with \ddmytruk\user\events\FormEvent.
     */
    const EVENT_AFTER_REQUEST = 'afterRequest';

    /**
     * Event is triggered before validating recovery token.
     * Triggered with \ddmytruk\user\events\ResetPasswordEvent. May not have $form property set.
     */
    const EVENT_BEFORE_TOKEN_VALIDATE = 'beforeTokenValidate';

    /**
     * Event is triggered after validating recovery token.
     * Triggered with \ddmytruk\user\events\ResetPasswordEvent. May not have $form property set.
     */
    const EVENT_AFTER_TOKEN_VALIDATE = 'afterTokenValidate';

    /**
     * Event is triggered before resetting password.
     * Triggered with \ddmytruk\user\events\ResetPasswordEvent.
     */
    const EVENT_BEFORE_RESET = 'beforeReset';

    /**
     * Event is triggered after resetting password.
     * Triggered with \ddmytruk\user\events\ResetPasswordEvent.
     */
    const EVENT_AFTER_RESET = 'afterReset';

    /**
     * Shows page where user can request password recovery.
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionRequest() {

        /** @var RecoveryForm $model */
        $model = $this->di->getRecoveryForm();

        $event = $this->getFormEvent($model);

        $this->performAjaxValidation($model);

        $this->trigger(self::EVENT_BEFORE_REQUEST, $event);

        if ($model->load(\Yii::$app->request->post()) && $model->perform()) {
            $this->trigger(self::EVENT_AFTER_REQUEST, $event);
        }

        $view = $model->getViewPath() ? $model->getViewPath() : 'request';

        return $this->render('request', [
            'model' => $model,
        ]);

    }

    /**
     * Displays page where user can reset password.
     *
     * @param int    $id
     * @param string $code
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionReset($id, $code) {

        /** @var Token $token */
        $token = $this->finder->findToken(['user_id' => $id, 'code' => $code, 'type' => Token::TYPE_RECOVERY])->one();
        $event = $this->getResetPasswordEvent($token);

        $this->trigger(self::EVENT_BEFORE_TOKEN_VALIDATE, $event);

        if ($token === null || $token->isExpired || $token->user === null) {
            $this->trigger(self::EVENT_AFTER_TOKEN_VALIDATE, $event);

            return $this->render('/message', [
                'title'  => \Yii::t('user', 'Invalid or expired link'),
                'module' => $this->module,
            ]);
        }

        /** @var RecoveryFormAbstract $model */
        $model = $this->di->getResetPasswordForm();

        $event->setForm($model);

        $this->performAjaxValidation($model);

        $this->trigger(self::EVENT_BEFORE_RESET, $event);

        $model->token = $token;
        if ($model->load(\Yii::$app->getRequest()->post()) && $model->perform($token)) {
            $this->trigger(self::EVENT_AFTER_RESET, $event);
            return $this->render('/message', [
                'title'  => \Yii::t('user', 'Password has been changed'),
                'module' => $this->module,
            ]);
        }

        $view = $model->getViewPath() ? $model->getViewPath() : 'reset';

        return $this->render('reset', [
            'model' => $model,
        ]);

    }

}