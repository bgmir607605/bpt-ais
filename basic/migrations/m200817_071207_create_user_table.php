<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m200817_071207_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'fName' => $this->string(),
            'mName' => $this->string(),
            'lName' => $this->string(),
            'username' => $this->string(),
            'password' => $this->string(),
            'admin' => $this->integer().' NOT NULL DEFAULT 0',
            'schedule' => $this->integer().' NOT NULL DEFAULT 0',
            'inspector' => $this->integer().' NOT NULL DEFAULT 0',
            'teacher' => $this->integer().' NOT NULL DEFAULT 0',
            'groupManager' => $this->integer().' NOT NULL DEFAULT 0',
            'applicantManager' => $this->integer().' NOT NULL DEFAULT 0',
            'student' => $this->integer().' NOT NULL DEFAULT 1',
            'deleted' => $this->integer().' NOT NULL DEFAULT 0',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}
