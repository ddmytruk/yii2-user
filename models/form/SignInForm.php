<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 16.06.17
 * Time: 12:56
 */

namespace ddmytruk\user\models\form;

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

        return $user::rulesForForm(SignInFormAbstract::className());
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
            #$this->user->updateAttributes(['last_sign_in' => new Expression('NOW()')]);
            #return \Yii::$app->user->login($this->user, $this->rememberMe ? $this->module->rememberFor : 0);
        }

        echo "<pre>";
        print_r($this->module->signInScenario);
        #print_r($this);
        echo "</pre>";
        die;

        return false;

    }

    /** @inheritdoc */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            #$this->user = $this->finder->findUserByUsernameOrEmail(trim($this->login));

            return true;
        } else {
            return false;
        }
    }
}