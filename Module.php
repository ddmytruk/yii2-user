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
    public $signUpScenarioConfig = [
        UserAbstract::SIGN_UP_EMAIL,
        UserAbstract::SIGN_UP_PHONE,
        UserAbstract::SIGN_UP_USERNAME
    ];

    /** Default sign in scenario for set required email */
    public $signInScenarioConfig = [
        #UserAbstract::SCENARIO_SIGN_IN_EMAIL,
    ];

    /** @var int Cost parameter used by the Blowfish hash algorithm. */
    public $cost = 10;

    /** @var int The time before a confirmation token becomes invalid. */
    public $confirmWithin = 86400; // 24 hours

    /** @var int The time you want the user will be remembered without asking for credentials. */
    public $rememberFor = 1209600; // two weeks

    /** @var array Model map */
    public $modelMap = [];

    /** @var array Mailer configuration */
    public $mailer = [];

    /**  */
    public $viewPaths = [];
}