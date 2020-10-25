<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%monitoring}}`.
 */
class m201025_133723_create_monitoring_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%monitoring}}', [
            'id' => $this->primaryKey(),
            'date' => $this->date(),
            'name' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%monitoring}}');
    }
}
