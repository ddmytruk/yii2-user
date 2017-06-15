<?php

/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 12.06.17
 * Time: 14:40
 */

namespace ddmytruk\user;

use ddmytruk\user\abstracts\UserAbstract;
use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    const VERSION = '0.0.0'; /* На донышке */

    /** Default sign up scenario for set required username and email */
    public $signUpScenario = UserAbstract::SCENARIO_SIGN_UP_EMAIL_AND_USERNAME;

    /** @var int Cost parameter used by the Blowfish hash algorithm. */
    public $cost = 10;

    /** @var array Model map */
    public $modelMap = [];

    /** @var array Mailer configuration */
    public $mailer = [];

    /**  */
    public $viewPaths = [];
}