<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 13.06.17
 * Time: 17:25
 */

namespace ddmytruk\user\models\orm;

use ddmytruk\user\abstracts\UserAbstract;
use ddmytruk\user\Finder;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\Application as WebApplication;
use ddmytruk\user\helpers\Password;
use ddmytruk\user\Mailer;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property integer $status
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property string $auth_key
 * @property string $sing_up_ip
 * @property integer $role
 * @property string $last_sign_in
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Token[] $tokens
 */

class User extends UserAbstract
{
    const STATUS_CONFIRMED = 10;
    const STATUS_BLOCKED = 1;
    const STATUS_UN_CONFIRMED = 0;

    const BEFORE_SIGN_UP = 'beforeSignUp';
    const AFTER_SIGN_UP  = 'afterSignUp';

    const BEFORE_CONFIRM  = 'beforeConfirm';
    const AFTER_CONFIRM   = 'afterConfirm';
    /**
     * @var string username regexp
     */
    public static $usernameRegexp = '/^[-a-zA-Z]+$/';

    /**
     * @return object an instance of the requested class. (Mailer)
     * @throws \yii\base\InvalidConfigException
     */
    protected function getMailer()
    {
        return \Yii::$container->get(Mailer::className());
    }

    /**
     * @return object an instance of the requested class.(Finder)
     * @throws \yii\base\InvalidConfigException
     */
    protected function getFinder()
    {
        return \Yii::$container->get(Finder::className());
    }

    /**
     * @inheritdoc
     */
    public function rules() {

        $rules = [
            'password_hashRequired' => ['password_hash', 'required'],
            'password_hash'         => ['password_hash', 'string', 'max' => 60],


            'auth_keyRequired'  => ['auth_key', 'required'],
            'auth_keyLength'    => ['auth_key', 'string', 'max' => 32],


            'sing_up_ipRequired'    => ['sing_up_ip', 'required'],
            'sing_up_ipLength'      => ['sing_up_ip', 'string', 'max' => 45],


            'created_atRequired' => ['created_at', 'required'],
            'created_atFormat'   => ['created_at', 'date', 'format' => 'yyyy-M-d H:m:s'],


            'updated_atRequired'    => ['updated_at', 'required'],
            'updated_atFormat'      => ['updated_at', 'date', 'format' => 'yyyy-M-d H:m:s'],


            'last_sign_inFormat'    => ['last_sign_in', 'date', 'format' => 'yyyy-M-d H:m:s'],


            'roleLength' => ['role', 'integer'],

            'statusLenght' => ['status', 'integer'],
        ];

        $rules = array_merge($rules, static::rulesForForm());

        return $rules;

    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        return static::getScenarios();
    }

    public static function getScenarios() {

        return [
            static::SCENARIO_SIGN_UP_EMAIL => ['email', 'password'],
            static::SCENARIO_SIGN_UP_USERNAME => ['username', 'password'],
            static::SCENARIO_SIGN_UP_EMAIL_AND_USERNAME => ['username', 'email', 'password']
        ];

    }

    /**
     * @inheritdoc
     */
    public static function rulesForForm() {

        $rules = [
            'usernameRequired' => ['username', 'required', 'on' => [
                static::SCENARIO_SIGN_UP_USERNAME, static::SCENARIO_SIGN_UP_EMAIL_AND_USERNAME
            ]],
            'usernameMatch'    => ['username', 'match', 'pattern' => static::$usernameRegexp],
            'usernameLength'   => ['username', 'string', 'min' => 3, 'max' => 25],
            'usernameUnique'   => [
                'username',
                'unique',
                'targetClass' => get_class(),
                'message' => 'This username has already been taken'
            ],
            'usernameTrim'     => ['username', 'trim'],


            'emailRequired' => ['email', 'required', 'on' => [
                static::SCENARIO_SIGN_UP_EMAIL, static::SCENARIO_SIGN_UP_EMAIL_AND_USERNAME
            ]],
            'emailPattern'  => ['email', 'email'],
            'emailLength'   => ['email', 'string', 'max' => 255],
            'emailUnique'   => [
                'email',
                'unique',
                'targetClass' => get_class(),
                'message' => 'This email address has already been taken'
            ],
            'emailTrim'     => ['email', 'trim'],


            'passwordRequired' => ['password', 'required'],
            'passwordLength'   => ['password', 'string', 'min' => 6, 'max' => 72],
        ];

        return $rules;

    }

    /** @inheritdoc */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->setAttribute('auth_key', \Yii::$app->security->generateRandomString());
            if (\Yii::$app instanceof WebApplication) {
                $this->setAttribute('sing_up_ip', \Yii::$app->request->userIP);
            }
        }

        if (!empty($this->password)) {
            $this->setAttribute('password_hash', Password::hash($this->password));
        }

        return parent::beforeSave($insert);
    }

    /**
     * New user account
     * @return bool
     */
    public function signUp() {

        $transaction = $this->getDb()->beginTransaction();

        try {

            $this->trigger(static::BEFORE_SIGN_UP);

            if (!$this->save()) {
                $transaction->rollBack();
                return false;
            }

            /** @var Token $token */
            $token = \Yii::createObject(['class' => Token::className(), 'type' => Token::TYPE_CONFIRMATION]);
            $token->link('user', $this);

            $this->mailer->sendWelcomeMessage($this, isset($token) ? $token : null);

            $this->trigger(self::AFTER_SIGN_UP);

            $transaction->commit();

            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::warning($e->getMessage());
            throw $e;
        }
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password_hash' => 'Password Hash',
            'auth_key' => 'Auth Key',
            'sing_up_ip' => 'Sing Up Ip',
            'role' => 'Role',
            'last_sign_in' => 'Last Sign In',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];

    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
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
    public function attemptConfirmation($code)
    {
        $token = $this->finder->findTokenByParams($this->id, $code, Token::TYPE_CONFIRMATION);

        if ($token instanceof Token && !$token->isExpired) {
            $token->delete();
            if (($success = $this->confirm())) {
                \Yii::$app->user->login($this, $this->module->rememberFor);
            }
        } else {
            $success = false;
        }

        return $success;
    }

    /**
     * @return boolean
     */
    public function confirm()
    {
        $this->trigger(self::BEFORE_CONFIRM);
        $result = (bool) $this->updateAttributes(['status' => static::STATUS_CONFIRMED]);
        $this->trigger(self::AFTER_CONFIRM);
        return $result;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTokens() {
        return $this->hasMany(Token::className(), ['user_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user}}';
    }

}