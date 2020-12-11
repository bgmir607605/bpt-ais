<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%semestrMark}}`.
 */
class m201211_100450_create_semestrMark_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%semestrMark}}', [
            'id' => $this->primaryKey(),
            'semestrId' => $this->integer(),
            'studentId' => $this->integer(),
            'value' => $this->integer().' DEFAULT NULL',
            'deleted' => $this->integer().' NOT NULL DEFAULT 0',
        ]);
        
        $this->createIndex(
            'idx-semestrMark-semestrId',
            'semestrMark',
            'semestrId'
        );

        $this->addForeignKey(
            'fk-semestrMark-semestrId',
            'semestrMark',
            'semestrId',
            'semestr',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-semestrMark-studentId',
            'semestrMark',
            'studentId'
        );

        $this->addForeignKey(
            'fk-semestrMark-studentId',
            'semestrMark',
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
        $this->dropTable('{{%semestrMark}}');
    }
}
