<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%teacherloadInSemestr}}`.
 */
class m201211_095921_create_teacherloadInAttestation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%teacherloadInAttestation}}', [
            'id' => $this->primaryKey(),
            'attestationId' => $this->integer(),
            'teacherloadId' => $this->integer(),
            'deleted' => $this->integer().' NOT NULL DEFAULT 0',
        ]);
        
        $this->createIndex(
            'idx-teacherloadInAttestation-attestationId',
            'teacherloadInAttestation',
            'attestationId'
        );
        $this->createIndex(
            'idx-teacherloadInAttestation-teacherloadId',
            'teacherloadInAttestation',
            'teacherloadId'
        );

        $this->addForeignKey(
            'fk-teacherloadInAttestation-attestationId',
            'teacherloadInAttestation',
            'attestationId',
            'attestation',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-teacherloadInAttestation-teacherloadId',
            'teacherloadInAttestation',
            'teacherloadId',
            'teacherload',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%teacherloadInAttestation}}');
    }
}
