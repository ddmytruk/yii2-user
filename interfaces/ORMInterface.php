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
     * @param string $scenario the scenario that this model is in.
     */
    public static function rulesForForm($className, $scenario);

    /**
     * @inheritdoc
     */
    public static function getScenarios();

}