<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 15.06.17
 * Time: 11:38
 */

namespace ddmytruk\user\interfaces;

interface ORMInterface
{

    /**
     * @inheritdoc
     */
    public static function rulesForForm();

    /**
     * @inheritdoc
     */
    public static function getScenarios();

}