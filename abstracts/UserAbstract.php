<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 13.06.17
 * Time: 17:52
 */

namespace ddmytruk\user\abstracts;

use ddmytruk\user\helpers\Password;
use ddmytruk\user\interfaces\ORMInterface;
use ddmytruk\user\models\orm\SocialAccount;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;

/**
 * Class UserAbstract
 * @package ddmytruk\user\abstracts
 * @property SocialAccount[] $accounts
 */

abstract class UserAbstract extends ActiveRecord implements IdentityInterface, ORMInterface
{

    const SIGN_UP_SCENARIO = 'singUp';
    const SIGN_IN_SCENARIO = 'singIn';
    const CONNECT_SCENARIO = 'connect';

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

    /**
     * @var string Password
     */
    public $password;

    /**
     * @var string[] scenario config array
     */
    public static $scenarioConfig = [];

    /**
     * @param string[] $value scenario config array.
     */
    public static function setScenarioConfig($value)
    {
        static::$scenarioConfig = $value;
    }

    /**
     * Resets password.
     *
     * @param string $password
     *
     * @return bool
     */
    public function resetPassword($password)
    {
        return (bool)$this->updateAttributes(['password_hash' => Password::hash($password)]);
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
     * @return SocialAccount[] Connected accounts ($provider => $account)
     */
    public function getAccounts()
    {
        $connected = [];
        $accounts  = $this->hasMany($this->module->modelMap['Account'], ['user_id' => 'id'])->all();

        /** @var SocialAccount $account */
        foreach ($accounts as $account) {
            $connected[$account->provider] = $account;
        }

        return $connected;
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
     * Creates new user account. It generates password if it is not provided by user.
     *
     * @return bool
     */
    abstract public function create();

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