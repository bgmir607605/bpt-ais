<?php

use yii\db\Migration;

/**
 * Class m200910_125416_alter_schedule_table
 */
class m200910_125416_alter_schedule_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('schedule', 'cabinetId', $this->integer().' default NULL');
        $this->createIndex(
            'idx-schedule-cabinetId',
            'schedule',
            'cabinetId'
        );

        // add foreign key for table `specialty`
        $this->addForeignKey(
            'fk-schedule-cabinetId',
            'schedule',
            'cabinetId',
            'cabinet',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('schedule', 'cabinetId');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200910_125416_alter_schedule_table cannot be reverted.\n";

        return false;
    }
    */
}
