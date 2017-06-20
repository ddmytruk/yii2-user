<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 20.06.17
 * Time: 14:10
 */

namespace ddmytruk\user\models\form;


use ddmytruk\user\abstracts\RecoveryFormAbstract;
use ddmytruk\user\abstracts\UserAbstract;
use ddmytruk\user\models\orm\Token;

class RecoveryForm extends RecoveryFormAbstract
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email'    => \Yii::t('user', 'Email'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'emailTrim' => ['email', 'filter', 'filter' => 'trim'],
            'emailRequired' => ['email', 'required'],
            'emailPattern' => ['email', 'email'],
        ];
    }

    /**
     * Registers a new user account.
     *
     * @return bool
     */
    public function perform() {

        if (!$this->validate()) {
            return false;
        }

        $user = $this->finder->findUserByEmail($this->email);

        if ($user instanceof UserAbstract) {
            /** @var Token $token */
            $token = \Yii::createObject([
                'class' => Token::className(),
                'user_id' => $user->id,
                'type' => Token::TYPE_RECOVERY,
            ]);

            if (!$token->save(false)) {
                return false;
            }

            if (!$this->mailer->sendRecoveryMessage($user, $token)) {
                return false;
            }
        }

        return true;

    }
}