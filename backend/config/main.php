<?php
use backend\components\EmailService;
use yii\filters\AccessControl;
use backend\models\auth\Admin;
use yii\log\FileTarget;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
		'emailService' => [
			'class' => EmailService::class
		],
        'request' => [
			'baseUrl' => '/admin',
			'cookieValidationKey' => 'tasd123123',
			'csrfParam' => '_csrf-backend',
        ],
        'user' => [
			'identityClass' => Admin::class,
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_backendUser', 'httpOnly' => true],
        ],
		'session' => [
			'name' => 'PHPBACKSESSID',
			'savePath' => sys_get_temp_dir(),
		],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
    ],
    'params' => $params,
	'as beforeRequest' => [
		'class' => AccessControl::class,
		'rules' => [
			[
				'allow' => true,
				'actions' => ['login'],
			],
			[
				'allow' => true,
				'roles' => ['@'],
			],
		],
		'denyCallback' => function () {
			return Yii::$app->response->redirect(['site/login']);
		},
	],
];
