<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%semestr}}`.
 */
class m201211_095428_create_attestation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%attestation}}', [
            'id' => $this->primaryKey(),
            'date' => $this->date(),
            'type' => "SET('ДКР', 'Экзамен', 'Квалификационный экзамен', 'Дифференцированный зачёт')",
            'semestrNumber' => $this->integer(),
            'deleted' => $this->integer().' NOT NULL DEFAULT 0',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%attestation}}');
    }
}
