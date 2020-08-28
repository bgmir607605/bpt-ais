<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%teacherload}}`.
 */
class m200827_135812_create_teacherload_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('teacherload', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer(),
            'groupId' => $this->integer(),
            'disciplineId' => $this->integer(),
            'total' => $this->integer().' NOT NULL DEFAULT 0',
            'fSub' => $this->integer().' NOT NULL DEFAULT 0',
            'sSub' => $this->integer().' NOT NULL DEFAULT 0',
            'cons' => $this->integer().' NOT NULL DEFAULT 0',
            'fSubKP' => $this->integer().' NOT NULL DEFAULT 0',
            'sSubKP' => $this->integer().' NOT NULL DEFAULT 0',
            'sr' => $this->integer().' NOT NULL DEFAULT 0',
            'exam' => $this->integer().' NOT NULL DEFAULT 0',
            'deleted' => $this->integer().' NOT NULL DEFAULT 0',
        ]);

        $this->createIndex(
            'idx-teacherload-userId',
            'teacherload',
            'userId'
        );
        $this->createIndex(
            'idx-teacherload-groupId',
            'teacherload',
            'groupId'
        );
        $this->createIndex(
            'idx-teacherload-disciplineId',
            'teacherload',
            'disciplineId'
        );

        $this->addForeignKey(
            'fk-teacherload-userId',
            'teacherload',
            'userId',
            'user',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-teacherload-groupId',
            'teacherload',
            'groupId',
            'group',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-teacherload-disciplineId',
            'teacherload',
            'disciplineId',
            'discipline',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('teacherload');
    }
}
