<?php

use yii\db\Migration;

class m170616_090857_add_column_in_user extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'status', $this->integer(6)->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'status');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170616_090857_add_column_in_user cannot be reverted.\n";

        return false;
    }
    */
}
