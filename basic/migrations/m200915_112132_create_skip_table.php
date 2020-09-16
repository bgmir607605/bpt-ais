<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%skip}}`.
 */
class m200915_112132_create_skip_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('skip', [
            'id' => $this->primaryKey(),
            'scheduleId' => $this->integer(),
            'studentId' => $this->integer(),
            'deleted' => $this->integer().' NOT NULL DEFAULT 0',
        ]);

        $this->createIndex(
            'idx-skip-scheduleId',
            'skip',
            'scheduleId'
        );

        $this->addForeignKey(
            'fk-skip-scheduleId',
            'skip',
            'scheduleId',
            'schedule',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-skip-studentId',
            'skip',
            'studentId'
        );

        $this->addForeignKey(
            'fk-skip-studentId',
            'skip',
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
        $this->dropTable('skip');
    }
}
