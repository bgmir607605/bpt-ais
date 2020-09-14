<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cabinet}}`.
 */
class m200910_124435_create_cabinet_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('cabinet', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'deleted' => $this->integer().' NOT NULL DEFAULT 0',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('cabinet');
    }
}
