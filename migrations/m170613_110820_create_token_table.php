<?php

use yii\db\Migration;

/**
 * Handles the creation of table `token`.
 */
class m170613_110820_create_token_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('token', [
            'user_id'    => $this->integer()->notNull(),
            'code'       => $this->string(32)->notNull(),
            'type'       => $this->smallInteger()->notNull(),
            'created_at'           => $this->dateTime()->notNull(),
        ]);

        $this->createIndex('{{%token_unique}}', '{{%token}}', ['user_id', 'code', 'type'], true);
        $this->addForeignKey('{{%fk_user__token}}', '{{%token}}', 'user_id', '{{%user}}', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('{{%fk_user__token}}', '{{%token}}');
        $this->dropIndex('{{%token_unique}}', '{{%token}}');

        $this->dropTable('token');
    }
}
