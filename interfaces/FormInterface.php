<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 15.06.17
 * Time: 11:30
 */

namespace ddmytruk\user\interfaces;


interface FormInterface {

    /**
     * @inheritdoc
     */
    public function rules();

    /**
     * Registers a new user account.
     *
     * @return bool
     */
    public function perform();

}