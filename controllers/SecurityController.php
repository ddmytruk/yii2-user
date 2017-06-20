<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 12.06.17
 * Time: 15:09
 */

namespace ddmytruk\user\controllers;

use ddmytruk\traits\AjaxValidationTrait;
use ddmytruk\user\abstracts\ResendFormAbstract;
use ddmytruk\user\abstracts\SignUpFormAbstract;
use ddmytruk\user\abstracts\UserAbstract;
use ddmytruk\user\models\form\ResendForm;
use ddmytruk\user\models\orm\SocialAccount;
use ddmytruk\user\traits\EventTrait;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\authclient\ClientInterface;
use yii\authclient\AuthAction;

use ddmytruk\user\components\CommonController;
use yii\helpers\Url;

/**
 * @property \ddmytruk\user\Module $module
 */

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

    /**
     * Event is triggered before logging user out.
     * Triggered with \ddmytruk\user\events\UserEvent.
     */
    const EVENT_BEFORE_LOGOUT = 'beforeLogout';

    /**
     * Event is triggered after logging user out.
     * Triggered with \ddmytruk\user\events\UserEvent.
     */
    const EVENT_AFTER_LOGOUT = 'afterLogout';

    /**
     * Event is triggered after creating ResendForm class.
     * Triggered with \ddmytruk\user\events\FormEvent.
     */
    const EVENT_BEFORE_RESEND = 'beforeResend';

    /**
     * Event is triggered after successful resending of confirmation email.
     * Triggered with \ddmytruk\user\events\FormEvent.
     */
    const EVENT_AFTER_RESEND = 'afterResend';

    /**
     * Event is triggered before connecting social network account to user.
     * Triggered with \ddmytruk\user\events\AuthEvent.
     */
    const EVENT_BEFORE_CONNECT = 'beforeConnect';

    /**
     * Event is triggered before connecting social network account to user.
     * Triggered with \ddmytruk\user\events\AuthEvent.
     */
    const EVENT_AFTER_CONNECT = 'afterConnect';

    /**
     * Event is triggered before authenticating user via social network.
     * Triggered with \ddmytruk\user\events\AuthEvent.
     */
    const EVENT_BEFORE_AUTHENTICATE = 'beforeAuthenticate';

    /**
     * Event is triggered after authenticating user via social network.
     * Triggered with \ddmytruk\user\events\AuthEvent.
     */
    const EVENT_AFTER_AUTHENTICATE = 'afterAuthenticate';


    public function actionSignUp() {

        /** @var $model SignUpFormAbstract */
        $model = $this->di->getSignUpForm();

        /** @var $user UserAbstract */
        $user = $this->module->modelMap['User'];

        $model->setScenario($user::SIGN_UP_SCENARIO);

        $event = $this->getFormEvent($model);

        $this->performAjaxValidation($model);

        $this->trigger(self::EVENT_BEFORE_SIGN_UP, $event);

        if ($model->load(\Yii::$app->request->post()) && $model->perform()) {

            $this->trigger(self::EVENT_AFTER_SIGN_UP, $event);

        }

        $view = $model->getViewPath() ? $model->getViewPath() : 'sign-up';

        return $this->render($view, [
            'model' => $model,
            'module' => $this->module,
        ]);

    }

    /**
     * Displays the sign in page.
     *
     * @return string|Response
     */
    public function actionSignIn() {

        if (!\Yii::$app->user->isGuest)
            $this->goHome();

        /** @var $model SignUpFormAbstract */
        $model = $this->di->getSignInForm();

        /** @var $user UserAbstract */
        $user = $this->module->modelMap['User'];

        $model->setScenario($user::SIGN_IN_SCENARIO);

        $event = $this->getFormEvent($model);

        $this->performAjaxValidation($model);

        $this->trigger(self::EVENT_BEFORE_SIGN_IN, $event);

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->perform()) {

            $this->trigger(self::EVENT_AFTER_SIGN_IN, $event);
            return $this->goBack();

        }

        $view = $model->getViewPath() ? $model->getViewPath() : 'sign-in';

        return $this->render($view, [
            'model'  => $model,
            'module' => $this->module,
        ]);

    }

    /**
     * Logs the user out and then redirects to the homepage.
     *
     * @return Response
     */
    public function actionLogout() {
        $event = $this->getUserEvent(\Yii::$app->user->identity);

        $this->trigger(self::EVENT_BEFORE_LOGOUT, $event);

        \Yii::$app->user->logout();

        $this->trigger(self::EVENT_AFTER_LOGOUT, $event);

        return $this->goHome();
    }

    /**
     * Confirms user's account. If confirmation was successful logs the user and shows success message. Otherwise
     * shows error message.
     *
     * @param int    $id
     * @param string $code
     * @throws \yii\web\HttpException
     */
    public function actionConfirm($id, $code) {

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

    /**
     * Displays page where user can request new confirmation token. If resending was successful, displays message.
     *
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionResend() {

        if ($this->module->enableConfirmationEmail == false) {
            throw new NotFoundHttpException();
        }

        /** @var ResendFormAbstract $model */
        $model = \Yii::createObject(ResendForm::className());
        $event = $this->getFormEvent($model);

        $this->trigger(self::EVENT_BEFORE_RESEND, $event);

        $this->performAjaxValidation($model);

        if ($model->load(\Yii::$app->request->post()) && $model->perform()) {

            $this->trigger(self::EVENT_AFTER_RESEND, $event);
            
        }

        $view = $model->getViewPath() ? $model->getViewPath() : 'resend';

        return $this->render($view, [
            'model' => $model,
        ]);

    }

    /**
     * Displays page where user can create new account that will be connected to social account.
     *
     * @param string $code
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionSugnUpConnect($code) {

    }

    /**
     * Tries to connect social account to user.
     *
     * @param ClientInterface $client
     */
    public function connect(ClientInterface $client)
    {
        /** @var SocialAccount $account */
        $account = \Yii::createObject(SocialAccount::className());
        $event   = $this->getAuthEvent($account, $client);

        $this->trigger(self::EVENT_BEFORE_CONNECT, $event);

        $account->connectWithUser($client);

        $this->trigger(self::EVENT_AFTER_CONNECT, $event);

        $this->action->successUrl = Url::to(['/user/settings/networks']);
    }


    /** @inheritdoc */
    public function actions()
    {
        return [
            'auth' => [
                'class' => AuthAction::className(),
                'successCallback' => \Yii::$app->user->isGuest
                    ? [$this, 'authenticate']
                    : [$this, 'connect'],
            ],
        ];
    }
    /**
     * @param ClientInterface $client
     */
    public function authenticate(ClientInterface $client) {

        $account = $this->finder->findAccount()->byClient($client)->one();

        if ($account === null) {
            /** @var SocialAccount $account */
            $accountObj = \Yii::createObject(SocialAccount::className());

            $account = $accountObj::create($client);
        }

        $event = $this->getAuthEvent($account, $client);

        $this->trigger(self::EVENT_BEFORE_AUTHENTICATE, $event);

        if ($account->user instanceof UserAbstract) {
            if ($account->user->isBlocked) {
                $this->action->successUrl = Url::to(['/user/security/sign-in']);
            } else {
                \Yii::$app->user->login($account->user, $this->module->rememberFor);
                $this->action->successUrl = \Yii::$app->user->getReturnUrl();
            }
        } else {
            $this->action->successUrl = $account->getConnectUrl();
        }

        $this->trigger(self::EVENT_AFTER_AUTHENTICATE, $event);

    }
}