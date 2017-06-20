<?php


namespace ddmytruk\user\events;

use ddmytruk\user\abstracts\RecoveryFormAbstract;
use ddmytruk\user\models\orm\Token;
use yii\base\Event;

/**
 * @property Token       $token
 * @property RecoveryFormAbstract $form
 */
class ResetPasswordEvent extends Event
{
    /**
     * @var RecoveryFormAbstract
     */
    private $_form;

    /**
     * @var Token
     */
    private $_token;

    /**
     * @return Token
     */
    public function getToken()
    {
        return $this->_token;
    }

    /**
     * @param Token $token
     */
    public function setToken(Token $token = null)
    {
        $this->_token = $token;
    }

    /**
     * @return RecoveryFormAbstract
     */
    public function getForm()
    {
        return $this->_form;
    }

    /**
     * @param RecoveryFormAbstract $form
     */
    public function setForm(RecoveryFormAbstract $form = null)
    {
        $this->_form = $form;
    }
}
