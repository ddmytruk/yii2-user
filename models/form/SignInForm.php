<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 16.06.17
 * Time: 12:56
 */

namespace ddmytruk\user\models\form;

use ddmytruk\user\helpers\Password;
use ddmytruk\user\traits\ModuleTrait;
use ddmytruk\user\abstracts\UserAbstract;
use ddmytruk\user\abstracts\SignInFormAbstract;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use ddmytruk\user\Finder;

class SignInForm extends SignInFormAbstract
{
    use ModuleTrait;

    /** @var \ddmytruk\user\abstracts\UserAbstract*/
    protected $user;

    /** @var Finder */
    protected $finder;

    /** @var integer */
    protected $loginType;

    /**
     * @param Finder $finder
     * @param array  $config
     */
    public function __construct(Finder $finder, $config = [])
    {
        $this->finder = $finder;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        /** @var $user UserAbstract */
        $user = $this->module->modelMap['User'];

        $user::setScenarioConfig($this->module->signInScenarioConfig);

        return $user::rulesForForm(SignInFormAbstract::className(), $user::SIGN_IN_SCENARIO);
    }

    public function confirmationValidate($attribute) {

        /** @var $user UserAbstract */
        $user = $this->module->modelMap['User'];

        if ($this->user !== null) {

            $confirmationRequired = true;
            if($this->loginType == $user::SIGN_IN_EMAIL) {
                $confirmationRequired = $this->module->enableConfirmationEmail
                && !$this->module->enableUnconfirmedLogin;
            } elseif ($this->loginType == $user::SIGN_IN_PHONE) {
                $confirmationRequired = $this->module->enableConfirmationPhone
                    && !$this->module->enableUnconfirmedLogin;
            }

            if ($confirmationRequired && !$this->user->getIsConfirmed()) {
                $this->addError($attribute, 'You need to confirm your email address');
            }
            if ($this->user->getIsBlocked()) {
                $this->addError($attribute, 'Your account has been blocked');
            }
        }

    }

    public function passwordValidate($attribute) {

        if ($this->user === null || !Password::validate($this->password, $this->user->password_hash)) {
            $this->addError($attribute, 'Invalid login or password');
        }

    }

    public function scenarios()
    {
        /** @var $user UserAbstract */
        $user = $this->module->modelMap['User'];

        $scenarios = parent::scenarios();
        return ArrayHelper::merge($scenarios, $user::getScenarios());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'login'    => 'Login',
            'password' => 'Password',
        ];
    }

    /**
     * Registers a new user account.
     *
     * @return bool
     */
    public function perform() {

        if ($this->validate()) {
            $this->user->updateAttributes(['last_sign_in' => new Expression('NOW()')]);
            return \Yii::$app->user->login($this->user, $this->rememberMe ? $this->module->rememberFor : 0);
        }

        return false;

    }

    /** @inheritdoc */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $userData = $this->finder->findUserByLogin(trim($this->login), $this->module->signInScenarioConfig);
            $this->user = $userData['user'];
            $this->loginType = $userData['loginType'];

            return true;
        } else {
            return false;
        }
    }
}