<?php

use yii\db\Migration;

class m170616_141207_add_column_in_user extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'phone', $this->string(20)->unique());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'phone');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170616_141207_add_column_in_user cannot be reverted.\n";

        return false;
    }
    */
}
