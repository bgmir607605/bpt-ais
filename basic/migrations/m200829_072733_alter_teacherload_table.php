<?php

use yii\db\Migration;

/**
 * Class m200829_072733_alter_teacherload_table
 */
class m200829_072733_alter_teacherload_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('teacherload', 'total', $this->integer().' DEFAULT 0');
        $this->alterColumn('teacherload', 'fSub', $this->integer().' DEFAULT 0');
        $this->alterColumn('teacherload', 'sSub', $this->integer().' DEFAULT 0');
        $this->alterColumn('teacherload', 'cons', $this->integer().' DEFAULT 0');
        $this->alterColumn('teacherload', 'fSubKP', $this->integer().' DEFAULT 0');
        $this->alterColumn('teacherload', 'sSubKP', $this->integer().' DEFAULT 0');
        $this->alterColumn('teacherload', 'sr', $this->integer().' DEFAULT 0');
        $this->alterColumn('teacherload', 'exam', $this->integer().' DEFAULT 0');
        $this->alterColumn('teacherload', 'deleted', $this->integer().' DEFAULT 0');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('teacherload', 'total', $this->integer().' NOT NULL DEFAULT 0');
        $this->alterColumn('teacherload', 'fSub', $this->integer().' NOT NULL DEFAULT 0');
        $this->alterColumn('teacherload', 'sSub', $this->integer().' NOT NULL DEFAULT 0');
        $this->alterColumn('teacherload', 'cons', $this->integer().' NOT NULL DEFAULT 0');
        $this->alterColumn('teacherload', 'fSubKP', $this->integer().' NOT NULL DEFAULT 0');
        $this->alterColumn('teacherload', 'sSubKP', $this->integer().' NOT NULL DEFAULT 0');
        $this->alterColumn('teacherload', 'sr', $this->integer().' NOT NULL DEFAULT 0');
        $this->alterColumn('teacherload', 'exam', $this->integer().' NOT NULL DEFAULT 0');
        $this->alterColumn('teacherload', 'deleted', $this->integer().' NOT NULL DEFAULT 0');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200829_072733_alter_teacherload_table cannot be reverted.\n";

        return false;
    }
    */
}
