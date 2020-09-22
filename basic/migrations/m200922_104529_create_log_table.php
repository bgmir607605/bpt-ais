<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%log}}`.
 */
class m200922_104529_create_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('log', [
            'id' => $this->primaryKey(),
            'userId' => $this->string(). ' default null',
            'action' => $this->string(). ' default null',
            'ip' => $this->string(). ' default null',
            'optional' => $this->string(). ' default null',
            'datetime' => $this->dateTime()->defaultValue( new \yii\db\Expression('NOW()') ),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('log');
    }
}
