<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%group}}`.
 */
class m200827_134139_create_group_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('group', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'directId' => $this->integer(),
            'course' => "SET('1', '2', '3', '4') NOT NULL",
        ]);

        // creates index for column `directId`
        $this->createIndex(
            'idx-group-directId',
            'group',
            'directId'
        );

        // add foreign key for table `direct`
        $this->addForeignKey(
            'fk-group-directId',
            'group',
            'directId',
            'direct',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('group');
    }
}
