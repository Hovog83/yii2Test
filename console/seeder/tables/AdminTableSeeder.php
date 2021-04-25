<?php
namespace console\seeder\tables;

use antonyz89\seeder\TableSeeder;
use backend\models\auth\Admin;
use Yii;

/**
 * Handles the creation of seeder `{{%admin}}`.
 */
class AdminTableSeeder extends TableSeeder
{
	/**
	 * {}
	 * @throws \yii\base\Exception
	 */
	public function run()
	{
		$this->insert(Admin::tableName(), [
			'username' => "admin",
			'auth_key' => Yii::$app->security->generateRandomString(),
			'verification_token' => Yii::$app->security->generateRandomString() . '_' . time(),
			'password_hash' => Yii::$app->security->generatePasswordHash("123456"), // password 123456
			'email' => "admin@admin.com",
			'first_name' => "admin",
			'last_name' => "admin",
			'status' => Admin::STATUS_ACTIVE,
		]);
	}

}