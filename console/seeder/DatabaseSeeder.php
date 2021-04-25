<?php
namespace console\seeder;

use antonyz89\seeder\TableSeeder;
use console\seeder\tables\UserTableSeeder;
use console\seeder\tables\AdminTableSeeder;

class DatabaseSeeder extends TableSeeder
{

    public const MODEL_COUNT = 10;

	/**
	 * @throws \yii\base\Exception
	 */
	public function run()
    {
		UserTableSeeder::create()->run();
		AdminTableSeeder::create()->run();
    }

}
