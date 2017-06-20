<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 13.06.17
 * Time: 17:26
 */

namespace ddmytruk\user\models\orm;

use ddmytruk\user\traits\ModuleTrait;
use yii\helpers\Url;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;


/**
 * This is the model class for table "token".
 *
 * @property integer $user_id
 * @property string $code
 * @property integer $type
 * @property string $created_at
 *
 * @property User $user
 */

class Token extends \yii\db\ActiveRecord
{
    use ModuleTrait;

    const TYPE_CONFIRMATION      = 0;
    const TYPE_RECOVERY          = 1;

    /** @inheritdoc */
    public function beforeSave($insert)
    {
        if ($insert) {
            static::deleteAll(['user_id' => $this->user_id, 'type' => $this->type]);
            $this->setAttribute('created_at', time());
            $this->setAttribute('code', \Yii::$app->security->generateRandomString());
        }

        return parent::beforeSave($insert);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        switch ($this->type) {
            case self::TYPE_CONFIRMATION:
                $route = '/user/security/confirm';
                break;
            case self::TYPE_RECOVERY:
                $route = '/user/recovery/reset';
                break;
            default:
                throw new \RuntimeException();
        }

        return Url::to([$route, 'id' => $this->user_id, 'code' => $this->code], true);
    }

    /**
     * @return bool Whether token has expired.
     */
    public function getIsExpired()
    {
        switch ($this->type) {
            case self::TYPE_CONFIRMATION:
                $expirationTime = 'PT'.$this->module->confirmWithin.'S';
                break;
            case self::TYPE_RECOVERY:
                $expirationTime = 'PT'.$this->module->recoverWithin.'S';
                break;
            default:
                throw new \RuntimeException();
        }

        $liveTime = new \DateTime($this->created_at);
        $liveTime->add(new \DateInterval($expirationTime));
        $now = new \DateTime();

        return $liveTime < $now;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%token}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne($this->module->modelMap['User'], ['id' => 'user_id']);
    }

    /** @inheritdoc */
    public static function primaryKey()
    {
        return ['user_id', 'code', 'type'];
    }

}