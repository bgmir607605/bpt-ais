<?php

use yii\db\Migration;

/**
 * Class m200829_095555_alter_schedule_table
 */
class m200829_095555_alter_schedule_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('schedule', 'type', "SET('', 'I', 'II') default ''",);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('schedule', 'type', "SET('', 'I', 'II') NOT NULL",);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200829_095555_alter_schedule_table cannot be reverted.\n";

        return false;
    }
    */
}
