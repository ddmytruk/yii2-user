<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 13.06.17
 * Time: 11:51
 */

namespace ddmytruk\user\models\form;


use ddmytruk\user\abstracts\SignUpFormAbstract;
use ddmytruk\user\abstracts\UserAbstract;
use ddmytruk\user\models\orm\User;
use ddmytruk\user\traits\ModuleTrait;
use yii\helpers\ArrayHelper;

class SignUpForm extends SignUpFormAbstract
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public function rules() {

        /** @var $user UserAbstract */
        $user = $this->module->modelMap['User'];

        $user::setScenarioConfig($this->module->signUpScenarioConfig);

        return $user::rulesForForm(SignUpFormAbstract::className(), $user::SIGN_UP_SCENARIO);

    }

    /**
     * @inheritdoc
     */
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
            'email'     => \Yii::t('user', 'Email'),
            'phone'     => \Yii::t('user', 'Phone'),
            'username'  => \Yii::t('user', 'Username'),
            'password'  => \Yii::t('user', 'Password'),
        ];
    }

    /**
     * Registers a new user account.
     *
     * @return bool
     */
    public function perform() {

        if (!$this->validate()) {
            return false;
        }

        /** @var User $user */
        $user = \Yii::createObject(User::className());
        $user->setScenario($this->getScenario());
        $this->loadAttributes($user);

        if (!$user->signUp()) {
            return false;
        }

        return true;

    }

    /**
     * Loads attributes to the user model. You should override this method if you are going to add new fields to the
     * registration form. You can read more in special guide.
     *
     * By default this method set all attributes of this model to the attributes of User model, so you should properly
     * configure safe attributes of your User model.
     *
     * @param User $user
     */
    protected function loadAttributes(User $user)
    {
        $user->setAttributes($this->attributes);
    }

}