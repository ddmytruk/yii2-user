<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 19.06.17
 * Time: 12:26
 */

namespace ddmytruk\user\models\form;


use ddmytruk\user\abstracts\ResendFormAbstract;
use ddmytruk\user\abstracts\UserAbstract;
use ddmytruk\user\models\orm\Token;

class ResendForm extends ResendFormAbstract
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'emailRequired' => ['email', 'required'],
            'emailPattern' => ['email', 'email'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Email',
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

        if ($user instanceof UserAbstract && !$user->isConfirmed) {
            /** @var Token $token */
            $token = \Yii::createObject([
                'class' => Token::className(),
                'user_id' => $user->id,
                'type' => Token::TYPE_CONFIRMATION,
            ]);
            $token->save(false);
            $this->mailer->sendConfirmationMessage($user, $token);
        }

        return true;

    }
}