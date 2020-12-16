<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%semestrMark}}`.
 */
class m201211_100450_create_attestationMark_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%attestationMark}}', [
            'id' => $this->primaryKey(),
            'attestationId' => $this->integer(),
            'studentId' => $this->integer(),
            'value' => $this->integer().' DEFAULT NULL',
            'deleted' => $this->integer().' NOT NULL DEFAULT 0',
        ]);
        
        $this->createIndex(
            'idx-attestationMark-attestationId',
            'attestationMark',
            'attestationId'
        );

        $this->addForeignKey(
            'fk-attestationMark-attestationId',
            'attestationMark',
            'attestationId',
            'attestation',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-attestationMark-studentId',
            'attestationMark',
            'studentId'
        );

        $this->addForeignKey(
            'fk-attestationMark-studentId',
            'attestationMark',
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
        $this->dropTable('{{%attestationMark}}');
    }
}
