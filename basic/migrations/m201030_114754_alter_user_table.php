<?php

use yii\db\Migration;

/**
 * Class m201030_114754_alter_user_table
 */
class m201030_114754_alter_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'lastDateTime', $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'lastDateTime');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201030_114754_alter_user_table cannot be reverted.\n";

        return false;
    }
    */
}
