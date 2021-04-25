<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m210423_190159_add_first_and_last_name_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('user', 'first_name', $this->string());
		$this->addColumn('user', 'last_name', $this->string());
	}

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn('user', 'first_name');
		$this->dropColumn('user', 'last_name');
	}
}
