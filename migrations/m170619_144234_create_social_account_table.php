<?php

use yii\db\Migration;

/**
 * Handles the creation of table `social_account`.
 */
class m170619_144234_create_social_account_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('social_account', [
            'id' => $this->primaryKey(),
            'user_id'    => $this->integer()->null(),
            'provider'   => $this->string()->notNull(),
            'client_id'  => $this->string()->notNull(),
            'data' => $this->text()->null(),
            'code' => $this->string(32)->null(),
            'email' => $this->string()->null(),
            'username' => $this->string()->null(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);

        $this->createIndex('{{%account_unique}}', '{{%social_account}}', ['provider', 'client_id', 'code'], true);

        $this->addForeignKey('{{%fk_user_account}}', '{{%social_account}}', 'user_id', '{{%user}}', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('{{%fk_user_account}}', '{{%social_account}}');

        $this->dropIndex('{{%account_unique}}', '{{%social_account}}');

        $this->dropTable('social_account');
    }
}
