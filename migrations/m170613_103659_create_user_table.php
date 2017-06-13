<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m170613_103659_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string(25)->notNull(),
            'email' => $this->string(255)->notNull(),
            'password_hash'        => $this->string(60)->notNull(),
            'auth_key'             => $this->string(32)->notNull(),
            'sing_up_ip'           => $this->string(45)->notNull(),
            'role'                 => $this->integer()->defaultValue(null),
            'last_sign_in'         => $this->dateTime()->notNull(),
            'created_at'           => $this->dateTime()->notNull(),
            'updated_at'           => $this->dateTime()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user');
    }
}
