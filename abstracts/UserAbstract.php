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


    const STATUS_CONFIRMED = 10;
    const STATUS_BLOCKED = 1;
    const STATUS_UN_CONFIRMED = 0;

    /**
     * @var string username regexp
     */
    public static $usernameRegexp = '/^[-a-zA-Z]+$/';

    /**
     * @var string phone regexp
     */
    public static $phoneRegexp = '/^(\d{12})$/';

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
     * @return bool Whether the user is confirmed or not.
     */
    public function getIsConfirmed()
    {
        return $this->status == static::STATUS_CONFIRMED;
    }

    /**
     * @return bool Whether the user is blocked or not.
     */
    public function getIsBlocked()
    {
        return $this->status == static::STATUS_BLOCKED;
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