<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 19.06.17
 * Time: 21:53
 */

namespace ddmytruk\user\models\orm;

use ddmytruk\user\abstracts\UserAbstract;
use ddmytruk\user\interfaces\ClientInterface;
use ddmytruk\user\models\query\AccountQuery;
use yii\authclient\ClientInterface as BaseClientInterface;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use ddmytruk\user\traits\ModuleTrait;
use ddmytruk\user\Finder;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;


/**
 * Class SocialAccount
 * @package ddmytruk\user\models\orm
 */

class SocialAccount extends ActiveRecord
{

    use ModuleTrait;

    /**
     * @var Finder
     */
    protected static $finder;

    /**
     * @var
     */
    private $_data;

    /**
     * @return ActiveQuery
     */
    public function getUser() {
        return $this->hasOne($this->module->modelMap['User'], ['id' => 'user_id']);
    }

    /**
     * @return bool Whether this social account is connected to user.
     */
    public function getIsConnected() {
        return $this->user_id != null;
    }

    /**
     * @return mixed Json decoded properties.
     */
    public function getDecodedData() {

        if ($this->_data == null) {
            $this->_data = Json::decode($this->data);
        }

        return $this->_data;
    }

    /**
     * Returns connect url.
     * @return string
     */
    public function getConnectUrl()
    {
        $code = \Yii::$app->security->generateRandomString();
        $this->updateAttributes(['code' => md5($code)]);

        return Url::to(['/user/security/sign-up-connect', 'code' => $code]);
    }

    public function connect(User $user)
    {
        return $this->updateAttributes([
            'username' => null,
            'email'    => null,
            'code'     => null,
            'user_id'  => $user->id,
        ]);
    }

    /**
     * @return object the created object (AccountQuery)
     */
    public static function find()
    {
        return \Yii::createObject(AccountQuery::className(), [get_called_class()]);
    }

    public static function create(BaseClientInterface $client)
    {
        /** @var SocialAccount $account */
        $account = \Yii::createObject([
            'class'      => static::className(),
            'provider'   => $client->getId(),
            'client_id'  => $client->getUserAttributes()['id'],
            'data'       => Json::encode($client->getUserAttributes()),
        ]);

        if ($client instanceof ClientInterface) {
            $account->setAttributes([
                'username' => $client->getUsername(),
                'email'    => $client->getEmail(),
            ], false);
        }

        if (($user = static::fetchUser($account)) instanceof User) {
            $account->user_id = $user->id;
        }

        $account->save(false);

        return $account;
    }

    /**
     * Tries to find an account and then connect that account with current user.
     *
     * @param BaseClientInterface $client
     */
    public static function connectWithUser(BaseClientInterface $client)
    {
        if (\Yii::$app->user->isGuest) {
            \Yii::$app->session->setFlash('danger', \Yii::t('user', 'Something went wrong'));

            return;
        }

        $account = static::fetchAccount($client);

        if ($account->user === null) {
            $account->link('user', \Yii::$app->user->identity);
            \Yii::$app->session->setFlash('success', \Yii::t('user', 'Your account has been connected'));
        } else {
            \Yii::$app->session->setFlash(
                'danger',
                \Yii::t('user', 'This account has already been connected to another user')
            );
        }
    }

    /**
     * Tries to find account, otherwise creates new account.
     *
     * @param BaseClientInterface $client
     *
     * @return SocialAccount
     * @throws \yii\base\InvalidConfigException
     */
    protected static function fetchAccount(BaseClientInterface $client)
    {
        $account = static::getFinder()->findAccount()->byClient($client)->one();

        if (null === $account) {
            $account = \Yii::createObject([
                'class'      => static::className(),
                'provider'   => $client->getId(),
                'client_id'  => $client->getUserAttributes()['id'],
                'data'       => Json::encode($client->getUserAttributes()),
            ]);
            $account->save(false);
        }

        return $account;
    }

    /**
     * Tries to find user or create a new one.
     *
     * @param SocialAccount $account
     *
     * @return UserAbstract|bool False when can't create user.
     */
    protected static function fetchUser(SocialAccount $account)
    {
        $user = static::getFinder()->findUserByEmail($account->email);

        if (null !== $user) {
            return $user;
        }

        /** @var UserAbstract $user */
        $user = \Yii::createObject([
            'class'    => \Yii::$app->getModule('user')->modelMap['User'],
            'scenario' => 'connect',
            'username' => $account->username,
            'email'    => $account->email,
        ]);

        if (!$user->validate(['email'])) {
            $account->email = null;
        }

        if (!$user->validate(['username'])) {
            $account->username = null;
        }

        return $user->create() ? $user : false;
    }

    /**
     * @return Finder
     */
    protected static function getFinder()
    {
        if (static::$finder === null) {
            static::$finder = \Yii::$container->get(Finder::className());
        }

        return static::$finder;
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
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%social_account}}';
    }

}