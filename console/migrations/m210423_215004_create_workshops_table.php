<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%workshops}}`.
 */
class m210423_215004_create_workshops_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%workshops}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%workshops}}');
    }
}
