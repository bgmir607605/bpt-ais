<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%monitoringMark}}`.
 */
class m201025_134231_create_monitoringMark_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%monitoringMark}}', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer(),
            'teacherLoadId' => $this->integer(),
            'monitoringId' => $this->integer(),
            'mark' => $this->string(),
        ]);

        // индексы и связи
        $this->createIndex(
            'idx-monitoringMark-userId',
            'monitoringMark',
            'userId'
        );

        $this->addForeignKey(
            'fk-monitoringMark-userId',
            'monitoringMark',
            'userId',
            'user',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-monitoringMark-teacherLoadId',
            'monitoringMark',
            'teacherLoadId'
        );

        $this->addForeignKey(
            'fk-monitoringMark-teacherLoadId',
            'monitoringMark',
            'teacherLoadId',
            'teacherload',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-monitoringMark-monitoringId',
            'monitoringMark',
            'monitoringId'
        );

        $this->addForeignKey(
            'fk-monitoringMark-monitoringId',
            'monitoringMark',
            'monitoringId',
            'monitoring',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%monitoringMark}}');
    }
}
