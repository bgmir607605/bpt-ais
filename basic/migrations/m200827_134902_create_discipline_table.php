<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%discipline}}`.
 */
class m200827_134902_create_discipline_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('discipline', [
            'id' => $this->primaryKey(),
            'shortName' => $this->string(),
            'fullName' => $this->string(),
            'directId' => $this->integer().' DEFAULT NULL',
            'deleted' => $this->integer().' NOT NULL DEFAULT 0',
        ]);

        // creates index for column `directId`
        $this->createIndex(
            'idx-discipline-directId',
            'discipline',
            'directId'
        );

        // add foreign key for table `direct`
        $this->addForeignKey(
            'fk-discipline-directId',
            'discipline',
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
        $this->dropTable('discipline');
    }
}
