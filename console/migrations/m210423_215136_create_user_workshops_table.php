<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_workshops}}`.
 */
class m210423_215136_create_user_workshops_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_workshops}}', [
            'id' => $this->primaryKey(),
			'workshops_id' => $this->integer()->notNull(),
			'user_id' => $this->integer()->notNull(),
			'is_send_mail' => $this->integer()->notNull()->defaultValue(0),
        ]);

		$this->createIndex(
			'idx-user_workshops-workshops_id',
			'user_workshops',
			'workshops_id'
		);

		$this->addForeignKey(
			'fk-user_workshops-workshops_id',
			'user_workshops',
			'workshops_id',
			'workshops',
			'id',
			'CASCADE'
		);

		$this->createIndex(
			'idx-user_workshops-user_id',
			'user_workshops',
			'user_id'
		);

		$this->addForeignKey(
			'fk-user_workshops-user_id',
			'user_workshops',
			'user_id',
			'user',
			'id',
			'CASCADE'
		);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropForeignKey(
			'fk-user_workshops-workshops_id',
			'post'
		);

		// drops index for column `author_id`
		$this->dropIndex(
			'idx-user_workshops-workshops_id',
			'post'
		);

		// drops foreign key for table `category`
		$this->dropForeignKey(
			'fk-user_workshops-user_id',
			'post'
		);

		// drops index for column `category_id`
		$this->dropIndex(
			'idx-user_workshops-user_id',
			'post'
		);

		$this->dropTable('{{%user_workshops}}');
    }
}
