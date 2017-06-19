<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 12.06.17
 * Time: 17:50
 */

namespace ddmytruk\user;


use ddmytruk\user\abstracts\UserAbstract;
use ddmytruk\user\models\orm\Token;
use ddmytruk\user\models\orm\User;
use ddmytruk\user\traits\ModuleTrait;
use yii\base\Object;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class Finder extends Object
{

    use ModuleTrait;

    /**
     * @var ActiveQuery
     */
    protected $userQuery;

    /**
     * @var ActiveQuery
     */
    protected $tokenQuery;

    /**
     * @return ActiveQuery
     */
    public function getUserQuery()
    {
        return $this->userQuery;
    }

    /**
     * @return ActiveQuery
     */
    public function getTokenQuery()
    {
        return $this->tokenQuery;
    }

    /**
     * @param ActiveQuery $userQuery
     */
    public function setUserQuery(ActiveQuery $userQuery)
    {
        $this->userQuery = $userQuery;
    }

    /**
     * @param ActiveQuery $tokenQuery
     */
    public function setTokenQuery(ActiveQuery $tokenQuery)
    {
        $this->tokenQuery = $tokenQuery;
    }

    /**
     * Finds a token by params.
     *
     * @param integer $userId
     * @param string  $code
     * @param integer $type
     *
     * @return Token|array|null
     */
    public function findTokenByParams($userId, $code, $type)
    {
        return $this->findToken([
            'user_id' => $userId,
            'code'    => $code,
            'type'    => $type,
        ])->one();
    }

    /**
     * Finds a token by user id and code.
     *
     * @param mixed $condition
     *
     * @return ActiveQuery
     */
    public function findToken($condition)
    {
        return $this->tokenQuery->where($condition);
    }

    /**
     * Finds a user by the given id.
     *
     * @param int $id User id to be used on search.
     *
     * @return ActiveRecord|array|null
     */
    public function findUserById($id)
    {
        return $this->findUser(['id' => $id])->one();
    }

    /**
     * Finds a user by the given username.
     *
     * @param string $username Username to be used on search.
     *
     * @return abstracts\UserAbstract|ActiveRecord
     */
    public function findUserByUsername($username)
    {
        return $this->findUser(['username' => $username])->one();
    }

    /**
     * Finds a user by the given email.
     *
     * @param string $email Email to be used on search.
     *
     * @return abstracts\UserAbstract|ActiveRecord
     */
    public function findUserByEmail($email)
    {
        return $this->findUser(['email' => $email])->one();
    }

    /**
     * Finds a user by the given email.
     *
     * @param string $phone Phone to be used on search.
     *
     * @return abstracts\UserAbstract|ActiveRecord
     */
    public function findUserByPhone($phone)
    {
        return $this->findUser(['phone' => $phone])->one();
    }

    /**
     * Finds a user by the given username or email.
     *
     * @param string $usernameOrEmail Username or email to be used on search.
     *
     * @return ['data' => abstracts\UserAbstract, 'loginType' => const SIGN_IN_]
     */

    public function findUserByLogin($login, $scenarioConfig)
    {

        /** @var $user UserAbstract */
        $user = $this->module->modelMap['User'];

        if (in_array($user::SIGN_IN_EMAIL, $scenarioConfig) && filter_var($login, FILTER_VALIDATE_EMAIL)) {
            return [
                'user' => $this->findUserByEmail($login),
                'loginType' => $user::SIGN_IN_EMAIL
            ];
        } elseif (in_array($user::SIGN_IN_USERNAME, $scenarioConfig) && $this->isUserName($login)) {
            return [
                'user' => $this->findUserByUsername($login),
                'loginType' => $user::SIGN_IN_USERNAME
            ];
        } elseif (in_array($user::SIGN_IN_PHONE, $scenarioConfig) &&  $this->isPhone($login)) {
            return [
                'user' => $this->findUserByPhone($login),
                'loginType' => $user::SIGN_IN_PHONE
            ];
        } else {
            return null;
        }
    }

    private function isUserName($login) {
        preg_match(UserAbstract::$usernameRegexp, $login, $matches, PREG_OFFSET_CAPTURE);
        if(count($matches))
            return true;
        else
            return false;
    }

    private function isPhone($login) {
        preg_match(UserAbstract::$phoneRegexp, $login, $matches, PREG_OFFSET_CAPTURE);
        if(count($matches))
            return true;
        else
            return false;
    }


    /**
     * Finds a user by the given condition.
     *
     * @param mixed $condition Condition to be used on search.
     *
     * @return \yii\db\ActiveQuery
     */
    public function findUser($condition)
    {
        return $this->userQuery->where($condition);
    }

}