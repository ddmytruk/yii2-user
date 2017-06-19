<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 19.06.17
 * Time: 22:21
 */

namespace ddmytruk\user\controllers;

use ddmytruk\user\abstracts\UserAbstract;
use ddmytruk\user\components\CommonController;
use ddmytruk\user\traits\EventTrait;
use ddmytruk\traits\AjaxValidationTrait;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * @property \ddmytruk\user\Module $module
 */

class SettingsController extends CommonController {

    use AjaxValidationTrait;
    use EventTrait;

    /**
     * Event is triggered before disconnecting social account from user.
     * Triggered with \ddmytruk\user\events\ConnectEvent.
     */
    const EVENT_BEFORE_DISCONNECT = 'beforeDisconnect';

    /**
     * Event is triggered after disconnecting social account from user.
     * Triggered with \ddmytruk\user\events\ConnectEvent.
     */
    const EVENT_AFTER_DISCONNECT = 'afterDisconnect';

    /**
     * Event is triggered before deleting user's account.
     * Triggered with \ddmytruk\user\events\UserEvent.
     */
    const EVENT_BEFORE_DELETE = 'beforeDelete';

    /**
     * Event is triggered after deleting user's account.
     * Triggered with \ddmytruk\user\events\UserEvent.
     */
    const EVENT_AFTER_DELETE = 'afterDelete';

    /**
     * Displays list of connected network accounts.
     *
     * @return string
     */
    public function actionNetworks()
    {
        return $this->render('networks', [
            'user' => \Yii::$app->user->identity,
        ]);
    }

    /**
     * Disconnects a network account from user.
     *
     * @param int $id
     *
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionDisconnect($id)
    {
        $account = $this->finder->findAccount()->byId($id)->one();

        if ($account === null) {
            throw new NotFoundHttpException();
        }
        if ($account->user_id != \Yii::$app->user->id) {
            throw new ForbiddenHttpException();
        }

        $event = $this->getConnectEvent($account, $account->user);

        $this->trigger(self::EVENT_BEFORE_DISCONNECT, $event);
        $account->delete();
        $this->trigger(self::EVENT_AFTER_DISCONNECT, $event);

        return $this->redirect(['networks']);
    }

    /**
     * Completely deletes user's account.
     *
     * @return \yii\web\Response
     * @throws \Exception
     */
    public function actionDelete()
    {
        if (!$this->module->enableAccountDelete) {
            throw new NotFoundHttpException(\Yii::t('user', 'Not found'));
        }

        /** @var UserAbstract $user */
        $user  = \Yii::$app->user->identity;
        $event = $this->getUserEvent($user);

        \Yii::$app->user->logout();

        $this->trigger(self::EVENT_BEFORE_DELETE, $event);
        $user->delete();
        $this->trigger(self::EVENT_AFTER_DELETE, $event);

        return $this->goHome();
    }

}