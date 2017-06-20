<?php

/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 12.06.17
 * Time: 14:40
 */

namespace ddmytruk\user;

use ddmytruk\user\abstracts\UserAbstract;
use ddmytruk\user\models\orm\User;
use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    const VERSION = '0.0.0'; /* На донышке */

    /** Default sign up scenario config required username, email, phone */
    public $signUpScenarioConfig = [
        UserAbstract::SIGN_UP_EMAIL,
        UserAbstract::SIGN_UP_PHONE,
        UserAbstract::SIGN_UP_USERNAME
    ];

    /** Default sign in scenario for set required email */
    public $signInScenarioConfig = [
        UserAbstract::SIGN_IN_EMAIL,
        UserAbstract::SIGN_IN_USERNAME,
        UserAbstract::SIGN_IN_PHONE
    ];

    /** @var bool Whether user has to confirm email his account. */
    public $enableConfirmationEmail = true;

    /** @var bool Whether user has to confirm phone his account. */
    public $enableConfirmationPhone = true;

    /** @var bool Whether to allow logging in without confirmation. */
    public $enableUnconfirmedLogin = false;

    /** @var int Cost parameter used by the Blowfish hash algorithm. */
    public $cost = 10;

    /** @var int The time before a confirmation token becomes invalid. */
    public $confirmWithin = 86400; // 24 hours

    /** @var int The time before a recovery token becomes invalid. */
    public $recoverWithin = 21600; // 6 hours

    /** @var int The time you want the user will be remembered without asking for credentials. */
    public $rememberFor = 1209600; // two weeks

    /** @var array Model map */
    public $modelMap = [];

    /** @var array Mailer configuration */
    public $mailer = [];

    /**  */
    public $viewPaths = [];
}