<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%mark}}`.
 */
class m200914_124343_create_mark_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('mark', [
            'id' => $this->primaryKey(),
            'scheduleId' => $this->integer(),
            'studentId' => $this->integer(),
            'value' => $this->integer().' DEFAULT NULL',
            'deleted' => $this->integer().' NOT NULL DEFAULT 0',
        ]);

        $this->createIndex(
            'idx-mark-scheduleId',
            'mark',
            'scheduleId'
        );

        $this->addForeignKey(
            'fk-mark-scheduleId',
            'mark',
            'scheduleId',
            'schedule',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-mark-studentId',
            'mark',
            'studentId'
        );

        $this->addForeignKey(
            'fk-mark-studentId',
            'mark',
            'studentId',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('mark');
    }
}
