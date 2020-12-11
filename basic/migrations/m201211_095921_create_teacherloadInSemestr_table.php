<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%teacherloadInSemestr}}`.
 */
class m201211_095921_create_teacherloadInSemestr_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%teacherloadInSemestr}}', [
            'id' => $this->primaryKey(),
            'semestrId' => $this->integer(),
            'teacherloadId' => $this->integer(),
            'deleted' => $this->integer().' NOT NULL DEFAULT 0',
        ]);
        
        $this->createIndex(
            'idx-teacherloadInSemestr-semestrId',
            'teacherloadInSemestr',
            'semestrId'
        );
        $this->createIndex(
            'idx-teacherloadInSemestr-teacherloadId',
            'teacherloadInSemestr',
            'teacherloadId'
        );

        $this->addForeignKey(
            'fk-teacherloadInSemestr-semestrId',
            'teacherloadInSemestr',
            'semestrId',
            'semestr',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-teacherloadInSemestr-teacherloadId',
            'teacherloadInSemestr',
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
        $this->dropTable('{{%teacherloadInSemestr}}');
    }
}
