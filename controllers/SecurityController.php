<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 12.06.17
 * Time: 15:09
 */

namespace ddmytruk\user\controllers;

use ddmytruk\user\abstracts\SignUpFormAbstract;
use ddmytruk\user\models\form\SignUpForm;
use Yii;

use ddmytruk\user\components\CommonController;

class SecurityController extends CommonController
{
    public function actionSignIn() {

        $this->layout = false;
        /** @var $model SignUpFormAbstract */
        $model = $this->di->getSignUpForm();

        return $this->render('sign-up', [

        ]);

    }
}