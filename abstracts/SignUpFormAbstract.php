<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 12.06.17
 * Time: 17:49
 */

namespace ddmytruk\user\abstracts;


use ddmytruk\user\interfaces\FormInterface;
use yii\base\Model;

abstract class SignUpFormAbstract extends Model implements FormInterface {

    /**
     * @var string User email address
     */
    public $email;

    /**
     * @var string Username
     */
    public $username;

    /**
     * @var string Phone
     */
    public $phone;

    /**
     * @var string Password
     */
    public $password;

    public function getViewPath() {
        return false;
    }

}