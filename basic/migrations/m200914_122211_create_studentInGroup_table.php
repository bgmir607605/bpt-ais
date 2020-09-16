<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%studentInGroup}}`.
 */
class m200914_122211_create_studentInGroup_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('studentInGroup', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer(),
            'groupId' => $this->integer(),
            'deleted' => $this->integer().' NOT NULL DEFAULT 0',
        ]);

        $this->createIndex(
            'idx-studentInGroup-userId',
            'studentInGroup',
            'userId'
        );
        $this->createIndex(
            'idx-studentInGroup-groupId',
            'studentInGroup',
            'groupId'
        );

        $this->addForeignKey(
            'fk-studentInGroup-userId',
            'studentInGroup',
            'userId',
            'user',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-studentInGroup-groupId',
            'studentInGroup',
            'groupId',
            'group',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('studentInGroup');
    }
}
