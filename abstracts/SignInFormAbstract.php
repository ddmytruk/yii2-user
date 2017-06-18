<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 16.06.17
 * Time: 12:55
 */

namespace ddmytruk\user\abstracts;

use ddmytruk\user\interfaces\FormInterface;
use yii\base\Model;

abstract class SignInFormAbstract extends Model implements FormInterface {

    /**
     * @var string User's email or username
     */
    public $login;

    /**
     * @var string User's plain password
     */
    public $password;

    /**
     * @var string Whether to remember the user
     */
    public $rememberMe = false;

    public function getViewPath() {
        return false;
    }

}