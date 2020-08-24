<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%direct}}`.
 */
class m200817_094223_create_direct_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('direct', [
            'id' => $this->primaryKey(),
            'code' => $this->string(),
            'name' => $this->string(),
            'type' => " SET('СПО','НПО') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'СПО'",
            'forApplicant' => $this->integer().' DEFAULT 0',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('direct');
    }
}
