<?php

namespace console\seeder\tables;

use antonyz89\seeder\TableSeeder;
use console\seeder\DatabaseSeeder;
use common\models\User;
use Yii;

/**
 * Handles the creation of seeder `{{%user}}`.
 */
class UserTableSeeder extends TableSeeder
{
	/**
	 * {}
	 * @throws \yii\base\Exception
	 */
    public function run()
    {
		if (isset(Yii::$app)) {

			loop(function ($i) {
				$this->insert(User::tableName(), [
					'email' => "user$i@gmail.com",
					'username' => "user$i",
					'auth_key' => Yii::$app->security->generateRandomString(),
					'verification_token' => Yii::$app->security->generateRandomString() . '_' . time(),
					'password_hash' => Yii::$app->security->generatePasswordHash("123456$i"), // password 123456
					'first_name' => "user-$i",
					'last_name' => "user-$i",
					'status' => User::STATUS_ACTIVE,
				]);
			}, DatabaseSeeder::MODEL_COUNT);
		}
    }
}
