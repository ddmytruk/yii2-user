<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 13.06.17
 * Time: 17:25
 */

namespace ddmytruk\user\models\orm;

use ddmytruk\user\abstracts\SignInFormAbstract;
use ddmytruk\user\abstracts\SignUpFormAbstract;
use ddmytruk\user\abstracts\UserAbstract;
use ddmytruk\user\Finder;
use ddmytruk\user\traits\ModuleTrait;
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
 * @property string $phone
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
    use ModuleTrait;

    const BEFORE_SIGN_UP = 'beforeSignUp';
    const AFTER_SIGN_UP  = 'afterSignUp';

    const BEFORE_CONFIRM  = 'beforeConfirm';
    const AFTER_CONFIRM   = 'afterConfirm';

    const BEFORE_CREATE   = 'beforeCreate';
    const AFTER_CREATE    = 'afterCreate';

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

        #$rules = array_merge($rules, static::rulesForForm());

        return $rules;

    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        return static::getScenarios();
    }

    private static function getRulesConfigForAttribute($attribute, $scenario)
    {
        $result = static::SCENARIO_DEFAULT;

        switch ($attribute) {
            case 'username':
                if(in_array(static::SIGN_UP_USERNAME,  static::$scenarioConfig))
                    return $scenario;
                break;

            case 'email':
                if(in_array(static::SIGN_UP_EMAIL,  static::$scenarioConfig))
                    return $scenario;
                break;

            case 'phone':
                if(in_array(static::SIGN_UP_PHONE,  static::$scenarioConfig))
                    return $scenario;
                break;

            default:
                return static::SCENARIO_DEFAULT;
        }

        return $result;
    }

    public static function getRulesConfigForAttributes()
    {
        $result = [];

        if(in_array(static::SIGN_UP_USERNAME,  static::$scenarioConfig))
            $result[static::SIGN_UP_SCENARIO][] = 'username';
        if(in_array(static::SIGN_UP_EMAIL,  static::$scenarioConfig))
            $result[static::SIGN_UP_SCENARIO][] = 'email';
        if(in_array(static::SIGN_UP_PHONE,  static::$scenarioConfig))
            $result[static::SIGN_UP_SCENARIO][] = 'phone';

        $result[static::SIGN_UP_SCENARIO][] = 'password';

        $result[static::CONNECT_SCENARIO][] = ['username', 'email'];

        return $result;
    }

    public static function getScenarios() {
        return static::getRulesConfigForAttributes();
    }

    /**
     * @inheritdoc
     * @param string $scenario the scenario that this model is in.
     */
    public static function rulesForForm($className, $scenario) {

        if($className == SignUpFormAbstract::className()) {

            $result = [
                'usernameRequired' => [
                    'username', 'required', 'on' =>
                        static::getRulesConfigForAttribute('username', $scenario)
                ],
                'usernameMatch'    => [
                    'username', 'match', 'pattern' => static::$usernameRegexp, 'on' =>
                        static::getRulesConfigForAttribute('username', $scenario)
                ],
                'usernameLength'   => [
                    'username', 'string', 'min' => 3, 'max' => 25, 'on' =>
                        static::getRulesConfigForAttribute('username', $scenario)
                ],
                'usernameUnique'   => [
                    'username',
                    'unique',
                    'targetClass' => get_class(),
                    'message' => \Yii::t('user', 'This username has already been taken'),
                    'on' => static::getRulesConfigForAttribute('username', $scenario)
                ],
                'usernameTrim'     => ['username', 'trim', 'on' =>
                    static::getRulesConfigForAttribute('username', $scenario)
                ],


                'emailRequired' => [
                    'email', 'required', 'on' =>
                        static::getRulesConfigForAttribute('email', $scenario)
                ],
                'emailPattern'  => [
                    'email', 'email', 'on' =>
                        static::getRulesConfigForAttribute('email', $scenario)
                ],
                'emailLength'   => [
                    'email', 'string', 'max' => 255, 'on' =>
                        static::getRulesConfigForAttribute('email', $scenario)
                ],
                'emailUnique'   => [
                    'email',
                    'unique',
                    'targetClass' => get_class(),
                    'message' => \Yii::t('user', 'This email address has already been taken'),
                    'on' => static::getRulesConfigForAttribute('email', $scenario)
                ],
                'emailTrim'     => ['email', 'trim', 'on' =>
                    static::getRulesConfigForAttribute('email', $scenario)
                ],


                'phoneRequired' => [
                    'phone', 'required', 'on' =>
                        static::getRulesConfigForAttribute('phone', $scenario)
                ],
                'phoneMatch'    => [
                    'phone', 'match', 'pattern' => static::$phoneRegexp, 'on' =>
                        static::getRulesConfigForAttribute('phone', $scenario)
                ],
                'phoneUnique'   => [
                    'phone',
                    'unique',
                    'targetClass' => get_class(),
                    'message' => \Yii::t('user', 'This phone has already been taken'),
                    'on' => static::getRulesConfigForAttribute('phone', $scenario)
                ],
                'phoneTrim'     => ['phone', 'trim', 'on' =>
                    static::getRulesConfigForAttribute('phone', $scenario)
                ],


                'passwordRequired' => ['password', 'required', 'on' => $scenario],
                'passwordLength'   => ['password', 'string', 'min' => 6, 'max' => 72, 'on' => $scenario],
            ];

        } elseif ($className == SignInFormAbstract::className()) {

            #var_dump($this->module->signInScenario);

            $result = [
                'loginRequired' => ['login', 'required', 'on' => $scenario],
                'loginTrim' => ['login', 'trim'],
                'confirmationValidate' => ['login', 'confirmationValidate'],

                'requiredFields' => [['login', 'password'], 'required'],
                'passwordValidate' => ['password', 'passwordValidate'],

                'rememberMe' => ['rememberMe', 'boolean'],
            ];
        }

        return isset($result) ? $result : [];

    }

    /** @inheritdoc */
    public function beforeSave($insert) {

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

            $this->status = static::STATUS_UN_CONFIRMED;

            if (!$this->save()) {
                $transaction->rollBack();
                return false;
            }

            if ($this->module->enableConfirmationEmail) {
                /** @var Token $token */
                $token = \Yii::createObject(['class' => Token::className(), 'type' => Token::TYPE_CONFIRMATION]);
                $token->link('user', $this);
            }

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
     * Creates new user account. It generates password if it is not provided by user.
     *
     * @return bool
     */
    public function create() {

        if ($this->getIsNewRecord() == false) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        $transaction = $this->getDb()->beginTransaction();

        try {
            $this->password = $this->password == null ? Password::generate(8) : $this->password;

            $this->trigger(self::BEFORE_CREATE);

            if (!$this->save()) {
                $transaction->rollBack();
                return false;
            }

            $this->confirm();

            $this->mailer->sendWelcomeMessage($this, null, true);
            $this->trigger(self::AFTER_CREATE);

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

}