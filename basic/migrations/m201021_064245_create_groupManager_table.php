<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%groupManager}}`.
 */
class m201021_064245_create_groupManager_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('groupManager', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer(),
            'groupId' => $this->integer(),
            'deleted' => $this->integer().' NOT NULL DEFAULT 0',
        ]);

        $this->createIndex(
            'idx-groupManager-userId',
            'groupManager',
            'userId'
        );
        $this->createIndex(
            'idx-groupManager-groupId',
            'groupManager',
            'groupId'
        );

        $this->addForeignKey(
            'fk-groupManager-userId',
            'groupManager',
            'userId',
            'user',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-groupManager-groupId',
            'groupManager',
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
        $this->dropTable('groupManager');
    }
}
