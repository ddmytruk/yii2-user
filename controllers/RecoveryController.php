<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 19.06.17
 * Time: 17:52
 */

namespace ddmytruk\user\controllers;

use ddmytruk\traits\AjaxValidationTrait;
use ddmytruk\user\traits\EventTrait;

use ddmytruk\user\components\CommonController;
/**
 * @property \ddmytruk\user\Module $module
 */

class RecoveryController extends CommonController {

    use AjaxValidationTrait;
    use EventTrait;



}