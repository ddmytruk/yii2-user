<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 13.06.17
 * Time: 11:51
 */

namespace ddmytruk\user\models\form;


use ddmytruk\user\abstracts\SignUpFormAbstract;

class SignUpForm extends SignUpFormAbstract
{

    public function rules() {

        return [
            // username rules
            'usernameLength'   => ['username', 'string', 'min' => 3, 'max' => 255],
            'usernameTrim'     => ['username', 'filter', 'filter' => 'trim'],
            'usernameRequired' => ['username', 'required'],
            'usernameUnique'   => [
                'username',
                'unique'
            ],
        ];

    }

}