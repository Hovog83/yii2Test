<?php
return [
	'bootstrap' => ['log', 'queue'],

	'components' => [
		'db' => [
			'class' => 'yii\db\Connection',
			'dsn' => 'mysql:host=127.0.0.1;dbname=test',
			'username' => 'root',
			'password' => '123456',
			'charset' => 'utf8',
		],
		'mailer' => [
			'class' => 'yii\swiftmailer\Mailer',
			'viewPath' => '@common/mail',
			'transport' => [
				'class' => 'Swift_SmtpTransport',
				'host' => 'smtp.mailtrap.io',
				'username' => '746b3270859a5c',
				'password' => 'e3b17bae197c37',
				'port' => '2525',
				'encryption' => 'tls',
			],
		],
		'queue' => [
			'class' => \yii\queue\db\Queue::class,
			'db' => 'db',
			'tableName' => '{{%queue}}', // Table name
			'channel' => 'default', // Queue channel key
			'mutex' => \yii\mutex\MysqlMutex::class, // Mutex used to sync queries
		],
	],
];
