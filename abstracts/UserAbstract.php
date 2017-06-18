<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 13.06.17
 * Time: 17:52
 */

namespace ddmytruk\user\abstracts;

use ddmytruk\user\interfaces\ORMInterface;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;

abstract class UserAbstract extends ActiveRecord implements IdentityInterface, ORMInterface
{

    const SIGN_UP_SCENARIO = 'singUp';
    const SIGN_IN_SCENARIO = 'singIn';

    const SIGN_UP_EMAIL = 'signUpEmail';
    const SIGN_UP_USERNAME = 'signUpUsername';
    const SIGN_UP_PHONE = 'signUpPhone';
    #const SIGN_UP_EMAIL_AND_USERNAME = 'signUpEmailAndUsername';

    const SIGN_IN_EMAIL = 'signInEmail';
    const SIGN_IN_USERNAME = 'signInUsername';
    const SIGN_IN_PHONE = 'signInUsername';

    public $password;

    /**
     * @var string[] scenario config array
     */
    public static $scenarioConfig;

    /**
     * @param string[] $value scenario config array.
     */
    public static function setScenarioConfig($value)
    {
        static::$scenarioConfig = $value;
    }

    /**
     * Attempts user confirmation.
     *
     * @param string $code Confirmation code.
     *
     * @return boolean
     */
    abstract public function attemptConfirmation($code);

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAttribute('auth_key') === $authKey;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->getAttribute('auth_key');
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('Method "' . __CLASS__ . '::' . __METHOD__ . '" is not implemented.');
    }
}