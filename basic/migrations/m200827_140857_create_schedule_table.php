<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%schedule}}`.
 */
class m200827_140857_create_schedule_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('schedule', [
            'id' => $this->primaryKey(),
            'date' => $this->date(),
            'number' => $this->integer(),
            'type' => "SET('', 'I', 'II') NOT NULL",
            'teacherLoadId' => $this->integer(),
            'cons' => $this->boolean()->notNull(). ' default 0',
            'forTeach' => $this->boolean(). ' NOT NULL default 0',
            'hours' => $this->integer().' NOT NULL DEFAULT 2',
            'kp' => $this->integer().' NOT NULL DEFAULT 0',
            'sr' => $this->integer().' NOT NULL DEFAULT 0',
            'replaceTeacherId' => $this->integer().' DEFAULT NULL',
            'deleted' => $this->integer().' NOT NULL DEFAULT 0',
        ]);

        // creates index for column `specialtyId`
        $this->createIndex(
            'idx-schedule-teacherLoadId',
            'schedule',
            'teacherLoadId'
        );

        // add foreign key for table `specialty`
        $this->addForeignKey(
            'fk-schedule-teacherLoadId',
            'schedule',
            'teacherLoadId',
            'teacherload',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-schedule-replaceTeacherId',
            'schedule',
            'replaceTeacherId'
        );

        // add foreign key for table `specialty`
        $this->addForeignKey(
            'fk-schedule-replaceTeacherId',
            'schedule',
            'replaceTeacherId',
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
        $this->dropTable('schedule');
    }
}
